<?php
/**
 * This file is part of yii2-imperavi-widget.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/vova07/yii2-imperavi-widget
 */

namespace vova07\imperavi;

use yii\web\AssetBundle;

/**
 * Widget asset bundle.
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07/yii2-imperavi-widget
 */
class Asset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vova07/imperavi/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'redactor.css',
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'redactor.min.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @param array $plugins The plugins array to register.
     */
    public function addPlugins($plugins)
    {
        foreach ($plugins as $plugin) {
            if ($plugin === 'clips') {
                $this->css[] = 'plugins/' . $plugin . '/' . $plugin . '.css';
            }
            $this->js[] = 'plugins/' . $plugin . '/' . $plugin . '.js';
        }
    }

    /**
     * @param string $language The language to register.
     */
    public function addLanguage($language)
    {
        $this->js[] = 'lang/' . $language . '.js';
    }
}
