<?php

use superbig\ordernotes\OrderNotes;
use superbig\ordernotes\services\OrderNotesService;

it('can be instantiated directly', function() {
    $service = new OrderNotesService();

    expect($service)->toBeInstanceOf(OrderNotesService::class);
});

it('is accessible from the plugin instance', function() {
    $service = OrderNotes::getInstance()->orderNotes;

    expect($service)->toBeInstanceOf(OrderNotesService::class);
})->skip(fn() => OrderNotes::getInstance() === null, 'Plugin not installed in test environment');

it('returns null when formatting empty notes', function() {
    $service = new OrderNotesService();

    expect($service->formatOrderNotes([]))->toBeNull();
    expect($service->formatOrderNotes(null))->toBeNull();
});
