<?php

namespace vova07\imperavi;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\widgets\InputWidget;
use Yii;

/**
 * Imperavi Redactor widget.
 *
 * @property string $settings
 * @property string $selector
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @version 1.1.4
 *
 * @link https://github.com/vova07/yii2-imperavi-widget
 * @link http://imperavi.com/redactor
 * @license https://github.com/vova07/yii2-imperavi-widget/blob/master/LICENSE.md
 */
class Widget extends InputWidget
{
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

        $this->registerTranslations();
        if (isset($this->settings['plugins']) && !is_array($this->settings['plugins'])) {
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
        $this->settings['uploadImageFields'][Yii::$app->request->csrfParam] = Yii::$app->request->getCsrfToken();
        $this->settings['uploadFileFields'][Yii::$app->request->csrfParam] = Yii::$app->request->getCsrfToken();
    }

    /**
     * Register widget translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['imperavi'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@vova07/imperavi/messages',
            'forceTranslation' => true
        ];
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
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $selector = Json::encode($this->selector);
        $asset = Asset::register($view);

        if (isset($this->settings['lang'])) {
            $asset->language = $this->settings['lang'];
        }
        if (isset($this->settings['plugins'])) {
            $asset->plugins = $this->settings['plugins'];
        }
        if (!empty($this->plugins)) {
            foreach ($this->plugins as $plugin => $bundle) {
                $this->settings['plugins'][] = $plugin;
                $bundle::register($view);
            }
        }

        $settings = !empty($this->settings) ? Json::encode($this->settings) : '';

        $view->registerJs("jQuery($selector).redactor($settings);");
    }
}
