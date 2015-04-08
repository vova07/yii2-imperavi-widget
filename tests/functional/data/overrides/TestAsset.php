<?php

namespace tests\data\overrides;

use vova07\imperavi\Asset;

/**
 * Class TestAsset
 * @package tests\data\overrides
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class TestAsset extends Asset
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@tests/../../src/assets';
}
