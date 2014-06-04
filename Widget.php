<?php

namespace vova07\imperavi;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * ImperaviRedactor class file.
 *
 * @property string $assetsPath
 * @property string $assetsUrl
 * @property array $plugins
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 *
 * @version 1.2.14
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
     * @var boolean Depends on this attribute textarea will be rendered or not
     */
    private $_renderTextarea = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (isset($this->settings['plugins']) && !is_array($this->settings['plugins'])) {
            throw new InvalidConfigException('The "plugins" property must be an array.');
        }
        if (!isset($this->settings['lang']) && Yii::$app->language !== 'en') {
            $this->settings['lang'] = Yii::$app->language;
        }
        if ($this->selector === null) {
            $this->selector = $this->hasModel() ? '#' . Html::getInputId($this->model, $this->attribute) : '#' . $this->getId();
        } else {
            $this->_renderTextarea = false;
        }
        $this->settings['uploadFields'][Yii::$app->request->csrfParam] = Yii::$app->request->getCsrfToken();
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
        $settings = !empty($this->settings) ? Json::encode($this->settings) : '';
        $selector = Json::encode($this->selector);
        $asset = Asset::register($view);

        if (isset($this->settings['lang'])) {
            $asset->language = $this->settings['lang'];
        }
        if (isset($this->settings['plugins'])) {
            $asset->plugins = $this->settings['plugins'];
        }

        $view->registerJs("jQuery($selector).redactor($settings);");
    }
}
