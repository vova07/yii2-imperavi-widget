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
 * @link https://github.com/vova07/yii2-imperavi-widget
 */
final class GetFilesActionTest extends TestCase
{
    /**
     * Test `GetFilesAction` with valid settings.
     */
    public function testGet()
    {
        $output = Yii::$app->runAction('/default/get-files');

        $this->assertSame(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertTrue(is_array($output));
        $this->assertCount(4, $output);

        foreach ($output as $file) {
            $this->assertArrayHasKey('title', $file);
            $this->assertArrayHasKey('name', $file);
            $this->assertArrayHasKey('link', $file);
            $this->assertArrayHasKey('size', $file);
        }
    }

    /**
     * Test `GetFilesAction` with invalid url property.
     */
    public function testGetInvalidUrl()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "url" attribute must be set');

        Yii::$app->runAction('/default/get-files-invalid-url');
    }

    /**
     * Test `GetFilesAction` with invalid path property.
     */
    public function testGetInvalidPath()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "path" attribute must be set');

        Yii::$app->runAction('/default/get-files-invalid-path');
    }

    /**
     * Test `GetFilesAction` with invalid path alias.
     */
    public function testGetInvalidAlias()
    {
        $this->setExpectedException('yii\base\InvalidParamException');

        Yii::$app->runAction('/default/get-files-invalid-alias');
    }
}
