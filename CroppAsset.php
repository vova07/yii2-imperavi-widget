<?php

namespace vova07\imperavi;

use yii\web\AssetBundle;

/**
 * Widget asset bundle
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
		'cropper.css',
		'http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css',
	];

	/**
	 * @inheritdoc
	 */
	public $js = [
		'cropper.js',
	];
}
