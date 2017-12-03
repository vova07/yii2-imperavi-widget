<?php
/**
 * This file is part of yii2-imperavi-widget.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/vova07/yii2-imperavi-widget
 */

namespace vova07\imperavi\tests\functional\data\models;

use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
final class Model extends ActiveRecord
{
    /**
     * @var string|null Message.
     */
    public $message;

    /**
     * @var string|null Intro.
     */
    public $intro;

    /**
     * @var Connection|null Database instance.
     */
    public static $db;

    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        return self::$db;
    }
}
