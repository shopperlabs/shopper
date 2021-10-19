<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Carbon\Carbon;
use function count;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Product\CollectionRule;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Traits\WithConditions;
use Shopper\Framework\Traits\WithSeoAttributes;

class Create extends Component
{
    use WithConditions, WithSeoAttributes;

    public string $name = '';
    public ?string $description = null;
    public string $type = 'auto';
    public ?string $publishedAt = null;
    public ?string $publishedAtFormatted = null;
    public ?string $fileUrl = null;

    public string $condition_match = 'all';

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:fileUpdated' => 'onFileUpdate',
    ];

    public function onTrixValueUpdate($value)
    {
        $this->description = $value;
    }

    public function onFileUpdate($file)
    {
        $this->fileUrl = $file;
    }

    /**
     * Live updated Formatted publishedAt attribute.
     */
    public function updatedPublishedAt()
    {
        $this->publishedAtFormatted = Carbon::createFromFormat('Y-m-d', $this->publishedAt)->toRfc7231String();
    }

    /**
     * Save new entry to the database.
     */
    public function store()
    {
        $this->validate($this->rules());

        $collection = (new CollectionRepository())->create([
            'name' => $this->name,
            'slug' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'match_conditions' => $this->condition_match,
            'seo_title' => $this->seoTitle,
            'seo_description' => $this->seoDescription,
            'published_at' => $this->publishedAt ?? now(),
        ]);

        if ($this->fileUrl) {
            $collection->addMedia($this->fileUrl)->toMediaCollection(config('shopper.system.storage.disks.uploads'));
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

        session()->flash('success', __('Collection successfully added!'));

        $this->redirectRoute('shopper.collections.edit', $collection);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:150|unique:' . shopper_table('collections'),
            'type' => 'required',
        ];
    }

    public function render()
    {
        return view('shopper::livewire.collections.create');
    }
}
