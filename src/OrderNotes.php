<?php
/**
 * Order Notes plugin for Craft CMS 3.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes;

use craft\helpers\UrlHelper;
use superbig\ordernotes\services\OrderNotesService as OrderNotesServiceService;
use superbig\ordernotes\services\OrderNotesService;
use superbig\ordernotes\variables\OrderNotesVariable;
use superbig\ordernotes\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class OrderNotes
 *
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 *
 * @property  OrderNotesService $orderNotes
 * @method   Settings getSettings()
 */
class OrderNotes extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var OrderNotes
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '2.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->getView()->hook('cp.commerce.order.edit', function(&$context) {
            if (Craft::$app->getRequest()->getIsCpRequest()) {
                $order = $context['order'];
                $code  = OrderNotes::$plugin->orderNotes->getCode($order);

                return $code;
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

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'order-notes/settings',
            [
                'settings' => $this->getSettings(),
            ]
        );
    }
}
