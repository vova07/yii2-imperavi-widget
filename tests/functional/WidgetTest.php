<?php

namespace tests;

use ReflectionClass;
use tests\data\models\Model;
use tests\data\overrides\TestWidget;
use Yii;
use yii\web\View;

/**
 * Class WidgetTest
 * @package tests
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class WidgetTest extends TestCase
{
    /**
     * Test render with model property and empty attribute value.
     */
    public function testRenderWithModel()
    {
        $output = TestWidget::widget([
            'model' => new Model(),
            'attribute' => 'message'
        ]);
        $expected = '<textarea id="model-message" name="Model[message]"></textarea>';
        $this->assertEqualsWithoutLE($expected, $output);
    }

    /**
     * Test render with model property and default attribute value.
     */
    public function testRenderWithModelAndAttributeValue()
    {
        $model = new Model();
        $model->message = 'test-value';

        $output = TestWidget::widget([
            'model' => $model,
            'attribute' => 'message'
        ]);
        $expected = '<textarea id="model-message" name="Model[message]">test-value</textarea>';
        $this->assertEqualsWithoutLE($expected, $output);
    }

    /**
     * Test render with model property and custom id.
     */
    public function testRenderWithModelAndCustomId()
    {
        $output = TestWidget::widget([
            'options' => [
                'id' => 'test-id'
            ],
            'model' => new Model(),
            'attribute' => 'message'
        ]);
        $expected = '<textarea id="test-id" name="Model[message]"></textarea>';
        $this->assertEqualsWithoutLE($expected, $output);
    }

    /**
     * Test render with name and value property.
     */
    public function testRenderWithNameAndValue()
    {
        $output = TestWidget::widget(
            [
                'name' => 'test-name',
                'value' => 'test-value'
            ]
        );
        $expected = '<textarea id="w0" name="test-name">test-value</textarea>';
        $this->assertEqualsWithoutLE($expected, $output);
    }

    /**
     * Test render with name property and empty value and custom id.
     */
    public function testRenderWithNameAndCustomId()
    {
        $output = TestWidget::widget(
            [
                'id' => 'test-id',
                'name' => 'test-name'
            ]
        );
        $expected = '<textarea id="test-id" name="test-name"></textarea>';
        $this->assertEqualsWithoutLE($expected, $output);
    }

    /**
     * Test render with predefined selector.
     */
    public function testRenderWithPredefinedSelector()
    {
        $output = TestWidget::widget(
            [
                'selector' => 'test-selector',
                'name' => 'test-name'
            ]
        );
        $expected = '';
        $this->assertEqualsWithoutLE($expected, $output);
    }

    /**
     * Test script registering.
     */
    public function testRegisterClientScriptMethod()
    {
        $class = new ReflectionClass(TestWidget::className());
        $method = $class->getMethod('registerClientScript');
        $method->setAccessible(true);
        $model = new Model();
        $widget = TestWidget::begin(
            [
                'model' => $model,
                'attribute' => 'message'
            ]
        );
        $view = $this->getView();
        $widget->setView($view);
        $method->invoke($widget);
        $test = 'jQuery("#model-message").redactor();';

        $this->assertArrayHasKey(TestWidget::INLINE_JS_KEY, $view->js[View::POS_READY]);
        $this->assertEquals($test, $view->js[View::POS_READY][TestWidget::INLINE_JS_KEY]);
    }

    /**
     * Test script registering.
     */
    public function testRegisterClientScriptMethodWithSettings()
    {
        $class = new ReflectionClass(TestWidget::className());
        $method = $class->getMethod('registerClientScript');
        $method->setAccessible(true);
        $model = new Model();
        $widget = TestWidget::begin(
            [
                'model' => $model,
                'attribute' => 'message',
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'plugins' => [
                        'clips',
                        'fullscreen'
                    ]
                ]
            ]
        );
        $view = $this->getView();
        $widget->setView($view);
        $method->invoke($widget);
        $test = 'jQuery("#model-message").redactor({"lang":"ru","minHeight":200,"plugins":["clips","fullscreen"]});';

        $this->assertArrayHasKey(TestWidget::INLINE_JS_KEY, $view->js[View::POS_READY]);
        $this->assertEquals($test, $view->js[View::POS_READY][TestWidget::INLINE_JS_KEY]);
    }

    /**
     * Test translations registering.
     */
    public function testRegisterTranslationsMethod()
    {
        $class = new ReflectionClass(TestWidget::className());
        $method = $class->getMethod('registerTranslations');
        $method->setAccessible(true);
        $model = new Model();
        $widget = TestWidget::begin(
            [
                'model' => $model,
                'attribute' => 'message'
            ]
        );
        $view = $this->getView();
        $widget->setView($view);
        $method->invoke($widget);
        $test = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@vova07/imperavi/messages',
            'forceTranslation' => true
        ];
        $this->assertArrayHasKey('imperavi', Yii::$app->i18n->translations);
        $this->assertEquals($test, Yii::$app->i18n->translations['imperavi']);
    }
}
