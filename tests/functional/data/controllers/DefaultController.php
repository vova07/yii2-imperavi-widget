<?php

namespace tests\data\controllers;

use org\bovigo\vfs\vfsStream;
use tests\TestCase;
use vova07\imperavi\actions\GetAction;
use vova07\imperavi\actions\UploadAction;
use yii\web\Controller;

/**
 * Class DefaultController
 * @package tests\data\controllers
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'get' => [
                'class' => GetAction::className(),
                'url' => '/statics/',
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::STATICS_DIRECTORY)
            ],
            'get-invalid-url' => [
                'class' => GetAction::className(),
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::STATICS_DIRECTORY)
            ],
            'get-invalid-path' => [
                'class' => GetAction::className(),
                'url' => '/statics/'
            ],
            'get-invalid-alias' => [
                'class' => GetAction::className(),
                'url' => '/statics/',
                'path' => '@invalid/data/statics'
            ],
            'upload' => [
                'class' => UploadAction::className(),
                'url' => '/upload/',
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::UPLOAD_DIRECTORY)
            ],
            'upload-max-size' => [
                'class' => UploadAction::className(),
                'url' => '/upload/',
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::UPLOAD_DIRECTORY),
                'validatorOptions' => [
                    'maxSize' => 10
                ]
            ],
            'upload-file' => [
                'class' => UploadAction::className(),
                'url' => '/upload/',
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::UPLOAD_DIRECTORY),
                'uploadOnlyImage' => false
            ],
            'upload-invalid-url' => [
                'class' => UploadAction::className(),
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::UPLOAD_DIRECTORY)
            ],
            'upload-invalid-path' => [
                'class' => UploadAction::className(),
                'url' => '/upload/'
            ]
        ];
    }
}

