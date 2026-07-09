<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Shopper\Livewire\Pages\Product\Media;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('products.edit');
    $this->actingAs($this->user);

    $this->product = Product::factory()->create();
});

describe(Media::class, function (): void {
    it('can render media page', function (): void {
        Livewire::test(Media::class, ['product' => $this->product])
            ->assertOk();
    });

    it('hides the use as thumbnail action when the gallery is empty', function (): void {
        Livewire::test(Media::class, ['product' => $this->product])
            ->assertActionHidden('useAsThumbnail')
            ->assertDontSee(__('shopper::pages/products.choose_from_images'));
    });

    it('renders the use as thumbnail action when the gallery has images', function (): void {
        $this->product->addMedia(UploadedFile::fake()->image('gallery.jpg'))
            ->toMediaCollection(config('shopper.media.storage.collection_name'));

        Livewire::test(Media::class, ['product' => $this->product])
            ->assertSee(__('shopper::pages/products.choose_from_images'));
    });

    it('sets a gallery image as the product thumbnail', function (): void {
        $image = $this->product->addMedia(UploadedFile::fake()->image('gallery.jpg'))
            ->toMediaCollection(config('shopper.media.storage.collection_name'));

        Livewire::test(Media::class, ['product' => $this->product])
            ->callAction('useAsThumbnail', ['media_id' => $image->id])
            ->assertNotified();

        $thumbnail = $this->product->refresh()
            ->getFirstMedia(config('shopper.media.storage.thumbnail_collection'));

        expect($thumbnail?->file_name)->toBe($image->file_name);
    });
})->group('livewire', 'products', 'media');
