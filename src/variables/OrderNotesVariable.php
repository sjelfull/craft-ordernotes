<?php
/**
 * Order Notes plugin for Craft CMS 3.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\variables;

use craft\commerce\elements\Order;
use superbig\ordernotes\OrderNotes;

use Craft;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class OrderNotesVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param Order|null $order
     *
     * @return array|null
     */
    public function getNotesForOrder(Order $order = null)
    {
        return OrderNotes::$plugin->orderNotes->getNotesByOrderId($order->id);
    }

    /**
     * @param null $orderId
     *
     * @return array|null
     */
    public function getNotesByOrderId($orderId = null)
    {
        return OrderNotes::$plugin->orderNotes->getNotesByOrderId($orderId);
    }
}
