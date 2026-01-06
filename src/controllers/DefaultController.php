<?php
/**
 * Order Notes plugin for Craft CMS 3.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\controllers;

use craft\commerce\Plugin;
use craft\commerce\services\Orders;
use superbig\ordernotes\models\OrderNotesModel;
use superbig\ordernotes\OrderNotes;

use Craft;
use craft\web\Controller;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class DefaultController extends Controller
{

    // Public Methods
    // =========================================================================

    public function actionGetOrderNotes()
    {
        $id    = Craft::$app->getRequest()->getRequiredParam('orderId');
        $order = Plugin::getInstance()->getOrders()->getOrderById($id);

        if (!$order) {
            throw new Exception(Craft::t('No order with id {id} found', ['id' => $id]), LogLevel::Error);
        }

        $this->returnJson(OrderNotes::$plugin->orderNotes->getNotesByOrderId($id));
    }

    public function actionAddNote()
    {
        $request = Craft::$app->getRequest();
        $orderId = $request->getRequiredParam('orderId');
        $message = $request->getRequiredParam('message');
        $notify  = filter_var($request->getRequiredParam('notify'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $order   = Plugin::getInstance()->getOrders()->getOrderById($orderId);

        if (!$order) {
            throw new \Exception(Craft::t('No order with id {id} found', ['id' => $orderId]), LogLevel::Error);
        }

        $note          = new OrderNotesModel();
        $note->message = $message;
        $note->notify  = $notify;
        $note->orderId = $orderId;
        $note->siteId  = Craft::$app->getSites()->currentSite->id;
        $note->userId  = Craft::$app->getUser()->getIdentity()->id;

        if (!OrderNotes::$plugin->orderNotes->saveNote($note)) {
            return $this->asJson([
                'success' => false,
                'error'   => Craft::t('Problem saving note: {error}', [
                    'error' => 'Error',
                    // $note->getErrors(),
                ]),
            ]);
        }

        if ($note->notify) {
            OrderNotes::$plugin->orderNotes->notifyCustomer($note, $order);
        }

        return $this->asJson([
            'success' => true,
            'note'    => [
                'message'     => $message,
                'username'    => $note->getUsername(),
                'dateCreated' => Craft::$app->getFormatter()->asDatetime($note->dateCreated, 'short'),
            ],
        ]);
    }
}
