<?php
/**
 * Order Notes plugin for Craft CMS 5.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\records;

use craft\db\ActiveRecord;
use yii\db\ActiveQueryInterface;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 *
 * @property string    $message
 * @property int       $id
 * @property int       $userId
 * @property int       $siteId
 * @property int       $orderId
 * @property bool      $notify
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 */
class OrderNotesRecord extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%ordernotes}}';
    }

    public function user(): ActiveQueryInterface
    {
        return $this->hasOne(\craft\records\User::class, ['id' => 'userId']);
    }

    public function order(): ActiveQueryInterface
    {
        return $this->hasOne(\craft\commerce\records\Order::class, ['id' => 'orderId']);
    }
}
