<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Shopper\Livewire\Components\Account\Profile;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    Storage::fake(config('shopper.media.storage.disk_name'));

    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(Profile::class, function (): void {
    it('rejects an SVG avatar to prevent stored XSS from same-origin media', function (): void {
        Livewire::test(Profile::class)
            ->set('data.avatar_location', [
                UploadedFile::fake()->create('avatar.svg', 10, 'image/svg+xml'),
            ])
            ->call('save')
            ->assertHasErrors(['data.avatar_location']);
    });

    it('accepts a curated image type as an avatar', function (): void {
        Livewire::test(Profile::class)
            ->set('data.avatar_location', [
                UploadedFile::fake()->image('avatar.png'),
            ])
            ->call('save')
            ->assertHasNoErrors(['data.avatar_location']);

        expect($this->user->refresh()->avatar_type)->toBe('storage');
    });
})->group('livewire', 'security');
