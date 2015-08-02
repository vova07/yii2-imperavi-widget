<?php

namespace vova07\imperavi\assets\custom\plugins\imagemanager;

use yii\web\AssetBundle;

/**
 * Widget asset bundle
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class ImageAsset extends AssetBundle
{
	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@vova07/imperavi/assets';

	public $js = [
		'custom/plugins/imagemanager/imagemanager.js'
	];
}
