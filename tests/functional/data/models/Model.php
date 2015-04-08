<?php

namespace tests\data\models;

use yii\db\ActiveRecord;

/**
 * Class Model
 * @package tests\data\models
 */
class Model extends ActiveRecord
{
    /** @var string|null Message */
    public $message;

    /** @var string|null Intro */
    public $intro;

    /** @var \yii\db\Connection|null Database instance */
    public static $db;

    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        return self::$db;
    }
}
