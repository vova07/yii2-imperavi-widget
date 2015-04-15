<?php

namespace vova07\imperavi;

use yii\web\AssetBundle;

/**
 * Widget asset bundle
 *
 * @author Artem Denysov <denysov.artem@gmail.com>
 *
 * @link https://github.com/denar90
 */
class FontAwesome extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/fontawesome';

    /**
     * @inheritdoc
     */
    public $css = [
        '/css/font-awesome.min.css'
    ];
}