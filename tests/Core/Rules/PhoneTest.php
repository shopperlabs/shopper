<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Shopper\Core\Rules\Phone;

uses(Tests\Core\TestCase::class);

describe(Phone::class, function (): void {
    it('accepts a valid national number for the given region', function (): void {
        $validator = Validator::make(
            ['phone' => '655123456'],
            ['phone' => [new Phone('CM')]]
        );

        expect($validator->passes())->toBeTrue();
    });

    it('accepts a valid international number regardless of region', function (): void {
        $validator = Validator::make(
            ['phone' => '+33612345678'],
            ['phone' => [new Phone('CM')]]
        );

        expect($validator->passes())->toBeTrue();
    });

    it('rejects a number that is not valid for its region', function (): void {
        $validator = Validator::make(
            ['phone' => '12'],
            ['phone' => [new Phone('CM')]]
        );

        expect($validator->fails())->toBeTrue();
    });

    it('rejects an unparseable value', function (): void {
        $validator = Validator::make(
            ['phone' => 'not-a-phone'],
            ['phone' => [new Phone('CM')]]
        );

        expect($validator->fails())->toBeTrue();
    });

    it('ignores empty values so `required` stays a separate concern', function (): void {
        $validator = Validator::make(
            ['phone' => null],
            ['phone' => [new Phone('CM')]]
        );

        expect($validator->passes())->toBeTrue();
    });
});
