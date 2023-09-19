<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Collections;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Traits\WithConditions;
use Shopper\Framework\Traits\WithSeoAttributes;

class Edit extends AbstractBaseComponent
{
    use WithConditions;
    use WithSeoAttributes;

    public Model $collection;

    public Collection $products;

    public int $collectionId;

    public string $name = '';

    public ?string $description = null;

    public string $type = 'auto';

    public ?Carbon $publishedAt = null;

    public ?string $publishedAtFormatted = null;

    public string $condition_match = 'all';

    public ?string $fileUrl = null;

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    protected $listeners = [
        'shopper:fileUpdated' => 'onFileUpdate',
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function mount($collection): void
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
        $this->seoTitle = $collection->seo_title ?? $collection->name;
        $this->seoDescription = $collection->seo_description;
    }

    public function onTrixValueUpdate(string $value): void
    {
        $this->description = $value;
    }

    public function onFileUpdate($file): void
    {
        $this->fileUrl = $file;
    }

    public function store(): void
    {
        $this->validate($this->rules());

        (new CollectionRepository())->getById($this->collection->id)->update([
            'name' => $this->name,
            'slug' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'match_conditions' => $this->condition_match,
            'seo_title' => $this->seoTitle,
            'seo_description' => str_limit($this->seoDescription, 157),
            'published_at' => $this->publishedAt,
        ]);

        if ($this->fileUrl) {
            $this->collection
                ->addMedia($this->fileUrl)
                ->toMediaCollection(config('shopper.system.storage.disks.uploads'));
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
            'type' => 'sometimes|required',
        ];
    }

    public function isUpdate(): bool
    {
        return true;
    }

    public function updatedPublishedAt($value): void
    {
        $this->publishedAtFormatted = Carbon::createFromFormat('Y-m-d H:i', $value)->toRfc7231String();
    }

    public function render(): View
    {
        return view('shopper::livewire.collections.edit');
    }
}
