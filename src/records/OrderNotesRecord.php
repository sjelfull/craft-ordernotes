<?php
/**
 * Order Notes plugin for Craft CMS 3.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\records;

use craft\commerce\records\Order;
use craft\db\ActiveRecord;

use superbig\ordernotes\OrderNotes;

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
    public static function tableName()
    {
        return '{{%ordernotes}}';
    }

    public function user(): ActiveQueryInterface
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

    public function order(): ActiveQueryInterface
    {
        return $this->hasOne(Order::class, ['id' => 'orderId']);
    }
}
