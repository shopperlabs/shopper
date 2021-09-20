<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Carbon\Carbon;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Shopper\Framework\Models\System\File;
use Shopper\Framework\Traits\WithConditions;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class Edit extends AbstractBaseComponent
{
    use WithFileUploads, WithUploadProcess, WithConditions, WithSeoAttributes;

    public $collection;
    public $products;
    public int $collectionId;
    public string $name = '';
    public ?string $description = null;
    public string $type = 'auto';
    public ?string $publishedAt = null;
    public ?string $publishedAtFormatted = null;
    public string $condition_match = 'all';

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    protected $listeners = [
        'mediaDeleted',
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function mount($collection)
    {
        $this->collection = $collection;
        $this->products = $collection->products;
        $this->collectionId = $collection->id;
        $this->name = $collection->name;
        $this->description = $collection->description;
        $this->type = $collection->type;
        $this->condition_match = $collection->match_conditions;
        $this->publishedAt = $collection->published_at;
        $this->publishedAtFormatted = Carbon::createFromFormat('Y-m-d', $collection->published_at->toDateString())->toRfc7231String();
        $this->updateSeo = true;
        $this->seoTitle = $collection->seo_title;
        $this->seoDescription = $collection->seo_description;
    }

    public function onTrixValueUpdate($value)
    {
        $this->description = $value;
    }

    public function store()
    {
        $this->validate($this->rules());

        (new CollectionRepository())->getById($this->collection->id)->update([
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'match_conditions' => $this->condition_match,
            'seo_title' => $this->seoTitle,
            'seo_description' => str_limit($this->seoDescription, 157),
            'published_at' => $this->publishedAt,
        ]);

        if ($this->file) {
            $this->collection->addMedia($this->file->getRealPath())->toMediaCollection(config('shopper.system.storage.disks.uploads'));
        }

        session()->flash('success', __('Collection successfully updated!'));

        $this->redirectRoute('shopper.collections.index');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'max:150',
                Rule::unique(shopper_table('collections'), 'name')->ignore($this->collectionId),
            ],
            'file' => 'sometimes|nullable|image|max:1024',
            'type' => 'sometimes|required',
        ];
    }

    /**
     * Define is the current action is create or update for the SEO Trait.
     *
     * @return false
     */
    public function isUpdate(): bool
    {
        return true;
    }

    /**
     * Live updated Formatted publishedAt attribute.
     */
    public function updatedPublishedAt()
    {
        $this->publishedAtFormatted = Carbon::createFromFormat('Y-m-d', $this->publishedAt)->toRfc7231String();
    }

    /**
     * Listen when a file is removed from the storage
     * and update the user screen and remove image preview.
     */
    public function mediaDeleted()
    {
        $this->media = null;
    }

    public function render()
    {
        return view('shopper::livewire.collections.edit', [
            'media' => $this->collection->getFirstMedia(config('shopper.system.storage.disks.uploads')),
        ]);
    }
}
