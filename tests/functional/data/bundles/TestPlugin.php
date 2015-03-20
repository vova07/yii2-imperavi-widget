<?php

namespace tests\data\bundles;

use yii\web\AssetBundle;

/**
 * Class TestPlugin
 * @package tests\data\bundles
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class TestPlugin extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@tests/../../src/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'redactor.min.js'
    ];
}
