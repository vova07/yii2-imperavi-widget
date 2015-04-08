<?php

namespace tests;

use org\bovigo\vfs\vfsStream;
use Yii;
use yii\web\Response;

/**
 * Class GetActionTest
 * @package tests
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class GetActionTest extends TestCase
{
    /**
     * Test GetAction with valid settings.
     */
    public function testGet()
    {
        $output = Yii::$app->runAction('/default/get');
        $this->assertEquals(Response::FORMAT_JSON, Yii::$app->getResponse()->format);
        $this->assertTrue(is_array($output));
        $this->assertCount(4, $output);
        $this->assertArrayHasKey('title', $output[0]);
    }

    /**
     * Test GetAction with invalid url property.
     */
    public function testGetInvalidUrl()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "url" attribute must be set');
        Yii::$app->runAction('/default/get-invalid-url');
    }

    /**
     * Test GetAction with invalid path property.
     */
    public function testGetInvalidPath()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "path" attribute must be set');
        Yii::$app->runAction('/default/get-invalid-path');
    }

    /**
     * Test GetAction with invalid path alias.
     */
    public function testGetInvalidAlias()
    {
        $this->setExpectedException('yii\base\InvalidParamException');
        Yii::$app->runAction('/default/get-invalid-alias');
    }
}
