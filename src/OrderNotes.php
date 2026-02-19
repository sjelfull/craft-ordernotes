<?php
/**
 * Order Notes plugin for Craft CMS 5.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes;

use Craft;
use craft\base\Plugin;
use craft\commerce\elements\Order;
use craft\events\PluginEvent;
use craft\helpers\UrlHelper;
use craft\services\Plugins;
use craft\web\twig\variables\CraftVariable;
use superbig\ordernotes\models\Settings;
use superbig\ordernotes\services\OrderNotesService;
use superbig\ordernotes\variables\OrderNotesVariable;
use yii\base\Event;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 *
 * @property  OrderNotesService $orderNotes
 * @method    Settings getSettings()
 */
class OrderNotes extends Plugin
{
    public string $schemaVersion = '2.0.0';

    public function init(): void
    {
        parent::init();

        $this->setComponents([
            'orderNotes' => OrderNotesService::class,
        ]);

        Craft::$app->getView()->hook('cp.commerce.order.edit', function(&$context) {
            if (Craft::$app->getRequest()->getIsCpRequest()) {
                /** @var Order $order */
                $order = $context['order'];

                return OrderNotes::getInstance()->orderNotes->getCode($order);
            }
        });

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function(Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('orderNotes', OrderNotesVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function(PluginEvent $event) {
                if ($event->plugin === $this && Craft::$app->getRequest()->getIsCpRequest()) {
                    Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('settings/plugins/order-notes'));

                    return Craft::$app->end();
                }
            }
        );

        Craft::info(
            Craft::t(
                'order-notes',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate(
            'order-notes/settings',
            [
                'settings' => $this->getSettings(),
            ]
        );
    }
}
