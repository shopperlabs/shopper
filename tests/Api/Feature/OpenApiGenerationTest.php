<?php

declare(strict_types=1);

uses(Tests\Api\TestCase::class);

it('exports a valid OpenAPI document through scramble', function (): void {
    $path = sys_get_temp_dir().'/shopper-api-openapi.json';

    $this->artisan('scramble:export', ['--path' => $path])->assertSuccessful();

    expect(file_exists($path))->toBeTrue();

    $document = json_decode((string) file_get_contents($path), true);

    expect($document)
        ->toBeArray()
        ->toHaveKey('openapi')
        ->toHaveKey('info');

    @unlink($path);
});
