<?php

namespace tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitorTestCase;
use vova07\imperavi\helpers\FileHelper;
use Yii;
use yii\web\Response;

/**
 * Class UploadActionTest
 * @package tests
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class UploadActionTest extends TestCase
{
    /**
     * Test UploadAction with valid settings.
     */
    public function testUpload()
    {
        $filePath = vfsStream::url(self::ROOT_DIRECTORY . '/' . self::UPLOAD_DIRECTORY . '/1.jpeg');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [
            'file' => [
                'name' => '1.jpeg',
                'tmp_name' => $filePath,
                'type' => $this->getVirtualFileMimeType($filePath),
                'size' => filesize($filePath),
                'error' => UPLOAD_ERR_OK
            ]

        ];
        $output = Yii::$app->runAction('/default/upload');

        $this->assertEquals(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertArrayHasKey('error', $output);
        $this->assertContains('ERROR_CAN_NOT_UPLOAD_FILE', $output['error']);

        unset($_FILES);
        unset($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Test UploadAction with valid settings and invalid file.
     */
    public function testUploadCanNotUploadFile()
    {
        $filePath = vfsStream::url(self::ROOT_DIRECTORY . '/' . self::UPLOAD_DIRECTORY . '/2.jpeg');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [
            'file' => [
                'name' => '2.jpeg',
                'tmp_name' => $filePath,
                'type' => $this->getVirtualFileMimeType($filePath),
                'size' => filesize($filePath),
                'error' => UPLOAD_ERR_OK
            ]

        ];
        $output = Yii::$app->runAction('/default/upload');

        $this->assertEquals(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertArrayHasKey('error', $output);
        $this->assertContains('ERROR_CAN_NOT_UPLOAD_FILE', $output['error']);

        unset($_FILES);
        unset($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Test UploadAction with valid settings and to small file.
     */
    public function testUploadWithTooSmallFile()
    {
        $filePath = vfsStream::url(self::ROOT_DIRECTORY . '/' . self::UPLOAD_DIRECTORY . '/2.jpeg');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [
            'file' => [
                'name' => '2.jpeg',
                'tmp_name' => $filePath,
                'type' => $this->getVirtualFileMimeType($filePath),
                'size' => filesize($filePath),
                'error' => UPLOAD_ERR_OK
            ]

        ];
        $output = Yii::$app->runAction('/default/upload-max-size');

        $this->assertEquals(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertArrayHasKey('error', $output);
        $this->assertContains('is too big. Its size cannot exceed 10 bytes', $output['error']);

        unset($_FILES);
        unset($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Test UploadAction with valid settings but not POST request.
     */
    public function testUploadNotPost()
    {
        $this->setExpectedException('yii\web\BadRequestHttpException');
        Yii::$app->runAction('/default/upload-file');
    }

    /**
     * Test UploadAction with invalid url property.
     */
    public function testUploadInvalidUrl()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "url" attribute must be set');
        Yii::$app->runAction('/default/upload-invalid-url');
    }

    /**
     * Test UploadAction with invalid path property.
     */
    public function testUploadInvalidPath()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "path" attribute must be set');
        Yii::$app->runAction('/default/upload-invalid-path');
    }
}
