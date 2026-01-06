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

## Using Order Notes

### Displaying notes in templates

You can display order notes in your front-end templates using the `craft.orderNotes` variable.

#### Get notes by Order object

```twig
{% set order = craft.commerce.orders.number('xxxxx').one() %}
{% set notes = craft.orderNotes.getNotesForOrder(order) %}

{% if notes %}
    <h3>Order Notes</h3>
    <ul>
    {% for note in notes %}
        <li>
            <strong>{{ note.getUsername() }}</strong> - {{ note.dateCreated|date('Y-m-d H:i') }}<br>
            {{ note.message|nl2br }}
        </li>
    {% endfor %}
    </ul>
{% endif %}
```

#### Get notes by order ID

```twig
{% set notes = craft.orderNotes.getNotesByOrderId(orderId) %}

{% if notes %}
    {% for note in notes %}
        <div class="note">
            <p>{{ note.message }}</p>
            <small>By {{ note.getUsername() }} on {{ note.dateCreated|date('short') }}</small>
        </div>
    {% endfor %}
{% endif %}
```

#### Available note properties

Each note object has the following properties:

- `note.id` - The note ID
- `note.message` - The note message content
- `note.userId` - The ID of the user who created the note
- `note.orderId` - The associated order ID
- `note.notify` - Whether the customer was notified (boolean)
- `note.dateCreated` - When the note was created
- `note.dateUpdated` - When the note was last updated
- `note.getUser()` - Get the User object who created the note
- `note.getUsername()` - Get the username of the user who created the note

## Order Notes Roadmap

* Add file attachments

Brought to you by [Superbig](https://superbig.co)
