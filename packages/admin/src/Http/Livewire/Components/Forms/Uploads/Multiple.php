<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Forms\Uploads;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class Multiple extends Component
{
    use WithFileUploads;

    public $files = [];

    public $images = [];

    public string $inputId;

    protected $listeners = [
        'fileDeleted' => 'render',
    ];

    protected $rules = [
        'files.*' => 'nullable|max:5120',
    ];

    public function mount($images = []): void
    {
        $this->images = $images;
        $this->inputId = 'files-upload-' . uniqid();
    }

    public function updatedFiles(array $files): void
    {
        $filesUrl = collect();

        foreach ($files as $file) {
            $filesUrl->push($file->getRealPath());
        }

        $this->emitUp('shopper:filesUpdated', $filesUrl);
    }

    public function removeMedia(int $id): void
    {
        Media::query()->find($id)->delete();

        $this->emitSelf('fileDeleted');

        Notification::make()
            ->body(__('shopper::notifications.actions.remove', ['item' => __('shopper::words.media')]))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.forms.uploads.multiple');
    }
}
