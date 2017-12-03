<?php
/**
 * This file is part of yii2-imperavi-widget.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/vova07/yii2-imperavi-widget
 */

namespace vova07\imperavi\tests\functional;

use org\bovigo\vfs\vfsStream;
use PHPUnit_Framework_TestCase;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\AssetManager;
use yii\web\View;

/**
 * This is the base class for all yii framework functional tests.
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Root directory name.
     */
    const ROOT_DIRECTORY = 'root';
    /**
     * Root directory name.
     */
    const STATICS_DIRECTORY = 'statics';
    /**
     * Root directory name.
     */
    const UPLOAD_DIRECTORY = 'upload';

    /**
     * Asserting two strings equality ignoring line endings.
     *
     * @param string $expected
     * @param string $actual
     */
    public function assertEqualsWithoutLE($expected, $actual)
    {
        $expected = str_replace("\r\n", "\n", $expected);
        $actual = str_replace("\r\n", "\n", $actual);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Mock application prior running tests.
     */
    protected function setUp()
    {
        vfsStream::setup(self::ROOT_DIRECTORY, null, [
            self::STATICS_DIRECTORY => [
                '1.php' => 'PHP file test content.',
                '2.html' => 'HTML file test content.',
                'folder' => [],
            ],
            self::UPLOAD_DIRECTORY => [],
        ]);

        $this->createVirtualJpegImage(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::STATICS_DIRECTORY . '/3.jpeg'));
        $this->createVirtualJpegImage(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::STATICS_DIRECTORY . '/folder/4.jpeg'));
        $this->createVirtualJpegImage(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::UPLOAD_DIRECTORY . '/1.jpeg'));
        $this->createVirtualJpegImage(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::UPLOAD_DIRECTORY . '/2.jpeg'));

        $this->mockWebApplication(
            [
                'components' => [
                    'request' => [
                        'class' => 'yii\web\Request',
                        'url' => '/test',
                        'enableCsrfValidation' => false,
                    ],
                    'response' => [
                        'class' => 'yii\web\Response',
                    ],
                ],
            ]
        );
    }

    /**
     * Clean up after test.
     * By default the application created with `mockApplication` will be destroyed.
     *
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->destroyApplication();
    }

    /**
     * @param array $config
     * @param string $appClass
     */
    protected function mockApplication($config = [], $appClass = '\yii\console\Application')
    {
        new $appClass(
            ArrayHelper::merge(
                [
                    'id' => 'test-app',
                    'basePath' => __DIR__,
                    'vendorPath' => $this->getVendorPath(),
                ],
                $config
            )
        );
    }

    /**
     * @param array $config
     * @param string $appClass
     */
    protected function mockWebApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
            'controllerNamespace' => 'vova07\imperavi\tests\functional\data\controllers',
            'components' => [
                'request' => [
                    'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
                    'scriptFile' => __DIR__ . '/index.php',
                    'scriptUrl' => '/index.php',
                ],
                'assetManager' => [
                    'basePath' => '@vova07/imperavi/tests/data/assets',
                    'baseUrl' => '/',
                ],
            ],
        ], $config));
    }

    /**
     * @return string Return vendor path.
     */
    protected function getVendorPath()
    {
        return dirname(dirname(__DIR__)) . '/vendor';
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
    }

    /**
     * Creates a view for testing purposes.
     *
     * @return View
     */
    protected function getView()
    {
        $view = new View();
        $view->setAssetManager(new AssetManager([
            'basePath' => '@vova07/imperavi/tests/data/assets',
            'baseUrl' => '/',
        ]));

        return $view;
    }

    /**
     * Create a virtual JPEG image.
     *
     * @param string $path vfsStream path
     */
    protected function createVirtualJpegImage($path)
    {
        ob_start();
        $image = imagecreate(100, 100);
        $color = imagecolorallocate($image, 0, 0, 255);
        imagecolorallocate($image, 255, 255, 255);
        imagestring($image, 1, 5, 5, 'Test image', $color);
        imagejpeg($image);
        $imageRawData = ob_get_contents();
        ob_end_clean();
        file_put_contents($path, $imageRawData);
    }

    /**
     * Get virtual file MIME type.
     *
     * @param string $path vfsStream path
     *
     * @return string File MIME type
     */
    protected function getVirtualFileMimeType($path)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $path);
        finfo_close($finfo);

        return $mimeType;
    }
}
