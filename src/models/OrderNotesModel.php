<?php
/**
 * Order Notes plugin for Craft CMS 3.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\models;

use superbig\ordernotes\OrderNotes;

use Craft;
use craft\base\Model;
use superbig\ordernotes\records\OrderNotesRecord;

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
class OrderNotesModel extends Model
{
    private $_user;

    public $id;
    public $userId;
    public $orderId;
    public $siteId;
    public $message = '';
    public $dateCreated;
    public $dateUpdated;
    public $notify  = false;

    public static function create(OrderNotesRecord $record)
    {
        $model              = new self();
        $model->id          = $record->id;
        $model->siteId      = $record->siteId;
        $model->orderId     = $record->orderId;
        $model->userId      = $record->userId;
        $model->message     = $record->message;
        $model->dateCreated = $record->dateCreated;
        $model->dateUpdated = $record->dateUpdated;
        $model->notify      = $record->notify;

        return $model;
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = Craft::$app->getUsers()->getUserById($this->userId);
        }

        return $this->_user;
    }

    public function getUsername()
    {
        return $this->getUser()->username;
    }

    public function rules()
    {
        return [
            ['notify', 'boolean'],
            ['notify', 'default', 'value' => false],
        ];
    }
}
