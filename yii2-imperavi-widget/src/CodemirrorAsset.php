<?php

namespace vova07\imperavi;

use yii\web\AssetBundle;

/**
 * Codemirror asset bundle.
 *
 * @author NickGoodwind <nickgoodwind@gmail.com>
 */
class CodemirrorAsset extends AssetBundle
{
    public $sourcePath = '@vova07/imperavi/assets';
    
    public $js = [
        'codemirror/codemirror.js',
        'codemirror/mode/htmlmixed/htmlmixed.js',
        'codemirror/mode/javascript/javascript.js',
    ];

    public $css = [
        'codemirror/codemirror.css'
    ];

    public $depends = [
        'app\modules\admin\assets\AdminAppAsset'
    ];
}
