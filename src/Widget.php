<?php

namespace vova07\imperavi;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use Yii;

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
class Widget extends InputWidget
{
    /** Name of inline JavaScript package that is registered by the widget */
    const INLINE_JS_KEY = 'vova07/imperavi/';

    /**
     * @var array {@link http://imperavi.com/redactor/docs/ redactor options}.
     */
    public $settings = [];

    /**
     * @var string|null Selector pointing to textarea to initialize redactor for.
     * Defaults to null meaning that textarea does not exist yet and will be
     * rendered by this widget.
     */
    public $selector;

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
        parent::init();

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

        self::registerTranslations();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();

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
     * Register widget asset.
     */
    public function registerClientScript()
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
}
