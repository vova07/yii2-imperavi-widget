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
class CroppAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vova07/imperavi/assets';
    /**
     * @inheritdoc
     */
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/cropper/0.9.1/cropper.min.css',
        'http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/cropper/0.9.1/cropper.js',
    ];
}