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
 * `GetFilesAction` returns a `JSON` array of the files found under the specified directory and subdirectories.
 * This array can be used in Imperavi Redactor to insert files that have been already uploaded.
 *
 * Usage:
 *
 * ```php
 * public function actions()
 * {
 *     return [
 *         'get-image' => [
 *             'class' => 'vova07\imperavi\actions\GetFilesAction',
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics',
 *             'options' => ['only' => ['*.txt', '*.md']],
 *         ]
 *     ];
 * }
 * ```
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07/yii2-imperavi-widget
 */
class GetFilesAction extends Action
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
    public $options = [];

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
            $size = $this->getFileSize($path);
            $url = $this->url . urlencode($file);

            $files[] = [
                'id' => $file,
                'title' => $file,
                'name' => $file,
                'link' => $url,
                'size' => $size,
            ];
        }

        return $files;
    }

    /**
     * @param string $path
     *
     * @return string File size in(B|KB|MB|GB).
     */
    private function getFileSize($path)
    {
        $size = filesize($path);
        $labels = ['B', 'KB', 'MB', 'GB'];
        $factor = (int) floor((strlen($size) - 1) / 3);

        return sprintf("%.1f ", $size / pow(1024, $factor)) . $labels[$factor];
    }
}
