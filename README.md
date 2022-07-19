# Imperavi Redactor Widget for Yii 2 - Mod by NickGoodwind

`Imperavi Redactor Widget` is a wrapper for [Imperavi Redactor 10.2.5](https://imperavi.com/assets/pdf/redactor-documentation-10.pdf),
a high quality WYSIWYG editor.

**Note that Imperavi Redactor itself is a proprietary commercial copyrighted software
but since Yii community bought OEM license you can use it for free within Yii.**

## Install

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ php composer.phar require --prefer-dist vova07/yii2-imperavi-widget "*"
```

or add

```json
"vova07/yii2-imperavi-widget": "*"
```

to the `require` section of your `composer.json` file.


## Basic usage

For basic usage follow the intructions provided by [`vova07`](https://github.com/vova07/yii2-imperavi-widget)

## CodeMirror usage

Simply add the following lines to your Redactor configuration
```php
use vova07\imperavi\Widget;

echo $form->field($model, 'content')->widget(Widget::className(), [
    'settings' => [
        ...
        'codemirror' => true,
        'soure' => [
            'codemirror' => [
                'lineNubers' => true,
                'mode' => 'htmlmixed',
                'theme' => 'ayu-mirage',
                'indentWithTabs' => true,
                # Add all other codemirror options here.
            ]
        ],
        ...
    ],
]);
```
For all CodeMirror options review the [manual](https://codemirror.net/doc/manual.html)

## Custom modes, themes or assets

To use custom CodeMirror assets (modes, themes, plugins) other than the ones predefined here you need to add those assets to the codemirror assets folder `src/assets/codemirror` If you need to add a mode added to the `modes` folder; same with the themes. Just make sure to maintain the folder order. You can get all assets from [CodeMirror](https://codemirror.net/).


After including the assets you need to update the `CodemirrorAsset.php` file:
```php
class CodemirrorAsset extends AssetBundle
{
    public $sourcePath = '@vova07/imperavi/assets';
    
    public $js = [
        'codemirror/codemirror.js',
        # Add your modes and plugins here
    ];

    public $css = [
        'codemirror/codemirror.css',
        # Add your themes and styles here
    ];

    public $depends = [
        'app\modules\admin\assets\AdminAppAsset'
    ];
}
```

## Further Information

Please, check the [Imperavi Redactor v10](https://imperavi.com/assets/pdf/redactor-documentation-10.pdf) documentation for further information about its configuration options.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for `vova07` previous contributtor details. All changes staged in this fork have been done by `NickgGoodwind`

## Credits

- [Vasile Crudu](https://github.com/vova07)
- [NickGoodwind](https://github.com/nickgoodwind)

## License

The BSD License (BSD). Please see [License File](LICENSE.md) for more information.

## Upgrade guide

Please check the [UPGRADE GUIDE](UPGRADE.md) for details. 
