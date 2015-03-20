<?php

namespace tests;

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
        $list = FileHelper::findFiles(Yii::getAlias('@tests/data/statics'), ['url' => '/statics/'], GetAction::TYPE_FILES);
        $this->assertEquals(3, count($list));
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
        $list = FileHelper::findFiles(Yii::getAlias('@tests/data/statics'), ['url' => '/statics/']);
        $this->assertEquals(3, count($list));
        $this->assertArrayHasKey('title', $list[0]);
        $this->assertArrayHasKey('thumb', $list[0]);
        $this->assertArrayHasKey('image', $list[0]);
    }

    /**
     * Test find files method with except option.
     */
    public function testFindFilesMethodExceptPng()
    {
        $list = FileHelper::findFiles(Yii::getAlias('@tests/data/statics'), ['except' => ['*.png']]);
        $this->assertEquals(2, count($list));
    }

    /**
     * Test find files method with only option.
     */
    public function testFindFilesMethodOnlyPng()
    {
        $list = FileHelper::findFiles(Yii::getAlias('@tests/data/statics'), ['only' => ['*.png']]);
        $this->assertEquals(1, count($list));
    }
}
