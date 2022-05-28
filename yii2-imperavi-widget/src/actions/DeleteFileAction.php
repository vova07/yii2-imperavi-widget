<?php
/**
 * This file is part of yii2-imperavi-widget.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/vova07/yii2-imperavi-widget
 */

namespace vova07\imperavi\actions;

use vova07\imperavi\Widget;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * UploadAction for images and files.
 *
 * Usage:
 *
 * ```php
 * public function actions()
 * {
 *     return [
 *         'delete-file' => [
 *             'class' => 'vova07\imperavi\actions\DeleteFileAction',
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics',
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07/yii2-imperavi-widget
 */
class DeleteFileAction extends Action
{
    /**
     * @var string Path to directory where files will be uploaded.
     */
    public $path;

    /**
     * @var string URL path to directory where files will be uploaded.
     */
    public $url;

    /**
     * @var string AJAX attribute name will contain the file identifier.
     */
    public $attribute = 'fileName';

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
        }

        Widget::registerTranslations();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isDelete && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $fileName = Yii::$app->request->post($this->attribute, null);

            if ($fileName === null) {
                return ['error' => Yii::t('vova07/imperavi', 'ERROR_FILE_IDENTIFIER_MUST_BE_PROVIDED')];
            }

            $file = $this->path . DIRECTORY_SEPARATOR . $fileName;

            if (!file_exists($file)) {
                return ['error' => Yii::t('vova07/imperavi', 'ERROR_FILE_DOES_NOT_EXIST')];
            }

            if (!unlink($file)) {
                return ['error' => Yii::t('vova07/imperavi', 'ERROR_CANNOT_REMOVE_FILE')];
            }

            return ['url' => $this->url . urlencode($fileName)];
        } else {
            throw new BadRequestHttpException('Only DELETE AJAX request is allowed');
        }
    }
}
