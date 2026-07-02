<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Shopper\Livewire\Components\Account\Profile;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(Profile::class, function (): void {
    it('can update the profile information', function (): void {
        Livewire::test(Profile::class)
            ->fillForm([
                'first_name' => 'Arthur',
                'last_name' => 'Monney',
                'email' => $this->user->email,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        expect($this->user->refresh())
            ->first_name->toBe('Arthur')
            ->last_name->toBe('Monney');
    });

    it('fills the form with only the editable profile fields', function (): void {
        $component = Livewire::test(Profile::class);

        expect(array_keys($component->get('data')))
            ->toEqualCanonicalizing([
                'avatar_location',
                'first_name',
                'last_name',
                'email',
                'phone_number',
            ]);
    });

    it('rejects an SVG file for the avatar upload', function (): void {
        Storage::fake(config('shopper.media.storage.disk_name'));

        Livewire::test(Profile::class)
            ->fillForm([
                'avatar_location' => UploadedFile::fake()->createWithContent(
                    'payload.svg',
                    '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"></svg>'
                ),
            ])
            ->call('save')
            ->assertHasFormErrors(['avatar_location']);
    });
})->group('livewire', 'components', 'account');
