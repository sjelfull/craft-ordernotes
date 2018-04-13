<?php
/**
 * Order Notes plugin for Craft CMS 3.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\models;

use superbig\ordernotes\OrderNotes;

use Craft;
use craft\base\Model;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 *
 * @property string $notifyEmailFrom
 * @property string $notifyEmailFromName
 * @property string $notifyEmailTemplate
 * @property string $notifyEmailTemplateText
 * @property string $notifyEmailSubject
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $notifyEmailFrom         = '';
    public $notifyEmailFromName     = '';
    public $notifyEmailTemplate     = '';
    public $notifyEmailTemplateText = '';
    public $notifyEmailSubject      = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //['someAttribute', 'string'],
        ];
    }
}
