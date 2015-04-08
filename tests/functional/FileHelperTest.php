<?php

namespace tests;

use org\bovigo\vfs\vfsStream;
use ReflectionClass;
use vova07\imperavi\actions\GetAction;
use vova07\imperavi\helpers\FileHelper;
use Yii;

/**
 * Class FileHelperTest
 * @package tests
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class FileHelperTest extends TestCase
{
    /**
     * Test find files method with file type.
     */
    public function testFindFilesMethodWithFileType()
    {
        $list = FileHelper::findFiles(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::STATICS_DIRECTORY), ['url' => '/statics/'], GetAction::TYPE_FILES);
        $this->assertCount(4, $list);
        $this->assertArrayHasKey('title', $list[0]);
        $this->assertArrayHasKey('name', $list[0]);
        $this->assertArrayHasKey('link', $list[0]);
        $this->assertArrayHasKey('size', $list[0]);
    }

    /**
     * Test find files method with image type.
     */
    public function testFindFilesMethodWithImageType()
    {
        $list = FileHelper::findFiles(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::STATICS_DIRECTORY), ['url' => '/statics/']);
        $this->assertCount(4, $list);
        $this->assertArrayHasKey('title', $list[0]);
        $this->assertArrayHasKey('thumb', $list[0]);
        $this->assertArrayHasKey('image', $list[0]);
    }

    /**
     * Test find files method with except option.
     */
    public function testFindFilesMethodExceptPng()
    {
        $list = FileHelper::findFiles(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::STATICS_DIRECTORY), ['except' => ['*.jpeg']]);
        $this->assertCount(2, $list);
    }

    /**
     * Test find files method with only option.
     */
    public function testFindFilesMethodOnlyPng()
    {
        $list = FileHelper::findFiles(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::STATICS_DIRECTORY), ['only' => ['*.jpeg']]);
        $this->assertCount(2, $list);
    }

    /**
     * Test find files method with invalid type.
     */
    public function testFindFilesMethodWithInvalidType()
    {
        $list = FileHelper::findFiles(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::STATICS_DIRECTORY), ['url' => '/statics/'], 'invalidType');
        $this->assertCount(4, $list);
        $this->assertTrue(is_string($list[0]));
    }

    /**
     * Test find files method with invalid directory.
     */
    public function testFindFilesMethodWithInvalidDirectory()
    {
        $this->setExpectedException('yii\base\InvalidParamException', 'The dir argument must be a directory.');
        FileHelper::findFiles(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::STATICS_DIRECTORY . '/3.jpeg'));
    }

    /**
     * Test get file size method.
     */
    public function testGetFileSize()
    {
        $class = new ReflectionClass('vova07\imperavi\helpers\FileHelper');
        $method = $class->getMethod('getFileSize');
        $method->setAccessible(true);
        $output = $method->invokeArgs(new FileHelper(), [vfsStream::url(self::ROOT_DIRECTORY . '/' . self::STATICS_DIRECTORY . '/3.jpeg')]);
        $size = filesize(vfsStream::url(self::ROOT_DIRECTORY . '/' . self::STATICS_DIRECTORY . '/3.jpeg'));
        $this->assertEquals($size . '.0 B', $output);
    }
}
