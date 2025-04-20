<?php
/**
 * Order Notes plugin for Craft CMS 3.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\assetbundles\ordernotes;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class OrderNotesAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@superbig/ordernotes/assetbundles/ordernotes/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/OrderNotes.js',
        ];

        $this->css = [
            'css/OrderNotes.css',
        ];

        parent::init();
    }
}
