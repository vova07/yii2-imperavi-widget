<?php

namespace tests;

use tests\data\overrides\TestAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class AssetTest
 * @package tests
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class AssetTest extends TestCase
{
    /**
     * Test asset registering.
     */
    public function testRegister()
    {
        $view = $this->getView();
        $this->assertEmpty($view->assetBundles);
        $asset = TestAsset::register($view);
        $asset->language = 'ru';
        $asset->plugins = ['clips', 'fullscreen'];
        $this->assertEquals(2, count($view->assetBundles));
        $this->assertArrayHasKey(JqueryAsset::className(), $view->assetBundles);
        $this->assertTrue($view->assetBundles[TestAsset::className()] instanceof AssetBundle);
        $content = $view->renderFile('@tests/data/views/rawlayout.php');
        $this->assertContains('redactor.css', $content);
        $this->assertContains('redactor.min.js', $content);
        $this->assertContains('jquery.js', $content);
        $this->assertContains('ru.js', $content);
        $this->assertContains('clips.css', $content);
        $this->assertContains('clips.js', $content);
        $this->assertContains('fullscreen.js', $content);
    }
}
