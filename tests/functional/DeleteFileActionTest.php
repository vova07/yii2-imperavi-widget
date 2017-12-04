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

use Yii;
use yii\web\Response;

/**
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
final class DeleteFileActionTest extends TestCase
{
    /**
     * Test DeleteFileAction with valid settings and valid file name.
     */
    public function testDeleteFile()
    {
        $_POST['_method'] = 'DELETE';
        $_POST['fileName'] = '2.jpeg';
        Yii::$app->request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $output = Yii::$app->runAction('/default/delete-file');

        $this->assertSame(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertArrayHasKey('url', $output);
        $this->assertSame('/upload/2.jpeg', $output['url']);

        unset($_POST);
    }

    /**
     * Test DeleteFileAction with valid settings and without file name.
     */
    public function testDeleteFileWithoutFileName()
    {
        $_POST['_method'] = 'DELETE';
        Yii::$app->request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $output = Yii::$app->runAction('/default/delete-file');

        $this->assertSame(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertArrayHasKey('error', $output);
        $this->assertSame('ERROR_FILE_IDENTIFIER_MUST_BE_PROVIDED', $output['error']);

        unset($_POST);
    }

    /**
     * Test DeleteFileAction with valid settings and invalid file name.
     */
    public function testDeleteFileWithInvalidFileName()
    {
        $_POST['_method'] = 'DELETE';
        $_POST['fileName'] = 'invalid-file.jpeg';
        Yii::$app->request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $output = Yii::$app->runAction('/default/delete-file');

        $this->assertSame(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertArrayHasKey('error', $output);
        $this->assertSame('ERROR_FILE_DOES_NOT_EXIST', $output['error']);

        unset($_POST);
    }

    /**
     * Test DeleteFileAction with valid settings but not DELETE request.
     */
    public function testDeleteFileNotDelete()
    {
        $this->setExpectedException('yii\web\BadRequestHttpException');

        Yii::$app->runAction('/default/delete-file');
    }

    /**
     * Test UploadAction with invalid url property.
     */
    public function testDeleteFileInvalidUrl()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "url" attribute must be set');

        Yii::$app->runAction('/default/delete-invalid-url');
    }

    /**
     * Test UploadAction with invalid path property.
     */
    public function testDeleteFileInvalidPath()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "path" attribute must be set');

        Yii::$app->runAction('/default/delete-invalid-path');
    }
}
