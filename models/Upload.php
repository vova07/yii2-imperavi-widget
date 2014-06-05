<?php

namespace vova07\imperavi\models;

use yii\base\Model;

/**
 * Class Upload
 * @package vova07\imperavi\models
 *
 * @property \yii\web\UploadedFile|null $file Uploaded file
 */
class Upload extends Model
{
    /**
     * @var \yii\web\UploadedFile|null $file Uploaded file
     */
    public $file;
}
