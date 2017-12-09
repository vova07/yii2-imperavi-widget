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

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\web\Response;

/**
 * `GetImagesAction` returns a `JSON` array of the images found under the specified directory and subdirectories.
 * This array can be used in Imperavi Redactor to insert images that have been already uploaded.
 *
 * Usage:
 *
 * ```php
 * public function actions()
 * {
 *     return [
 *         'get-image' => [
 *             'class' => 'vova07\imperavi\actions\GetImagesAction',
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics',
 *             'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.ico']],
 *         ]
 *     ];
 * }
 * ```
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class GetImagesAction extends Action
{
    /**
     * @var string Files directory path.
     */
    public $path;

    /**
     * @var string Files http URL.
     */
    public $url;

    /**
     * @var array FileHelper options.
     *
     * @see FileHelper::findFiles()
     */
    public $options = ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.ico']];

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
            $this->path = Yii::getAlias($this->path);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $files = [];

        foreach (FileHelper::findFiles($this->path, $this->options) as $path) {
            $file = basename($path);
            $url = $this->url . urlencode($file);

            $files[] = [
                'id' => $file,
                'title' => $file,
                'thumb' => $url,
                'image' => $url,
            ];
        }

        return $files;
    }
}
