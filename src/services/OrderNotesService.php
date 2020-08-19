<?php
/**
 * Order Notes plugin for Craft CMS 3.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\services;

use craft\commerce\elements\Order;
use craft\mail\Message;
use superbig\ordernotes\assetbundles\ordernotes\OrderNotesAsset;
use superbig\ordernotes\models\OrderNotesModel;
use superbig\ordernotes\OrderNotes;

use Craft;
use craft\base\Component;
use superbig\ordernotes\records\OrderNotesRecord;
use yii\base\ErrorException;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class OrderNotesService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * @param null $id
     *
     * @return OrderNotesModel|null
     */
    public function getNoteById($id = null)
    {
        $package = OrderNotesRecord::findOne($id);

        if (!$package) {
            return null;
        }

        /** @var OrderNotesModel $note */
        $note = (new OrderNotesModel)->setAttributes($package);

        return $note;
    }

    /**
     * @param null $orderId
     *
     * @return array|null
     */
    public function getNotesByOrderId($orderId = null)
    {
        $notes = OrderNotesRecord::findAll([
            'orderId' => $orderId,
        ]);

        if (!$notes) {
            return null;
        }

        return array_map(function($record) {
            /** @var OrderNotesModel $note */
            $note = OrderNotesModel::create($record);

            return $note;
        }, $notes);
    }

    public function getCode(Order $order)
    {
        Craft::$app->getView()->registerAssetBundle(OrderNotesAsset::class);

        $notes = $this->getNotesByOrderId($order->id);

        Craft::$app->getView()->registerJs('new OrderNotes(' . $order->id . ', ' . json_encode($this->formatOrderNotes($notes)) . ');');
    }

    public function formatOrderNotes($notes = [])
    {
        if (empty($notes)) {
            return null;
        }

        $formattedNotes = [];

        return array_reverse(array_map(function(
            /** @var OrderNotesModel $note */
            $note) {
            // GET User
            return [
                'date'     => $note->dateCreated,
                'message'  => nl2br($note->message),
                'notify'   => $note->notify,
                'username' => $note->getUsername(),
            ];
        }, $notes));
    }

    public function saveNote(OrderNotesModel &$note)
    {
        try {
            if ($note->id) {
                $record = $this->getNoteById($model->id);

                if (!$record) {
                    throw new \Exception(Craft::t('site', 'No note with id {id} was found!', ['id' => $note->id]));
                }
            }
            else {
                $record = new OrderNotesRecord();
            }

            $record->message = $note->message;
            $record->userId  = $note->userId;
            $record->orderId = $note->orderId;
            $record->siteId  = $note->siteId;
            $record->notify  = $note->notify;

            if (!$record->save()) {
                $this->error('An error occured when saving note record: {error}',
                    [
                        'error' => print_r($record->getErrors(), true),
                    ]);
            }

            $note->id          = $record->id;
            $note->dateCreated = $record->dateCreated;
            $note->dateUpdated = $record->dateUpdated;

            return true;
        } catch (Exception $e) {
            $this->error('An error occured when saving note record: {error}',
                [
                    'error' => $e->getMessage(),
                ]);

            return false;
        }
    }

    public function notifyCustomer(OrderNotesModel $note, Order $order)
    {
        $templates      = Craft::$app->getView();
        $mailer         = Craft::$app->getMailer();
        $systemSettings = Craft::$app->systemSettings;
        $settings       = OrderNotes::$plugin->getSettings();
        $htmlTemplate   = $settings->notifyEmailTemplate;
        $textTemplate   = $settings->notifyEmailTemplateText;
        $fromEmail      = !empty($settings->notifyEmailFrom) ? $settings->notifyEmailFrom : $systemSettings->getSetting('email', 'fromEmail');
        $fromName       = !empty($settings->notifyEmailFromName) ? $settings->notifyEmailFromName : $systemSettings->getSetting('email', 'fromName');
        $variables      = ['order' => $order, 'note' => $note];

        if (empty($htmlTemplate)) {
            $this->error('No email template set. Customer could not be notified');

            return false;
        }

        try {
            $address = $order->getBillingAddress();
            if( method_exists($address,'getFullName') ){
                //craft\commerce\models\Address::getFullName() removed as of commerce 3.0.0
                $fullName = $address->getFullName();
            } else {
                $fullName = $address->fullName ? $address->fullName : $address->firstName.' '.$address->lastName;
            }
            $subject = $templates->renderString($settings->notifyEmailSubject, $variables);
            $message = (new Message())
                ->setFrom([$fromEmail => $fromName])
                ->setReplyTo([$fromEmail => $fromName])
                ->setSubject($subject)
                ->setTo([$order->email => $fullName]);


        } catch (\Exception $e) {
            $this->error('Exception: {error}', ['error' => $e->getMessage()]);

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
        } catch (ErrorException $e) {
            $this->error('Error rendering email template {template}: {error}', [
                'template' => $htmlTemplate,
                'error'    => $e->getMessage(),
            ]);
        }

        $templates->setTemplateMode($oldMode);

        if (!$mailer->send($message)) {
            $this->error('Was not able to send notification email to customer: {error}', [
                'error' => print_r($email->getAllErrors(), true),
            ]);

            return false;
        }

        return true;
    }

    public function error($message, $variables = [])
    {
        Craft::error(
            Craft::t('order-notes', $message, $variables),
            __METHOD__);
    }
}
