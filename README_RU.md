Imperavi Redactor Widget для Yii 2
==================================

`Imperavi Redactor Widget` — обёртка для [Imperavi Redactor](http://imperavi.com/redactor/),
довольно неплохого WYSIWYG редактора.

Обратите внимание, что сам Imperavi Redactor — коммерческий продукт и не является
OpenSource, но так как сообщество Yii купило OEM-лицензию, то вы можете бесплатно
пользоваться им в проектах на Yii.

Установка
---------

Желательно устанавливать расширение через [composer](http://getcomposer.org/download/).

Просто запустите в консоли команду:

```
php composer.phar require --prefer-dist vova07/yii2-imperavi-widget "*"
```

или добавьте

```
"vova07/yii2-imperavi-widget": "*"
```

в `require` секцию вашего `composer.json` файла.


Использование
-------------

Как только вы установили расширение, вы можете её использовать в своём коде:

### Как простой виджет ###

```php
echo \vova07\imperavi\Widget::widget([
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'pastePlainText' => true,
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
        'pastePlainText' => true,
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
        'pastePlainText' => true,
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
            'url' => 'http://my-site.com/images/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
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
        'pastePlainText' => true,
        'plugins' => [
            'clips',
            'fullscreen'
        ],
        'imageManagerJson' => Url::to(['/default/images-get'])
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
            'url' => 'http://my-site.com/files/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
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
        'pastePlainText' => true,
        'plugins' => [
            'clips',
            'fullscreen'
        ],
        'fileManagerJson' => Url::to(['/default/files-get'])
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
            'url' => 'http://my-site.com/images/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path' // Or absolute path to directory where files are stored.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'pastePlainText' => true,
        'plugins' => [
            'clips',
            'fullscreen'
        ],
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
            'url' => 'http://my-site.com/files/', // Directory URL address, where files are stored.
            'path' => '@alias/to/my/path' // Or absolute path to directory where files are stored.
        ],
    ];
}

// View.php
echo \vova07\imperavi\Widget::widget([
    'selector' => '#my-textarea-id',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'pastePlainText' => true,
        'plugins' => [
            'clips',
            'fullscreen'
        ],
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
        'pastePlainText' => true,
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