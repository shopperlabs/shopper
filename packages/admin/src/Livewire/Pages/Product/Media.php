<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Shopper\Actions\Store\Product\UseImageAsThumbnail;
use Shopper\Components\Form\ImagePicker;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

/**
 * @property-read Schema $form
 */
#[Layout('shopper::components.layouts.product')]
class Media extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /** @var Model&Product */
    #[Locked]
    public Product $product;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->product->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('thumbnail')
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->helperText(__('shopper::pages/products.thumbnail_helpText'))
                    ->image()
                    ->maxSize(config('shopper.media.max_size.thumbnail'))
                    ->columnSpan(['lg' => 1]),
                SpatieMediaLibraryFileUpload::make('images')
                    ->collection(config('shopper.media.storage.collection_name'))
                    ->label(__('shopper::words.images'))
                    ->helperText(__('shopper::pages/products.images_helpText'))
                    ->multiple()
                    ->panelLayout('grid')
                    ->maxSize(config('shopper.media.max_size.images'))
                    ->columnSpan(['lg' => 2]),
            ])
            ->columns(3)
            ->statePath('data')
            ->model($this->product);
    }

    public function useAsThumbnailAction(): Action
    {
        return Action::make('useAsThumbnail')
            ->authorize('products.edit')
            ->label(__('shopper::pages/products.choose_from_images'))
            ->color('gray')
            ->visible(fn (): bool => $this->product->getMedia((string) config('shopper.media.storage.collection_name'))->isNotEmpty())
            ->modalHeading(__('shopper::pages/products.use_as_thumbnail'))
            ->modalDescription(__('shopper::pages/products.use_as_thumbnail_description'))
            ->modalWidth(Width::Large)
            ->modalSubmitActionLabel(__('shopper::pages/products.use_as_thumbnail'))
            ->schema([
                ImagePicker::make('media_id')
                    ->hiddenLabel()
                    ->required()
                    ->options(
                        fn (): array => $this->product->getMedia((string) config('shopper.media.storage.collection_name'))
                            ->mapWithKeys(fn (SpatieMedia $media): array => [$media->id => $media->getUrl()])
                            ->all()
                    ),
            ])
            ->action(function (array $data): void {
                app()->call(UseImageAsThumbnail::class, [
                    'model' => $this->product,
                    'mediaId' => $data['media_id'],
                ]);

                $this->form->fill($this->product->refresh()->toArray());

                $this->dispatch('product.updated');

                Notification::make()
                    ->body(__('shopper::pages/products.notifications.media_update'))
                    ->success()
                    ->send();
            });
    }

    public function store(): void
    {
        $this->authorize('products.edit');

        $this->validate();

        $this->product->update($this->form->getState());

        $this->dispatch('product.updated');

        Notification::make()
            ->body(__('shopper::pages/products.notifications.media_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.media')
            ->title($this->product->name.' ~ '.__('shopper::words.media'));
    }
}
