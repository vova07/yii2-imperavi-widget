<?php

namespace vova07\imperavi;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget as BaseWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Imperavi Redactor widget.
 *
 * @property array $settings JS Redactor settings
 * @property string|null $selector Textarea selector
 * @property array $plugins JS Redactor plugins
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07/yii2-imperavi-widget
 * @link http://imperavi.com/redactor
 * @license https://github.com/vova07/yii2-imperavi-widget/blob/master/LICENSE.md
 */
class Widget extends BaseWidget
{
    /** Name of inline JavaScript package that is registered by the widget */
    const INLINE_JS_KEY = 'vova07/imperavi/';

    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;

    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;

    /**
     * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
     */
    public $name;

    /**
     * @var string the input value.
     */
    public $value;

    /**
     * @var string|null Selector pointing to textarea to initialize redactor for.
     * Defaults to null meaning that textarea does not exist yet and will be
     * rendered by this widget.
     */
    public $selector;

    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var array {@link http://imperavi.com/redactor/docs/ redactor options}.
     */
    public $settings = [];

    /**
     * @var array Default settings that will be merged with {@link $settings}. Useful with DI container.
     */
    public $defaultSettings = [];

    /**
     * This property must be used only for registering widget custom plugins.
     * The key is the name of the plugin, and the value must be the class name of the plugin bundle.
     * @var array Widget custom plugins key => value array
     */
    public $plugins = [];

    /**
     * @var boolean Depends on this attribute textarea will be rendered or not
     */
    private $_renderTextarea = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->name === null && !$this->hasModel() && $this->selector === null) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
        if (!empty($this->defaultSettings)) {
            $this->settings = ArrayHelper::merge($this->defaultSettings, $this->settings);
        }
        if (isset($this->settings['plugins']) && !is_array($this->settings['plugins']) || !is_array($this->plugins)) {
            throw new InvalidConfigException('The "plugins" property must be an array.');
        }
        if (!isset($this->settings['lang']) && Yii::$app->language !== 'en-US') {
            $this->settings['lang'] = substr(Yii::$app->language, 0, 2);
        }
        if ($this->selector === null) {
            $this->selector = '#' . $this->options['id'];
        } else {
            $this->_renderTextarea = false;
        }

        // @codeCoverageIgnoreStart
        $request = Yii::$app->getRequest();

        if ($request->enableCsrfValidation) {
            $this->settings['uploadImageFields'][$request->csrfParam] = $request->getCsrfToken();
            $this->settings['uploadFileFields'][$request->csrfParam] = $request->getCsrfToken();
        }
        // @codeCoverageIgnoreEnd

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->register();

        if ($this->_renderTextarea === true) {
            if ($this->hasModel()) {
                return Html::activeTextarea($this->model, $this->attribute, $this->options);
            } else {
                return Html::textarea($this->name, $this->value, $this->options);
            }
        }
    }

    /**
     * Register widget translations.
     */
    public static function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['vova07/imperavi']) && !isset(Yii::$app->i18n->translations['vova07/imperavi/*'])) {
            Yii::$app->i18n->translations['vova07/imperavi'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@vova07/imperavi/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'vova07/imperavi' => 'imperavi.php'
                ]
            ];
        }
    }

    /**
     * Register all widget logic.
     */
    protected function register()
    {
        self::registerTranslations();
        $this->registerDefaultCallbacks();
        $this->registerClientScripts();
    }

    /**
     * Register widget asset.
     */
    protected function registerClientScripts()
    {
        $view = $this->getView();
        $selector = Json::encode($this->selector);
        $asset = Yii::$container->get(Asset::className());
        $asset = $asset::register($view);

        if (isset($this->settings['lang'])) {
            $asset->language = $this->settings['lang'];
        }
        if (isset($this->settings['plugins'])) {
            $asset->plugins = $this->settings['plugins'];
        }
        if (!empty($this->plugins)) {
            /** @var \yii\web\AssetBundle $bundle Asset bundle */
            foreach ($this->plugins as $plugin => $bundle) {
                $this->settings['plugins'][] = $plugin;
                $bundle::register($view);
            }
        }

        $settings = !empty($this->settings) ? Json::encode($this->settings) : '';

        $view->registerJs("jQuery($selector).redactor($settings);", $view::POS_READY, self::INLINE_JS_KEY . $this->options['id']);
    }

    /**
     * Register default callbacks.
     */
    protected function registerDefaultCallbacks()
    {
        if (isset($this->settings['imageUpload']) && !isset($this->settings['imageUploadErrorCallback'])) {
            $this->settings['imageUploadErrorCallback'] = new JsExpression('function (response) { alert(response.error); }');
        }
        if (isset($this->settings['fileUpload']) && !isset($this->settings['fileUploadErrorCallback'])) {
            $this->settings['fileUploadErrorCallback'] = new JsExpression('function (response) { alert(response.error); }');
        }
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model && $this->attribute !== null;
    }
}
