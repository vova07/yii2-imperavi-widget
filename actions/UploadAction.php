<?php

namespace vova07\imperavi\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\validators\Validator;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use vova07\imperavi\models\Upload;
use vova07\imperavi\Widget;

/**
 * UploadAction for images and files.
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
     * @var array|string a list of file name extensions that are allowed to be uploaded.
     * This can be either an array or a string consisting of file extension names
     * separated by space or comma (e.g. "gif, jpg").
     * Extension names are case-insensitive. Defaults to null, meaning all file name
     * extensions are allowed.
     * @see wrongType
     */
    public $types;
    /**
     * @var integer the minimum number of bytes required for the uploaded file.
     * Defaults to null, meaning no limit.
     * @see tooSmall
     */
    public $minSize;
    /**
     * @var integer the maximum number of bytes required for the uploaded file.
     * Defaults to null, meaning no limit.
     * Note, the size limit is also affected by 'upload_max_filesize' INI setting
     * and the 'MAX_FILE_SIZE' hidden field value.
     * @see tooBig
     */
    public $maxSize;
    /**
     * @var string the error message used when a file is not uploaded correctly.
     */
    public $message;
    /**
     * @var string the error message used when no file is uploaded.
     */
    public $uploadRequired;
    /**
     * @var string the error message used when the uploaded file is too large.
     * You may use the following tokens in the message:
     *
     * - {attribute}: the attribute name
     * - {file}: the uploaded file name
     * - {limit}: the maximum size allowed (see [[getSizeLimit()]])
     */
    public $tooBig;
    /**
     * @var string the error message used when the uploaded file is too small.
     * You may use the following tokens in the message:
     *
     * - {attribute}: the attribute name
     * - {file}: the uploaded file name
     * - {limit}: the value of [[minSize]]
     */
    public $tooSmall;
    /**
     * @var string the error message used when the uploaded file has an extension name
     * that is not listed in [[types]]. You may use the following tokens in the message:
     *
     * - {attribute}: the attribute name
     * - {file}: the uploaded file name
     * - {extensions}: the list of the allowed extensions.
     */
    public $wrongType;

    /**
     * @var string the error message used when the uploaded file is not an image.
     * You may use the following tokens in the message:
     *
     * - {attribute}: the attribute name
     * - {file}: the uploaded file name
     */
    public $notImage;
    /**
     * @var integer the minimum width in pixels.
     * Defaults to null, meaning no limit.
     * @see underWidth
     */
    public $minWidth;
    /**
     * @var integer the maximum width in pixels.
     * Defaults to null, meaning no limit.
     * @see overWidth
     */
    public $maxWidth;
    /**
     * @var integer the minimum height in pixels.
     * Defaults to null, meaning no limit.
     * @see underHeight
     */
    public $minHeight;
    /**
     * @var integer the maximum width in pixels.
     * Defaults to null, meaning no limit.
     * @see overWidth
     */
    public $maxHeight;
    /**
     * @var array|string a list of file mime types that are allowed to be uploaded.
     * This can be either an array or a string consisting of file mime types
     * separated by space or comma (e.g. "image/jpeg, image/png").
     * Mime type names are case-insensitive. Defaults to null, meaning all mime types
     * are allowed.
     * @see wrongMimeType
     */
    public $mimeTypes;
    /**
     * @var string the error message used when the image is under [[minWidth]].
     * You may use the following tokens in the message:
     *
     * - {attribute}: the attribute name
     * - {file}: the uploaded file name
     * - {limit}: the value of [[minWidth]]
     */
    public $underWidth;
    /**
     * @var string the error message used when the image is over [[maxWidth]].
     * You may use the following tokens in the message:
     *
     * - {attribute}: the attribute name
     * - {file}: the uploaded file name
     * - {limit}: the value of [[maxWidth]]
     */
    public $overWidth;
    /**
     * @var string the error message used when the image is under [[minHeight]].
     * You may use the following tokens in the message:
     *
     * - {attribute}: the attribute name
     * - {file}: the uploaded file name
     * - {limit}: the value of [[minHeight]]
     */
    public $underHeight;
    /**
     * @var string the error message used when the image is over [[maxHeight]].
     * You may use the following tokens in the message:
     *
     * - {attribute}: the attribute name
     * - {file}: the uploaded file name
     * - {limit}: the value of [[maxHeight]]
     */
    public $overHeight;
    /**
     * @var string the error message used when the file has an mime type
     * that is not listed in [[mimeTypes]].
     * You may use the following tokens in the message:
     *
     * - {attribute}: the attribute name
     * - {file}: the uploaded file name
     * - {mimeTypes}: the value of [[mimeTypes]]
     */
    public $wrongMimeType;

    /**
     * @var array|null Model validator options
     */
    private $_validatorOptions;

    /**
     * @var string Model validator name
     */
    private $_validator = 'image';

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
            $this->path = FileHelper::normalizePath($this->path) . DIRECTORY_SEPARATOR;

            if (!FileHelper::createDirectory($this->path)) {
                throw new InvalidCallException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
            }
        }
        $this->_validatorOptions = [
            'types' => $this->types,
            'minSize' => $this->minSize,
            'maxSize' => $this->maxSize,
            'message' => $this->message,
            'uploadRequired' => $this->uploadRequired,
            'tooBig' => $this->tooBig,
            'tooSmall' => $this->tooSmall,
            'wrongType' => $this->wrongType
        ];
        if ($this->uploadOnlyImage === true) {
            $this->_validatorOptions['notImage'] = $this->notImage;
            $this->_validatorOptions['minWidth'] = $this->minWidth;
            $this->_validatorOptions['maxWidth'] = $this->maxWidth;
            $this->_validatorOptions['minHeight'] = $this->minHeight;
            $this->_validatorOptions['maxHeight'] = $this->maxHeight;
            $this->_validatorOptions['mimeTypes'] = $this->mimeTypes;
            $this->_validatorOptions['underWidth'] = $this->underWidth;
            $this->_validatorOptions['overWidth'] = $this->overWidth;
            $this->_validatorOptions['underHeight'] = $this->underHeight;
            $this->_validatorOptions['overHeight'] = $this->overHeight;
            $this->_validatorOptions['wrongMimeType'] = $this->wrongMimeType;
        } else {
            $this->_validator = 'file';
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isPost) {
            $model = new Upload();
            $validator = Validator::createValidator($this->_validator, $model, $model->attributes(), $this->_validatorOptions);
            $model->validators[] = $validator;
            $model->file = UploadedFile::getInstanceByName($this->uploadParam);

            if ($model->validate()) {
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
                        'error' => Widget::t('ERROR_CAN_NOT_UPLOAD_FILE')
                    ];
                }
            } else {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }
}
