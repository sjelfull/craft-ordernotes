<?php

use superbig\ordernotes\models\OrderNotesModel;
use superbig\ordernotes\models\Settings;

it('creates a model with default values', function() {
    $model = new OrderNotesModel();

    expect($model->id)->toBeNull()
        ->and($model->userId)->toBeNull()
        ->and($model->orderId)->toBeNull()
        ->and($model->siteId)->toBeNull()
        ->and($model->message)->toBe('')
        ->and($model->notify)->toBeFalse();
});

it('validates notify as boolean', function() {
    $model = new OrderNotesModel();
    $model->notify = true;

    expect($model->validate())->toBeTrue();
});

it('returns empty string for username when no user', function() {
    $model = new OrderNotesModel();

    expect($model->getUsername())->toBe('');
});

it('creates settings with default values', function() {
    $settings = new Settings();

    expect($settings->notifyEmailFrom)->toBe('')
        ->and($settings->notifyEmailFromName)->toBe('')
        ->and($settings->notifyEmailTemplate)->toBe('')
        ->and($settings->notifyEmailTemplateText)->toBe('')
        ->and($settings->notifyEmailSubject)->toBe('');
});
