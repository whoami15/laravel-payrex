<?php

declare(strict_types=1);

use LegionHQ\LaravelPayrex\Data\PayrexObject;

it('hydrates common properties from attributes', function () {
    $obj = new PayrexObject([
        'id' => 'obj_123',
        'resource' => 'test_object',
        'livemode' => false,
        'metadata' => ['key' => 'value'],
        'created_at' => 1700000000,
        'updated_at' => 1700000100,
    ]);

    expect($obj->id)->toBe('obj_123')
        ->and($obj->resource)->toBe('test_object')
        ->and($obj->livemode)->toBeFalse()
        ->and($obj->metadata)->toBe(['key' => 'value'])
        ->and($obj->createdAt)->toBe(1700000000)
        ->and($obj->updatedAt)->toBe(1700000100);
});

it('supports ArrayAccess for backwards compatibility', function () {
    $obj = new PayrexObject([
        'id' => 'obj_123',
        'nested' => ['foo' => 'bar'],
    ]);

    expect(isset($obj['id']))->toBeTrue()
        ->and($obj['id'])->toBe('obj_123')
        ->and($obj['nested']['foo'])->toBe('bar')
        ->and(isset($obj['nonexistent']))->toBeFalse()
        ->and($obj['nonexistent'])->toBeNull();
});

it('is immutable via ArrayAccess', function () {
    $obj = new PayrexObject(['id' => 'obj_123']);

    $obj['id'] = 'changed';
    unset($obj['id']);

    expect($obj['id'])->toBe('obj_123');
});

it('serializes to the raw attributes array', function () {
    $attributes = ['id' => 'obj_123', 'resource' => 'test', 'extra' => 'field'];
    $obj = new PayrexObject($attributes);

    expect($obj->toArray())->toBe($attributes)
        ->and(json_encode($obj))->toBe(json_encode($attributes));
});

it('creates from static factory method', function () {
    $obj = PayrexObject::from(['id' => 'obj_456', 'resource' => 'test']);

    expect($obj)->toBeInstanceOf(PayrexObject::class)
        ->and($obj->id)->toBe('obj_456');
});

it('handles missing attributes gracefully', function () {
    $obj = new PayrexObject([]);

    expect($obj->id)->toBeNull()
        ->and($obj->resource)->toBeNull()
        ->and($obj->livemode)->toBeNull()
        ->and($obj->metadata)->toBeNull()
        ->and($obj->createdAt)->toBeNull()
        ->and($obj->updatedAt)->toBeNull();
});
