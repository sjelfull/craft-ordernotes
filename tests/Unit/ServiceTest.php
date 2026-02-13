<?php

use superbig\ordernotes\OrderNotes;
use superbig\ordernotes\services\OrderNotesService;

it('is accessible from the plugin instance', function() {
    $service = OrderNotes::getInstance()->orderNotes;

    expect($service)->toBeInstanceOf(OrderNotesService::class);
});

it('returns null for nonexistent note by id', function() {
    $service = new OrderNotesService();

    expect($service->getNoteById(999999))->toBeNull();
});

it('returns null for nonexistent order notes', function() {
    $service = new OrderNotesService();

    expect($service->getNotesByOrderId(999999))->toBeNull();
});

it('returns null when formatting empty notes', function() {
    $service = new OrderNotesService();

    expect($service->formatOrderNotes([]))->toBeNull();
    expect($service->formatOrderNotes(null))->toBeNull();
});
