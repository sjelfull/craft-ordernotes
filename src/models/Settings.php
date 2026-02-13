<?php
/**
 * Order Notes plugin for Craft CMS 5.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\models;

use craft\base\Model;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class Settings extends Model
{
    public string $notifyEmailFrom = '';
    public string $notifyEmailFromName = '';
    public string $notifyEmailTemplate = '';
    public string $notifyEmailTemplateText = '';
    public string $notifyEmailSubject = '';
}
