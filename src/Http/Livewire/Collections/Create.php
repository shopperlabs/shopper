<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Carbon\Carbon;
use function count;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Traits\WithConditions;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;
use Shopper\Framework\Models\Shop\Product\CollectionRule;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class Create extends Component
{
    use WithFileUploads;
    use WithUploadProcess;
    use WithConditions;
    use WithSeoAttributes;

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

        session()->flash('success', __('Collection successfully added!'));

        $this->redirectRoute('shopper.collections.edit', $collection);
    }

    /**
     * Component validation rules.
     *
     * @return array<string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:150|unique:' . shopper_table('collections'),
            'file' => 'nullable|image|max:1024',
            'type' => 'required',
        ];
    }

    public function render()
    {
        return view('shopper::livewire.collections.create');
    }
}
