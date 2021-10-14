<?php

namespace Shopper\Framework\Http\Livewire\Forms\Uploads;

use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SingleUpload extends Component
{
    use WithFileUploads;

    public $file;
    public $media;
    public string $inputId;

    protected $rules = [
        'file' => 'nullable|image|max:10240',
    ];

    public function mount($media = null)
    {
        $this->media = $media;
        $this->inputId = 'single-upload-' . uniqid();
    }

    public function updatedFile($file)
    {
        $this->emitUp('shopper:fileUpdated', $file->getRealPath());
    }

    public function removeSingleMediaPlaceholder()
    {
        $this->file = null;
    }

    public function removeMedia(int $id)
    {
        Media::find($id)->delete();

        $this->media = null;

        $this->notify([
            'title' => __('Removed'),
            'message' => __('Media removed from the storage.'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.forms.uploads.single-upload');
    }
}
