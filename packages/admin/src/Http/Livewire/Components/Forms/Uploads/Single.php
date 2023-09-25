<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Forms\Uploads;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class Single extends Component
{
    use WithFileUploads;

    public $file;

    public $media;

    public string $inputId;

    protected $rules = [
        'file' => 'nullable|image|max:5120',
    ];

    public function mount($media = null): void
    {
        $this->media = $media;
        $this->inputId = 'single-upload-' . uniqid();
    }

    public function updatedFile($file): void
    {
        $this->emitUp('shopper:fileUpdated', $file->getRealPath());
    }

    public function removeSingleMediaPlaceholder(): void
    {
        $this->file = null;
    }

    public function removeMedia(int $id): void
    {
        Media::query()->find($id)->delete();

        $this->media = null;

        Notification::make()
            ->body(__('shopper::notifications.actions.remove', ['item' => __('shopper::words.media')]))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.forms.uploads.single');
    }
}
