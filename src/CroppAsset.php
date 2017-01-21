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
    public $sourcePath = '@bower/cropper/dist';

    /**
     * @inheritdoc
     */
    public $css = [
        'cropper.min.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'cropper.min.js'
    ];
}