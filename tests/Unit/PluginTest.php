<?php

use superbig\ordernotes\OrderNotes;
use superbig\ordernotes\services\OrderNotesService;

it('can be instantiated', function() {
    $plugin = OrderNotes::getInstance();

    expect($plugin)->not->toBeNull()
        ->and($plugin)->toBeInstanceOf(OrderNotes::class);
})->skip(fn() => OrderNotes::getInstance() === null, 'Plugin not installed in test environment');

it('has the correct handle', function() {
    $plugin = OrderNotes::getInstance();

    expect($plugin->handle)->toBe('order-notes');
})->skip(fn() => OrderNotes::getInstance() === null, 'Plugin not installed in test environment');

it('registers the orderNotes service', function() {
    $plugin = OrderNotes::getInstance();

    expect($plugin->orderNotes)->toBeInstanceOf(OrderNotesService::class);
})->skip(fn() => OrderNotes::getInstance() === null, 'Plugin not installed in test environment');

it('has settings', function() {
    $plugin = OrderNotes::getInstance();
    $settings = $plugin->getSettings();

    expect($settings)->toBeInstanceOf(\superbig\ordernotes\models\Settings::class)
        ->and($settings->notifyEmailFrom)->toBe('')
        ->and($settings->notifyEmailFromName)->toBe('')
        ->and($settings->notifyEmailTemplate)->toBe('')
        ->and($settings->notifyEmailTemplateText)->toBe('')
        ->and($settings->notifyEmailSubject)->toBe('');
})->skip(fn() => OrderNotes::getInstance() === null, 'Plugin not installed in test environment');
