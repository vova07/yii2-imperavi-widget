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
final class GetImagesActionTest extends TestCase
{
    /**
     * Test `GetImagesAction` with valid settings.
     */
    public function testGet()
    {
        $output = Yii::$app->runAction('/default/get-images');

        $this->assertSame(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertTrue(is_array($output));
        $this->assertCount(2, $output);

        foreach ($output as $image) {
            $this->assertArrayHasKey('title', $image);
            $this->assertArrayHasKey('thumb', $image);
            $this->assertArrayHasKey('image', $image);
        }
    }

    /**
     * Test `GetImagesAction` with invalid url property.
     */
    public function testGetInvalidUrl()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "url" attribute must be set');

        Yii::$app->runAction('/default/get-images-invalid-url');
    }

    /**
     * Test `GetImagesAction` with invalid path property.
     */
    public function testGetInvalidPath()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "path" attribute must be set');

        Yii::$app->runAction('/default/get-images-invalid-path');
    }

    /**
     * Test `GetImagesAction` with invalid path alias.
     */
    public function testGetInvalidAlias()
    {
        $this->setExpectedException('yii\base\InvalidParamException');

        Yii::$app->runAction('/default/get-images-invalid-alias');
    }
}
