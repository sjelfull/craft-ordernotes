<?php
/**
 * Order Notes plugin for Craft CMS 5.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\controllers;

use Craft;
use craft\commerce\Plugin as Commerce;
use craft\web\Controller;
use superbig\ordernotes\models\OrderNotesModel;
use superbig\ordernotes\OrderNotes;
use yii\web\Response;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class DefaultController extends Controller
{
    public function actionGetOrderNotes(): Response
    {
        $id = Craft::$app->getRequest()->getRequiredParam('orderId');
        $order = Commerce::getInstance()->getOrders()->getOrderById($id);

        if (!$order) {
            throw new \Exception(Craft::t('order-notes', 'No order with id {id} found', ['id' => $id]));
        }

        return $this->asJson(OrderNotes::getInstance()->orderNotes->getNotesByOrderId($id));
    }

    public function actionAddNote(): Response
    {
        $request = Craft::$app->getRequest();
        $orderId = $request->getRequiredParam('orderId');
        $message = $request->getRequiredParam('message');
        $notify = filter_var($request->getRequiredParam('notify'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $order = Commerce::getInstance()->getOrders()->getOrderById($orderId);

        if (!$order) {
            throw new \Exception(Craft::t('order-notes', 'No order with id {id} found', ['id' => $orderId]));
        }

        $note = new OrderNotesModel();
        $note->message = $message;
        $note->notify = $notify;
        $note->orderId = $orderId;
        $note->siteId = Craft::$app->getSites()->currentSite->id;
        $note->userId = Craft::$app->getUser()->getIdentity()->id;

        if (!OrderNotes::getInstance()->orderNotes->saveNote($note)) {
            return $this->asJson([
                'success' => false,
                'error' => Craft::t('order-notes', 'Problem saving note'),
            ]);
        }

        if ($note->notify) {
            OrderNotes::getInstance()->orderNotes->notifyCustomer($note, $order);
        }

        return $this->asJson([
            'success' => true,
            'note' => [
                'message' => $message,
                'username' => $note->getUsername(),
                'dateCreated' => Craft::$app->getFormatter()->asDatetime($note->dateCreated, 'short'),
            ],
        ]);
    }
}
