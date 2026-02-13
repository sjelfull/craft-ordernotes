<?php
/**
 * Order Notes plugin for Craft CMS 5.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\services;

use Craft;
use craft\base\Component;
use craft\commerce\elements\Order;
use craft\helpers\App;
use craft\mail\Message;
use superbig\ordernotes\assetbundles\ordernotes\OrderNotesAsset;
use superbig\ordernotes\models\OrderNotesModel;
use superbig\ordernotes\OrderNotes;
use superbig\ordernotes\records\OrderNotesRecord;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class OrderNotesService extends Component
{
    public function getNoteById(?int $id = null): ?OrderNotesModel
    {
        $record = OrderNotesRecord::findOne($id);

        if (!$record) {
            return null;
        }

        return OrderNotesModel::create($record);
    }

    /**
     * @return OrderNotesModel[]|null
     */
    public function getNotesByOrderId(?int $orderId = null): ?array
    {
        $records = OrderNotesRecord::findAll([
            'orderId' => $orderId,
        ]);

        if (!$records) {
            return null;
        }

        return array_map(function($record) {
            return OrderNotesModel::create($record);
        }, $records);
    }

    public function getCode(Order $order): void
    {
        Craft::$app->getView()->registerAssetBundle(OrderNotesAsset::class);

        $notes = $this->getNotesByOrderId($order->id);

        Craft::$app->getView()->registerJs('new OrderNotes(' . $order->id . ', ' . json_encode($this->formatOrderNotes($notes)) . ');');
    }

    public function formatOrderNotes(?array $notes = []): ?array
    {
        if (empty($notes)) {
            return null;
        }

        return array_reverse(array_map(function(OrderNotesModel $note) {
            return [
                'date' => Craft::$app->getFormatter()->asDatetime($note->dateCreated, 'short'),
                'message' => nl2br($note->message),
                'notify' => $note->notify,
                'username' => $note->getUsername(),
            ];
        }, $notes));
    }

    public function saveNote(OrderNotesModel &$note): bool
    {
        try {
            if ($note->id) {
                $record = OrderNotesRecord::findOne($note->id);

                if (!$record) {
                    throw new \Exception(Craft::t('order-notes', 'No note with id {id} was found!', ['id' => $note->id]));
                }
            } else {
                $record = new OrderNotesRecord();
            }

            $record->message = $note->message;
            $record->userId = $note->userId;
            $record->orderId = $note->orderId;
            $record->siteId = $note->siteId;
            $record->notify = $note->notify;

            if (!$record->save()) {
                Craft::error(
                    Craft::t('order-notes', 'An error occured when saving note record: {error}', [
                        'error' => print_r($record->getErrors(), true),
                    ]),
                    __METHOD__
                );

                return false;
            }

            $note->id = $record->id;
            $note->dateCreated = $record->dateCreated;
            $note->dateUpdated = $record->dateUpdated;

            return true;
        } catch (\Exception $e) {
            Craft::error(
                Craft::t('order-notes', 'An error occured when saving note record: {error}', [
                    'error' => $e->getMessage(),
                ]),
                __METHOD__
            );

            return false;
        }
    }

    public function notifyCustomer(OrderNotesModel $note, Order $order): bool
    {
        $templates = Craft::$app->getView();
        $mailer = Craft::$app->getMailer();
        $mailSettings = App::mailSettings();
        $settings = OrderNotes::getInstance()->getSettings();
        $htmlTemplate = $settings->notifyEmailTemplate;
        $textTemplate = $settings->notifyEmailTemplateText;
        $fromEmail = !empty($settings->notifyEmailFrom) ? $settings->notifyEmailFrom : App::parseEnv($mailSettings->fromEmail);
        $fromName = !empty($settings->notifyEmailFromName) ? $settings->notifyEmailFromName : App::parseEnv($mailSettings->fromName);
        $variables = ['order' => $order, 'note' => $note];

        if (empty($htmlTemplate)) {
            Craft::error(
                Craft::t('order-notes', 'No email template set. Customer could not be notified'),
                __METHOD__
            );

            return false;
        }

        try {
            $address = $order->getBillingAddress();
            $fullName = $address?->fullName ?? '';
            $subject = $templates->renderString($settings->notifyEmailSubject, $variables);
            $message = (new Message())
                ->setFrom([$fromEmail => $fromName])
                ->setReplyTo([$fromEmail => $fromName])
                ->setSubject($subject)
                ->setTo([$order->email => $fullName]);
        } catch (\Exception $e) {
            Craft::error(
                Craft::t('order-notes', 'Exception: {error}', ['error' => $e->getMessage()]),
                __METHOD__
            );

            return false;
        }

        $oldMode = $templates->getTemplateMode();
        $templates->setTemplateMode($templates::TEMPLATE_MODE_SITE);

        try {
            $html = $templates->renderTemplate($htmlTemplate, ['note' => $note, 'order' => $order]);
            $message->setHtmlBody($html);

            if (!empty($textTemplate)) {
                $text = $templates->renderTemplate($textTemplate, ['note' => $note, 'order' => $order]);
                $message->setTextBody($text);
            }
        } catch (\Exception $e) {
            Craft::error(
                Craft::t('order-notes', 'Error rendering email template {template}: {error}', [
                    'template' => $htmlTemplate,
                    'error' => $e->getMessage(),
                ]),
                __METHOD__
            );
        }

        $templates->setTemplateMode($oldMode);

        if (!$mailer->send($message)) {
            Craft::error(
                Craft::t('order-notes', 'Was not able to send notification email to customer'),
                __METHOD__
            );

            return false;
        }

        return true;
    }
}
