<?php
/**
 * This file is part of yii2-imperavi-widget.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/vova07/yii2-imperavi-widget
 */

namespace vova07\imperavi\tests\functional\data\controllers;

use org\bovigo\vfs\vfsStream;
use vova07\imperavi\actions\GetFilesAction;
use vova07\imperavi\actions\GetImagesAction;
use vova07\imperavi\actions\UploadAction;
use vova07\imperavi\tests\functional\TestCase;
use yii\web\Controller;

/**
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
final class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'get-images' => [
                'class' => GetImagesAction::className(),
                'url' => '/statics/',
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::STATICS_DIRECTORY),
            ],
            'get-images-invalid-url' => [
                'class' => GetImagesAction::className(),
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::STATICS_DIRECTORY),
            ],
            'get-images-invalid-path' => [
                'class' => GetImagesAction::className(),
                'url' => '/statics/',
            ],
            'get-images-invalid-alias' => [
                'class' => GetImagesAction::className(),
                'url' => '/statics/',
                'path' => '@invalid/data/statics',
            ],
            'get-files' => [
                'class' => GetFilesAction::className(),
                'url' => '/statics/',
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::STATICS_DIRECTORY),
            ],
            'get-files-invalid-url' => [
                'class' => GetFilesAction::className(),
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::STATICS_DIRECTORY),
            ],
            'get-files-invalid-path' => [
                'class' => GetFilesAction::className(),
                'url' => '/statics/',
            ],
            'get-files-invalid-alias' => [
                'class' => GetFilesAction::className(),
                'url' => '/statics/',
                'path' => '@invalid/data/statics',
            ],
            'upload' => [
                'class' => UploadAction::className(),
                'url' => '/upload/',
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::UPLOAD_DIRECTORY),
            ],
            'upload-max-size' => [
                'class' => UploadAction::className(),
                'url' => '/upload/',
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::UPLOAD_DIRECTORY),
                'validatorOptions' => [
                    'maxSize' => 10,
                ],
            ],
            'upload-file' => [
                'class' => UploadAction::className(),
                'url' => '/upload/',
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::UPLOAD_DIRECTORY),
                'uploadOnlyImage' => false,
            ],
            'upload-invalid-url' => [
                'class' => UploadAction::className(),
                'path' => vfsStream::url(TestCase::ROOT_DIRECTORY . '/' . TestCase::UPLOAD_DIRECTORY),
            ],
            'upload-invalid-path' => [
                'class' => UploadAction::className(),
                'url' => '/upload/',
            ],
        ];
    }
}

