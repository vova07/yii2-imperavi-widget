# Imperavi Redactor Widget для Yii 2

[![Latest Version](https://img.shields.io/github/release/vova07/yii2-imperavi-widget.svg?style=flat-square)](https://github.com/vova07/yii2-imperavi-widget/releases)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/vova07/yii2-imperavi-widget/master.svg?style=flat-square)](https://travis-ci.org/vova07/yii2-imperavi-widget)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/vova07/yii2-imperavi-widget.svg?style=flat-square)](https://scrutinizer-ci.com/g/vova07/yii2-imperavi-widget/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/vova07/yii2-imperavi-widget.svg?style=flat-square)](https://scrutinizer-ci.com/g/vova07/yii2-imperavi-widget)
[![Total Downloads](https://img.shields.io/packagist/dt/vova07/yii2-imperavi-widget.svg?style=flat-square)](https://packagist.org/packages/vova07/yii2-imperavi-widget)

`Imperavi Redactor Widget` — обёртка для [Imperavi Redactor](http://imperavi.com/redactor/),
довольно неплохого WYSIWYG редактора.

Обратите внимание, что сам Imperavi Redactor — коммерческий продукт и не является
OpenSource, но так как сообщество Yii купило OEM-лицензию, то вы можете бесплатно
пользоваться им в проектах на Yii.

## Установка

Желательно устанавливать расширение через [composer](http://getcomposer.org/download/).

Просто запустите в консоли команду:

```bash
$ php composer.phar require --prefer-dist vova07/yii2-imperavi-widget "*"
```

или добавьте

```json
"vova07/yii2-imperavi-widget": "*"
```

в `require` секцию вашего `composer.json` файла.


## Использование

Как только вы установили расширение, вы можете её использовать в своём коде:

### Как простой виджет ###

```php
echo \vova07\imperavi\Widget::widget([
    'name' => 'redactor',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen'
        ]
    ]
]);
```

### Как виджет ActiveForm ###

```php
use vova07\imperavi\Widget;

echo $form->field($model, 'content')->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen'
        ]
    ]
]);
```

### Как виджет для уже существующего textarea ###

```php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen'
        ]
    ]
]);
```

### Добавляем возможность выбирать уже загружённые изображения ###

```php
// DefaultController.php
public function actions()
{
    return [
        'images-get' => [
            'class' => 'vova07\imperavi\actions\GetAction',
            'url' => 'http://my-site.com/images/', // URL адрес папки где хранятся изображения.
            'path' => '@alias/to/my/path', // Или абсолютный путь к папке с изображениями.
            'type' => GetAction::TYPE_IMAGES,
        ]
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'imageManagerJson' => Url::to(['/default/images-get']),
        'plugins' => [
            'imagemanager'
        ]
    ]
]);
```

### Добавляем возможность выбирать уже загружённые файлы ###

```php
// DefaultController.php
public function actions()
{
    return [
        'files-get' => [
            'class' => 'vova07\imperavi\actions\GetAction',
            'url' => 'http://my-site.com/files/', // URL адрес папки где хранятся файлы.
            'path' => '@alias/to/my/path', // Или абсолютный путь к папке с файлами.
            'type' => GetAction::TYPE_FILES,
        ]
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'fileManagerJson' => Url::to(['/default/files-get']),
        'plugins' => [
            'filemanager'
        ]
    ]
]);
```

### Загрузка изображения ###

```php
// DefaultController.php
public function actions()
{
    return [
        'image-upload' => [
            'class' => 'vova07\imperavi\actions\UploadAction',
            'url' => 'http://my-site.com/images/', // URL адрес папки куда будут загружатся изображения.
            'path' => '@alias/to/my/path' // Или абсолютный путь к папке куда будут загружатся изображения.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'imageUpload' => Url::to(['/default/image-upload'])
    ]
]);
```

### Загрузка файла ###

```php
// DefaultController.php
public function actions()
{
    return [
        'file-upload' => [
            'class' => 'vova07\imperavi\actions\UploadAction',
            'url' => 'http://my-site.com/files/', // URL адрес папки куда будут загружатся файлы.
            'path' => '@alias/to/my/path' // Или абсолютный путь к папке куда будут загружатся изображения.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'fileUpload' => Url::to(['/default/file-upload'])
    ]
]);
```

### Регистрация своих плагинов ###

### Add custom plugins ###

```php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen'
        ]
    ],
    'plugins' => [
        'my-custom-plugin' => 'app\assets\MyPluginBundle'
    ]
]);
```

## Тестирование

``` bash
$ phpunit
```

## Дополнительная информация

Пожалуйста проверьте [Imperavi Redactor](http://imperavi.com/redactor/) документацию для более подробной информации касательно его настроек.

## Хотите помочь?

Пожалуйста проверьте [CONTRIBUTING файл](CONTRIBUTING.md) для подробной информации.

## Авторство

- [Vasile Crudu](https://github.com/vova07)
- [Все участники](../../contributors)

## Лицензия

BSD Лицензия (BSD). Пожалуйста проверьте [License файл](LICENSE.md) для подробной информации.

> <a href="http://yiiwheels.com"><img src="http://yiiwheels.com/img/logo-big.png" alt="YiiWheels" width="150" height="100" /></a>  
[Доступно также на YiiWheels](http://yiiwheels.com)