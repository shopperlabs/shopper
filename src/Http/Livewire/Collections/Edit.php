<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\System\File;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Traits\WithConditions;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;

class Edit extends AbstractBaseComponent
{
    use WithFileUploads,
        WithUploadProcess,
        WithConditions,
        WithSeoAttributes;

    /**
     * Collection Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $collection;

    /**
     * Collection Id.
     *
     * @var int
     */
    public $collectionId;

    /**
     * Collection name.
     *
     * @var string
     */
    public $name;

    /**
     * Collection sample description.
     *
     * @var string
     */
    public $description;

    /**
     * Type of collection that's be created.
     *
     * @var string
     */
    public $type = 'auto';

    /**
     * Publish date for the collection.
     *
     * @var string
     */
    public $publishedAt;

    /**
     * Formatted publishedAt date.
     *
     * @var string
     */
    public $publishedAtFormatted;

    /**
     * The condition apply to the product of the collection.
     *
     * @var string
     */
    public $condition_match = 'all';

    /**
     * Products of the collections
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $products;

    /**
     * Upload listeners.
     *
     * @var array<string>
     */
    protected $listeners = ['fileDeleted'];

    /**
     * Component mounted action.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $collection
     *
     * @return void
     */
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
        $this->updateSeo = $collection->seo_title !== null;
        $this->seoTitle = $collection->seo_title;
        $this->seoDescription = $collection->seo_description;
    }

    /**
     * Update collection item in the database.
     *
     * @return void
     */
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
            if ($this->collection->files->isNotEmpty()) {
                foreach ($this->collection->files as $file) {
                    Storage::disk(config('shopper.system.storage.disks.uploads'))->delete($file->disk_name);
                }
                File::query()->where('filetable_id', $this->collectionId)->delete();
            }

            $this->uploadFile(config('shopper.system.models.collection'), $this->collection->id);
        }

        session()->flash('success', __('Collection successfully updated!'));

        $this->redirectRoute('shopper.collections.index');
    }

    /**
     * Define is the current action is create or update.
     *
     * @return false
     */
    public function isUpdate(): bool
    {
        return true;
    }

    /**
     * Live updated Formatted publishedAt attribute.
     *
     * @return void
     */
    public function updatedPublishedAt()
    {
        $this->publishedAtFormatted = Carbon::createFromFormat('Y-m-d', $this->publishedAt)->toRfc7231String();
    }

    /**
     * Component validation rules.
     *
     * @return array<string>
     */
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
     * Listen when a file is removed from the storage
     * and update the user screen and remove image preview.
     *
     * @return void
     */
    public function fileDeleted()
    {
        $this->media = null;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.collections.edit', [
            'media' => $this->collection->files->isNotEmpty()
                ? $this->collection->files->first()
                : null,
        ]);
    }
}
