<?php

namespace vova07\imperavi\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\Response;
use vova07\imperavi\helpers\FileHelper;

/**
 * GetAction returns a JSON array of the files found under the specified directory and subdirectories.
 * This array can be used in Imperavi Redactor to insert some files that have already been uploaded.
 *
 * Usage:
 *
 * ```php
 * public function actions()
 * {
 *     return [
 *         'image-upload' => [
 *             'class' => GetAction::className(),
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics',
 *             'type' => GetAction::TYPE_IMAGES,
 *         ]
 *     ];
 * }
 * ```
 */
class GetAction extends Action
{
    const TYPE_IMAGES = 0;
    const TYPE_FILES = 1;

    /**
     * @var string Files directory
     */
    public $path;

    /**
     * @var string Files directory URL
     */
    public $url;

    /**
     * [\vova07\imperavi\helpers\FileHelper::findFiles()|FileHelper::findFiles()] options argument.
     * @var array Options
     */
    public $options = [];

    /**
     * @var int return type (images or files)
     */
    public $type = self::TYPE_IMAGES;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->url === null) {
            throw new InvalidConfigException('The "url" attribute must be set.');
        } else {
            $this->options['url'] = $this->url;
        }
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" attribute must be set.');
        } else {
            $this->path = FileHelper::normalizePath(Yii::getAlias($this->path));
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return FileHelper::findFiles($this->path, $this->options, $this->type);
    }
}
