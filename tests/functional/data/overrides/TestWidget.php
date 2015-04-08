<?php

namespace tests\data\overrides;

use vova07\imperavi\Widget;
use vova07\imperavi\Asset;
use Yii;

/**
 * Class TestWidget
 * @package tests\data\overrides
 */
class TestWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        Yii::$container->set(Asset::className(), [
            'class' => TestAsset::className()
        ]);

        parent::init();
    }
}
