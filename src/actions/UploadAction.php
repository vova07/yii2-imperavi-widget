<?php

namespace vova07\imperavi\actions;

use vova07\imperavi\Widget;
use vova07\imperavi\helpers\ImageHelper;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use Yii;

/**
 * Class UploadAction
 * @package vova07\imperavi\actions
 *
 * UploadAction for images and files.
 *
 * Usage:
 *
 * ```php
 * public function actions()
 * {
 *     return [
 *         'upload-image' => [
 *             'class' => 'vova07\imperavi\actions\UploadAction',
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics',
 *             'validatorOptions' => [
 *                 'maxWidth' => 1000,
 *                 'maxHeight' => 1000
 *             ]
 *         ],
 *         'file-upload' => [
 *             'class' => 'vova07\imperavi\actions\UploadAction',
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics',
 *             'uploadOnlyImage' => false,
 *             'validatorOptions' => [
 *                 'maxSize' => 40000
 *             ]
 *         ]
 *     ];
 * }
 * ```
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class UploadAction extends Action
{
    /**
     * @var string Path to directory where files will be uploaded
     */
    public $path;

    /**
     * @var string URL path to directory where files will be uploaded
     */
    public $url;

    /**
     * @var string Validator name
     */
    public $uploadOnlyImage = true;

    /**
     * @var string Variable's name that Imperavi Redactor sent upon image/file upload.
     */
    public $uploadParam = 'file';

    /**
     * @var boolean If `true` unique filename will be generated automatically
     */
    public $unique = true;

    /**
     * @var array Model validator options
     */
    public $validatorOptions = [];

    /**
     * @var string Model validator name
     */
    private $_validator = 'image';

    /**
     * @var string Prefix for cropped image
     */
    private $croppingPrefix = 'crop_';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->url === null) {
            throw new InvalidConfigException('The "url" attribute must be set.');
        } else {
            $this->url = rtrim($this->url, '/') . '/';
        }
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" attribute must be set.');
        } else {
            $this->path = rtrim(Yii::getAlias($this->path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            if (!FileHelper::createDirectory($this->path)) {
                throw new InvalidCallException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
            }
        }
        if ($this->uploadOnlyImage !== true) {
            $this->_validator = 'file';
        }

        Widget::registerTranslations();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isPost) {
            //if data for cropping is present
            if (!empty(Yii::$app->request->post()['src'])
                && !empty(Yii::$app->request->post()['data'])
                && $this->uploadOnlyImage
            ) {
                //make array from json
                $croppingData = json_decode(Yii::$app->request->post()['data'], true);
                //check if croppingData in right format
                if (array_key_exists('x', $croppingData)
                    && array_key_exists('y', $croppingData)
                    && array_key_exists('width', $croppingData)
                    && array_key_exists('height', $croppingData)
                ){
                    //get image name
                    $imageName = basename(Yii::$app->request->post()['src']);
                    //check if image was cropped
                    if (ImageHelper::cropImage($this->path, $this->croppingPrefix, $imageName, $croppingData)) {
                        $result['filelink'] = $this->url . $this->croppingPrefix . $imageName;
                    } else {
                        $result = [
                            'error' => Yii::t('vova07/imperavi', 'ERROR_CAN_NOT_UPLOAD_FILE')
                        ];
                    }
                } else {
                    $result = [
                        'error' => Yii::t('vova07/imperavi', 'ERROR_CAN_NOT_UPLOAD_FILE')
                    ];
                }

                Yii::$app->response->format = Response::FORMAT_JSON;

                return $result;
            } else {
                $file = UploadedFile::getInstanceByName($this->uploadParam);
                $model = new DynamicModel(compact('file'));
                $model->addRule('file', $this->_validator, $this->validatorOptions)->validate();

                if ($model->hasErrors()) {
                    $result = [
                        'error' => $model->getFirstError('file')
                    ];
                } else {
                    if ($this->unique === true && $model->file->extension) {
                        $model->file->name = uniqid() . '.' . $model->file->extension;
                    }
                    if ($model->file->saveAs($this->path . $model->file->name)) {
                        $result = ['filelink' => $this->url . $model->file->name];
                        if ($this->uploadOnlyImage !== true) {
                            $result['filename'] = $model->file->name;
                        }
                    } else {
                        $result = [
                            'error' => Yii::t('vova07/imperavi', 'ERROR_CAN_NOT_UPLOAD_FILE')
                        ];
                    }
                }
                Yii::$app->response->format = Response::FORMAT_JSON;

                return $result;
            }
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }
}
