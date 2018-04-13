# Order Notes plugin for Craft CMS 3.x

Order notes for Commerce

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require superbig/craft-ordernotes

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Order Notes.

## Order Notes Overview

The Order Notes plugin will add a section to the Commerce Order details view, where any users with access may add notes.

## Configuring Order Notes

Before using the plugin, you should update all the settings values.

Alternatively you can use the example config file to override the settings:

```php
<?php
return [
    // Sender Email Address
    'notifyEmailFrom'         => '',

    // Sender Name
    'notifyEmailFromName'     => '',

    // HTML email template - will receive order and note as variables
    'notifyEmailTemplate'     => '',

    // Text email template (defaults to HTML if not set) - will receive order and note as variables
    'notifyEmailTemplateText' => '',

    // Email subject - will receive order and note as variables
    'notifyEmailSubject'      => '',
];
```

## Order Notes Roadmap

* Add file attachments

Brought to you by [Superbig](https://superbig.co)
