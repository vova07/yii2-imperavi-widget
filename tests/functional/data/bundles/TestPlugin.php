<?php
/**
 * This file is part of yii2-imperavi-widget.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/vova07/yii2-imperavi-widget
 */

namespace vova07\imperavi\tests\functional\data\bundles;

use yii\web\AssetBundle;

/**
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
final class TestPlugin extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vova07/imperavi/tests/../../src/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'redactor.min.js',
    ];
}
