<?php
/**
 * Order Notes plugin for Craft CMS 5.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\models;

use Craft;
use craft\base\Model;
use craft\elements\User;
use superbig\ordernotes\records\OrderNotesRecord;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class OrderNotesModel extends Model
{
    public ?int $id = null;
    public ?int $userId = null;
    public ?int $orderId = null;
    public ?int $siteId = null;
    public string $message = '';
    public mixed $dateCreated = null;
    public mixed $dateUpdated = null;
    public bool $notify = false;

    private ?User $_user = null;

    public static function create(OrderNotesRecord $record): self
    {
        $model = new self();
        $model->id = $record->id;
        $model->siteId = $record->siteId;
        $model->orderId = $record->orderId;
        $model->userId = $record->userId;
        $model->message = $record->message;
        $model->dateCreated = $record->dateCreated;
        $model->dateUpdated = $record->dateUpdated;
        $model->notify = (bool)$record->notify;

        return $model;
    }

    public function getUser(): ?User
    {
        if (!$this->_user && $this->userId) {
            $this->_user = Craft::$app->getUsers()->getUserById($this->userId);
        }

        return $this->_user;
    }

    public function getUsername(): string
    {
        $user = $this->getUser();

        return $user?->username ?? '';
    }

    public function rules(): array
    {
        return [
            ['notify', 'boolean'],
            ['notify', 'default', 'value' => false],
        ];
    }
}
