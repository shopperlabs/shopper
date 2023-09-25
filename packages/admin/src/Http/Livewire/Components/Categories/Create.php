<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Categories;

use Illuminate\Contracts\View\View;
use Shopper\Contracts\HasForm;
use Shopper\Core\Repositories\Ecommerce\CategoryRepository;
use Shopper\Core\Traits\Attributes\WithChoicesCategories;
use Shopper\Core\Traits\Attributes\WithSeoAttributes;
use Shopper\Http\Livewire\AbstractBaseComponent;

class Create extends AbstractBaseComponent implements HasForm
{
    use UseForm;
    use WithChoicesCategories;
    use WithSeoAttributes;

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:fileUpdated' => 'onFileUpdate',
    ];

    public function store(): void
    {
        $category = $this->save((new CategoryRepository())->model());

        if ($this->fileUrl) {
            $category->addMedia($this->fileUrl)->toMediaCollection(config('shopper.core.storage.collection_name'));
        }

        session()->flash('success', __('shopper::notifications.actions.create', ['item' => __('shopper::words.category')]));

        $this->redirectRoute('shopper.categories.index');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.categories.create', [
            'categories' => (new CategoryRepository())
                ->makeModel()
                ->scopes('enabled')
                ->tree()
                ->orderBy('name')
                ->get()
                ->toTree(),
        ]);
    }
}
