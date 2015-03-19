<?php

namespace tests\data\overrides;

use vova07\imperavi\Asset;

/**
 * Class TestAsset
 * @package tests\data\overrides
 */
class TestAsset extends Asset
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@tests/../../src/assets';
}
