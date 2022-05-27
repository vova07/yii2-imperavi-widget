# Imperavi Redactor Widget для Yii 2

[![Latest Version](https://img.shields.io/github/release/vova07/yii2-imperavi-widget.svg?style=flat-square)](https://github.com/vova07/yii2-imperavi-widget/releases)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/vova07/yii2-imperavi-widget/master.svg?style=flat-square)](https://travis-ci.org/vova07/yii2-imperavi-widget)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/vova07/yii2-imperavi-widget.svg?style=flat-square)](https://scrutinizer-ci.com/g/vova07/yii2-imperavi-widget/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/vova07/yii2-imperavi-widget.svg?style=flat-square)](https://scrutinizer-ci.com/g/vova07/yii2-imperavi-widget)
[![Total Downloads](https://img.shields.io/packagist/dt/vova07/yii2-imperavi-widget.svg?style=flat-square)](https://packagist.org/packages/vova07/yii2-imperavi-widget)

`Imperavi Redactor Widget` — обёртка для [Imperavi Redactor 10.2.5](https://imperavi.com/assets/pdf/redactor-documentation-10.pdf),
довольно неплохого WYSIWYG редактора.

**Обратите внимание, что сам Imperavi Redactor — коммерческий продукт и не является
OpenSource, но так как сообщество Yii купило OEM-лицензию, то вы можете бесплатно
пользоваться им в проектах на Yii.**

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

### Как простой виджет

```php
echo \vova07\imperavi\Widget::widget([
    'name' => 'redactor',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen',
        ],
        'clips' => [
            ['Lorem ipsum...', 'Lorem...'],
            ['red', '<span class="label-red">red</span>'],
            ['green', '<span class="label-green">green</span>'],
            ['blue', '<span class="label-blue">blue</span>'],
        ],
    ],
]);
```

### Как виджет ActiveForm

```php
use vova07\imperavi\Widget;

echo $form->field($model, 'content')->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen',
        ],
        'clips' => [
            ['Lorem ipsum...', 'Lorem...'],
            ['red', '<span class="label-red">red</span>'],
            ['green', '<span class="label-green">green</span>'],
            ['blue', '<span class="label-blue">blue</span>'],
        ],
    ],
]);
```

### Как виджет для уже существующего textarea

```php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen',
        ],
        'clips' => [
            ['Lorem ipsum...', 'Lorem...'],
            ['red', '<span class="label-red">red</span>'],
            ['green', '<span class="label-green">green</span>'],
            ['blue', '<span class="label-blue">blue</span>'],
        ],
    ],
]);
```

### Добавляем возможность выбирать уже загружённые изображения

```php
// DefaultController.php
public function actions()
{
    return [
        'images-get' => [
            'class' => 'vova07\imperavi\actions\GetImagesAction',
            'url' => 'http://my-site.com/images/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
            'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.ico']], // These options are by default.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'imageUpload' => Url::to(['default/image-upload']),
        'imageManagerJson' => Url::to(['/default/images-get']),
        'plugins' => [
            'imagemanager',
        ],
    ],
]);
```

### Добавляем возможность выбирать уже загружённые файлы

```php
// DefaultController.php
public function actions()
{
    return [
        'files-get' => [
            'class' => 'vova07\imperavi\actions\GetFilesAction',
            'url' => 'http://my-site.com/files/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
            'options' => ['only' => ['*.txt', '*.md']], // These options are by default.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'fileUpload' => Url::to(['default/file-upload']),
        'fileManagerJson' => Url::to(['/default/files-get']),
        'plugins' => [
            'filemanager',
        ],
    ],
]);
```

### Загрузка изображения

```php
// DefaultController.php
public function actions()
{
    return [
        'image-upload' => [
            'class' => 'vova07\imperavi\actions\UploadFileAction',
            'url' => 'http://my-site.com/images/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'imageUpload' => Url::to(['/default/image-upload']),
        'plugins' => [
            'imagemanager',
        ],
    ],
]);
```

### Загрузка файла

```php
// DefaultController.php
public function actions()
{
    return [
        'file-upload' => [
            'class' => 'vova07\imperavi\actions\UploadFileAction',
            'url' => 'http://my-site.com/files/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
            'uploadOnlyImage' => false, // For any kind of files uploading.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'fileUpload' => Url::to(['/default/file-upload']),
        'plugins' => [
            'filemanager',
        ],
    ],
]);
```

### Загрузка и замена файла с одинаковым названием 

```php
// DefaultController.php
public function actions()
{
    return [
        'file-upload' => [
            'class' => 'vova07\imperavi\actions\UploadFileAction',
            'url' => 'http://my-site.com/files/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
            'uploadOnlyImage' => false, // For any kind of files uploading.
            'unique' => false,
            'replace' => true, // By default it throw an excepiton instead.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'fileUpload' => Url::to(['/default/file-upload']),
        'plugins' => [
            'filemanager',
        ],
    ],
]);
```

### Загрузка и *translit* файла

```php
// DefaultController.php
public function actions()
{
    return [
        'file-upload' => [
            'class' => 'vova07\imperavi\actions\UploadFileAction',
            'url' => 'http://my-site.com/files/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
            'uploadOnlyImage' => false, // For any kind of files uploading.
            'unique' => false,
            'translit' => true,
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'fileUpload' => Url::to(['/default/file-upload']),
        'plugins' => [
            'filemanager',
        ],
    ]
]);
```

### Регистрация своих плагинов

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
        'my-custom-plugin' => 'app\assets\MyPluginBundle',
    ],
]);
```

### Включаем менеджер изображений с функционалом удаления изоражения

```php
// DefaultController.php
public function actions()
{
    return [
        'images-get' => [
            'class' => 'vova07\imperavi\actions\GetImagesAction',
            'url' => 'http://my-site.com/images/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
        ],
        'image-upload' => [
            'class' => 'vova07\imperavi\actions\UploadFileAction',
            'url' => 'http://my-site.com/images/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
        ],
        'file-delete' => [
            'class' => 'vova07\imperavi\actions\DeleteFileAction',
            'url' => 'http://my-site.com/statics/', // Directory URL address, where files are stored.
            'path' => '/var/www/my-site.com/web/statics', // Or absolute path to directory where files are stored.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'imageUpload' => Url::to(['/default/image-upload']),
        'imageDelete' => Url::to(['/default/file-delete']),
        'imageManagerJson' => Url::to(['/default/images-get']),
    ],
    'plugins' => [
        'imagemanager' => 'vova07\imperavi\bundles\ImageManagerAsset',              
    ],
]);
```

### Включаем менеджер файлов с функционалом удаления файла

```php
// DefaultController.php
public function actions()
{
    return [
        'files-get' => [
            'class' => 'vova07\imperavi\actions\GetFilesAction',
            'url' => 'http://my-site.com/images/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
        ],
        'file-upload' => [
            'class' => 'vova07\imperavi\actions\UploadFileAction',
            'url' => 'http://my-site.com/files/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
            'uploadOnlyImage' => false, // For any kind of files uploading.
        ],
        'file-delete' => [
            'class' => 'vova07\imperavi\actions\DeleteFileAction',
            'url' => 'http://my-site.com/statics/', // Directory URL address, where files are stored.
            'path' => '/var/www/my-site.com/web/statics', // Or absolute path to directory where files are stored.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'fileUpload' => Url::to(['/default/file-upload']),
        'fileDelete' => Url::to(['/default/file-delete']),
        'fileManagerJson' => Url::to(['/default/files-get']),
    ],
    'plugins' => [
        'filemanager' => 'vova07\imperavi\bundles\FileManagerAsset',              
    ],
]);
```

## Тестирование

``` bash
$ phpunit
```

## Дополнительная информация

Пожалуйста проверьте [Imperavi Redactor v10](https://imperavi.com/assets/pdf/redactor-documentation-10.pdf) документацию для более подробной информации касательно его настроек.

## Хотите помочь?

Пожалуйста проверьте [CONTRIBUTING файл](CONTRIBUTING.md) для подробной информации.

## Авторство

- [Vasile Crudu](https://github.com/vova07)
- [Все участники](../../contributors)

## Лицензия

BSD Лицензия (BSD). Пожалуйста проверьте [License файл](LICENSE.md) для подробной информации.

## Руководство по обновлению

Пожалуйста ознакомтесь с [РУКОВОДСТВОМ](UPGRADE.md) для подробной информации.
