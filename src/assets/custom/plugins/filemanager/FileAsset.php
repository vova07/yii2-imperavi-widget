<?php

namespace vova07\imperavi\assets\custom\plugins\filemanager;

use yii\web\AssetBundle;

/**
 * Widget asset bundle
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class FileAsset extends AssetBundle
{
	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@vova07/imperavi/assets';

	public $js = [
		'custom/plugins/filemanager/filemanager.js'
	];
}
