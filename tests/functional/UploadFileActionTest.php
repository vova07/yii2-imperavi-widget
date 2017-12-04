<?php
/**
 * This file is part of yii2-imperavi-widget.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/vova07/yii2-imperavi-widget
 */

namespace vova07\imperavi\tests\functional;

use org\bovigo\vfs\vfsStream;
use Yii;
use yii\web\Response;

/**
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
final class UploadFileActionTest extends TestCase
{
    /**
     * Test UploadAction with valid settings and invalid file.
     */
    public function testUploadCannotUploadFile()
    {
        $filePath = vfsStream::url(self::ROOT_DIRECTORY . '/' . self::UPLOAD_DIRECTORY . '/2.jpeg');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [
            'file' => [
                'name' => '2.jpeg',
                'tmp_name' => $filePath,
                'type' => $this->getVirtualFileMimeType($filePath),
                'size' => filesize($filePath),
                'error' => UPLOAD_ERR_OK,
            ],

        ];
        $output = Yii::$app->runAction('/default/upload-image');

        $this->assertSame(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertArrayHasKey('error', $output);
        $this->assertContains('ERROR_CAN_NOT_UPLOAD_FILE', $output['error']);

        unset($_FILES);
        unset($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Test UploadAction with valid settings and invalid file.
     */
    public function testUploadFileWithSameName()
    {
        $filePath = vfsStream::url(self::ROOT_DIRECTORY . '/' . self::UPLOAD_DIRECTORY . '/2.jpeg');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [
            'file' => [
                'name' => '2.jpeg',
                'tmp_name' => $filePath,
                'type' => $this->getVirtualFileMimeType($filePath),
                'size' => filesize($filePath),
                'error' => UPLOAD_ERR_OK,
            ],

        ];
        $output = Yii::$app->runAction('/default/upload-image-not-unique');

        $this->assertSame(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertArrayHasKey('error', $output);
        $this->assertContains('ERROR_FILE_ALREADY_EXIST', $output['error']);

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
                'error' => UPLOAD_ERR_OK,
            ],

        ];
        $output = Yii::$app->runAction('/default/upload-image-max-size');

        $this->assertSame(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertArrayHasKey('error', $output);
        $this->assertContains('is too big. Its size cannot exceed 10 B', $output['error']);

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

        Yii::$app->runAction('/default/upload-image-invalid-url');
    }

    /**
     * Test UploadAction with invalid path property.
     */
    public function testUploadInvalidPath()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "path" attribute must be set');

        Yii::$app->runAction('/default/upload-image-invalid-path');
    }
}
