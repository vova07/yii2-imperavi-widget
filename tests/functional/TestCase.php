<?php

namespace tests;

use yii\helpers\ArrayHelper;
use yii\web\AssetManager;
use yii\web\View;

/**
 * This is the base class for all yii framework unit tests.
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    public static $params;

    /**
     * Mock application prior running tests.
     */
    protected function setUp()
    {
        $this->mockWebApplication(
            [
                'components' => [
                    'request' => [
                        'class' => 'yii\web\Request',
                        'url' => '/test',
                        'enableCsrfValidation' => false
                    ],
                    'response' => [
                        'class' => 'yii\web\Response'
                    ]
                ]
            ]
        );
    }

    /**
     * Clean up after test.
     * By default the application created with [[mockApplication]] will be destroyed.
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->destroyApplication();
    }

    protected function mockApplication($config = [], $appClass = '\yii\console\Application')
    {
        new $appClass(
            ArrayHelper::merge(
                [
                    'id' => 'testapp',
                    'basePath' => __DIR__,
                    'vendorPath' => $this->getVendorPath()
                ],
                $config
            )
        );
    }

    protected function mockWebApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
            'components' => [
                'request' => [
                    'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
                    'scriptFile' => __DIR__ .'/index.php',
                    'scriptUrl' => '/index.php'
                ],
                'assetManager' => [
                    'basePath' => '@tests/data/assets',
                    'baseUrl' => '/'
                ]
            ]
        ], $config));
    }

    protected function getVendorPath()
    {
        return dirname(dirname(__DIR__)) . '/vendor';
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        \Yii::$app = null;
    }

    /**
     * Creates a view for testing purposes
     *
     * @return View
     */
    protected function getView()
    {
        $view = new View();
        $view->setAssetManager(new AssetManager([
            'basePath' => '@tests/data/assets',
            'baseUrl' => '/'
        ]));
        return $view;
    }

    /**
     * Asserting two strings equality ignoring line endings
     *
     * @param string $expected
     * @param string $actual
     */
    public function assertEqualsWithoutLE($expected, $actual)
    {
        $expected = str_replace("\r\n", "\n", $expected);
        $actual = str_replace("\r\n", "\n", $actual);
        $this->assertEquals($expected, $actual);
    }
}
