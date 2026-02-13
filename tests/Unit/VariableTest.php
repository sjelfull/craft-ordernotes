<?php

use superbig\ordernotes\services\OrderNotesService;
use superbig\ordernotes\variables\OrderNotesVariable;

it('can be instantiated with injected service', function() {
    $service = new OrderNotesService();
    $variable = new OrderNotesVariable($service);

    expect($variable)->toBeInstanceOf(OrderNotesVariable::class);
});

it('can be instantiated without arguments', function() {
    $variable = new OrderNotesVariable();

    expect($variable)->toBeInstanceOf(OrderNotesVariable::class);
});

it('returns null for nonexistent order notes', function() {
    $service = new OrderNotesService();
    $variable = new OrderNotesVariable($service);

    $result = $variable->getNotesByOrderId(999999);

    expect($result)->toBeNull();
});
