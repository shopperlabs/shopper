<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Models\Shop\Product\CollectionRule;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Traits\WithConditions;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;

class Create extends Component
{
    use WithFileUploads,
        WithUploadProcess,
        WithConditions,
        WithSeoAttributes;

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
     * Live updated Formatted publishedAt attribute.
     *
     * @return void
     */
    public function updatedPublishedAt()
    {
        $this->publishedAtFormatted = Carbon::createFromFormat('Y-m-d', $this->publishedAt)->toRfc7231String();
    }

    /**
     * Save new entry to the database.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        $collection = (new CollectionRepository())->create([
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'match_conditions' => $this->condition_match,
            'seo_title' => $this->seoTitle,
            'seo_description' => $this->seoDescription,
            'published_at' => $this->publishedAt ?? now(),
        ]);

        if ($this->file) {
            $this->uploadFile(config('shopper.system.models.collection'), $collection->id);
        }

        if ($this->type === 'auto' && count($this->conditions) > 0 && $this->rule) {

            foreach ($this->rule as $key => $value) {
                CollectionRule::query()->create([
                    'collection_id' => $collection->id,
                    'rule' => $this->rule[$key],
                    'operator' => $this->operator[$key],
                    'value' => $this->value[$key],
                ]);
            }

            $this->conditions = [];
            $this->resetConditionsFields();
        }

        session()->flash('success', __("Collection successfully added!"));
        $this->redirectRoute('shopper.collections.edit', $collection);
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'name' => 'required|max:150|unique:'.shopper_table('collections'),
            'file' => 'nullable|image|max:1024',
            'type' => 'required',
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.collections.create');
    }
}
