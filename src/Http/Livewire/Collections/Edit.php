<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Models\System\File;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Traits\WithConditions;
use Shopper\Framework\Traits\WithUploadProcess;

class Edit extends Component
{
    use WithFileUploads, WithUploadProcess, WithConditions;

    /**
     * Upload listeners.
     *
     * @var string[]
     */
    protected $listeners = ['fileDeleted'];

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
    public $name = '';

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
     * Update SEO elements.
     *
     * @var bool
     */
    public $updateSeo = false;

    /**
     * Seo Display title.
     *
     * @var string
     */
    public $seoTitle;

    /**
     * Seo description.
     *
     * @var string
     */
    public $seoDescription;

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
     * Component mounted action.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $collection
     * @return void
     */
    public function mount($collection)
    {
        $this->collection = $collection;
        $this->collectionId = $collection->id;
        $this->name = $collection->name;
        $this->description = $collection->description;
        $this->type = $collection->type;
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
            'seo_description' => $this->seoDescription,
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

        session()->flash('success', __("Collection successfully updated!"));
        $this->redirectRoute('shopper.collections.index');
    }

    /**
     * Real-time component validation.
     *
     * @param  string  $field
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    /**
     * Display the block to update SEO values.
     *
     * @return void
     */
    public function updateSeo()
    {
        $this->seoTitle = $this->name;
        $this->seoDescription = strip_tags(nl2br($this->description));
        $this->updateSeo = true;
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
     * @return string[]
     */
    public function rules()
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
