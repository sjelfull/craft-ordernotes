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
