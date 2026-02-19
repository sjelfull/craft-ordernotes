# Order Notes for Craft Commerce

Add internal notes to Craft Commerce orders. Team members can annotate orders directly from the control panel and optionally notify customers by email.

![Screenshot](resources/img/screenshot-plugin.png)

## Requirements

- Craft CMS 5.5+
- Craft Commerce 5.0+
- PHP 8.2+

## Installation

```bash
composer require superbig/craft-ordernotes
```

Then go to **Settings → Plugins** and install Order Notes.

## Quick Start

Once installed, a notes panel appears on every Commerce order edit screen. Type a message, optionally check "Notify customer", and submit.

## Using Order Notes in Templates

Access order notes in Twig via the `craft.orderNotes` variable.

### Get notes by Order object

```twig
{% set order = craft.commerce.orders.number(orderNumber).one() %}
{% set notes = craft.orderNotes.getNotesForOrder(order) %}

{% if notes %}
    <h3>Order Notes</h3>
    {% for note in notes %}
        <div class="note">
            <strong>{{ note.getUsername() }}</strong>
            <time>{{ note.dateCreated|date('Y-m-d H:i') }}</time>
            <p>{{ note.message|nl2br }}</p>
        </div>
    {% endfor %}
{% endif %}
```

### Get notes by order ID

```twig
{% set notes = craft.orderNotes.getNotesByOrderId(order.id) %}
```

### Available properties

| Property | Type | Description |
|----------|------|-------------|
| `note.id` | `int` | Note ID |
| `note.message` | `string` | Note content |
| `note.orderId` | `int` | Associated order ID |
| `note.userId` | `int` | Author's user ID |
| `note.notify` | `bool` | Whether the customer was notified |
| `note.dateCreated` | `DateTime` | When the note was created |
| `note.dateUpdated` | `DateTime` | When the note was last updated |
| `note.getUser()` | `User` | The User element who created the note |
| `note.getUsername()` | `string` | Username of the note author |

## Configuration

Configure via the CP settings page or a `config/order-notes.php` file:

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `notifyEmailFrom` | `string` | `''` | Sender email address (falls back to system email) |
| `notifyEmailFromName` | `string` | `''` | Sender name (falls back to system name) |
| `notifyEmailTemplate` | `string` | `''` | Path to HTML email template |
| `notifyEmailTemplateText` | `string` | `''` | Path to plain text email template |
| `notifyEmailSubject` | `string` | `''` | Email subject (Twig, receives `order` and `note`) |

### Config file example

```php
<?php

return [
    'notifyEmailFrom' => '',
    'notifyEmailFromName' => '',
    'notifyEmailTemplate' => '_emails/order-note',
    'notifyEmailTemplateText' => '_emails/order-note-text',
    'notifyEmailSubject' => 'Update on your order {{ order.reference }}',
];
```

Email templates receive `order` (Commerce Order) and `note` (OrderNotesModel) as variables.

## Support

- [GitHub Issues](https://github.com/sjelfull/craft-ordernotes/issues)

Brought to you by [Superbig](https://superbig.co)
