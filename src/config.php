<?php
/**
 * Order Notes plugin for Craft CMS 3.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

/**
 * Order Notes config.php
 *
 * This file exists only as a template for the Order Notes settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'order-notes.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [
    // Sender Email Address
    'notifyEmailFrom' => '',

    // Sender Name
    'notifyEmailFromName' => '',

    // HTML email template - will receive order and note as variables
    'notifyEmailTemplate' => '',

    // Text email template (defaults to HTML if not set) - will receive order and note as variables
    'notifyEmailTemplateText' => '',

    // Email subject - will receive order and note as variables
    'notifyEmailSubject' => '',
];
