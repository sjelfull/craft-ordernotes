<?php
/**
 * Order Notes plugin for Craft CMS 5.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\variables;

use craft\commerce\elements\Order;
use superbig\ordernotes\OrderNotes;
use superbig\ordernotes\services\OrderNotesService;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class OrderNotesVariable
{
    public function __construct(private ?OrderNotesService $service = null)
    {
    }

    /**
     * @return \superbig\ordernotes\models\OrderNotesModel[]|null
     */
    public function getNotesForOrder(Order $order): ?array
    {
        return $this->getService()->getNotesByOrderId($order->id);
    }

    /**
     * @return \superbig\ordernotes\models\OrderNotesModel[]|null
     */
    public function getNotesByOrderId(?int $orderId = null): ?array
    {
        return $this->getService()->getNotesByOrderId($orderId);
    }

    private function getService(): OrderNotesService
    {
        return $this->service ?? OrderNotes::getInstance()->orderNotes;
    }
}
