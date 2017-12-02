<?php

namespace vova07\imperavi\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\Response;
use yii\web\BadRequestHttpException;

/**
 * Class DeleteAction
 * @package vova07\imperavi\actions
 *
 * DeleteAction for images and files.
 *
 * Usage:
 *
 * ```php
 * public function actions()
 * {
 *     return [
 *         'images-delete' => [
 *             'class' => DeleteAction::className(),
 *             'filename' => Yii::$app->getRequest()->get('filename'),
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics'
 *         ],
 *         'files-delete' => [
 *              'class' => DeleteAction::className(),
 *              'filename' => Yii::$app->getRequest()->get('filename'),
 *              'url' => 'http://my-site.com/statics/', // Directory URL address, where files are stored.
 *              'path' => '/var/www/my-site.com/web/statics', // Or absolute path to directory where files are stored.
 *          ],
 *     ];
 * }
 * ```
 *
 * @link https://github.com/vova07
 */
class DeleteAction extends Action
{
    /**
     * @var string File name
     */
    public $filename;

    /**
     * @var string Files directory
     */
    public $path;

    /**
     * @var string Files directory URL
     */
    public $url;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->filename === null) {
            throw new InvalidConfigException('The "filename" attribute must be set.');
        }
        if ($this->url === null) {
            throw new InvalidConfigException('The "url" attribute must be set.');
        }
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" attribute must be set.');
        } else {
            $this->path = Yii::getAlias($this->path);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isAjax) {

            $file = $this->path . $this->filename;

            if(file_exists($file)){
                unlink($file);
            }

            $list = [
                'url' => $this->url . $this->filename,
            ];

            Yii::$app->response->format = Response::FORMAT_JSON;

            return $list;
        } else {
            throw new BadRequestHttpException('Only AJAX is allowed');
        }
    }
}
