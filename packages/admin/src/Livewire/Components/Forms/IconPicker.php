<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Forms;

use BladeUI\Icons\Factory as IconFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Shopper\Traits\CanBeCacheable;
use Shopper\Traits\HasCollectionPaginate;

class IconPicker extends Component
{
    use CanBeCacheable;
    use HasCollectionPaginate;

    public ?string $value = null;

    public string $search = '';

    public int $perPage = 60;

    public function mount(?string $value = null): void
    {
        $this->value = $value;
    }

    public function loadMore(): void
    {
        $this->perPage += 30;
    }

    public function selectedIcon(string $icon): void
    {
        $this->value = $icon;
        $this->emitUp('selectedIcon', $icon);
    }

    private function loadIcons(): Collection
    {
        $sets = $this->tryCache(
            key: 'icon-picker.fields',
            callback: function (): Collection {
                $iconsFactory = App::make(IconFactory::class);

                return collect($iconsFactory->all());
            }
        );

        $icons = [];

        foreach ($sets as $set) {
            $prefix = $set['prefix'];

            foreach ($set['paths'] as $path) {
                foreach (File::files($path) as $file) {

                    $filename = $prefix . '-' . $file->getFilenameWithoutExtension();
                    $icons[] = $filename;
                }
            }
        }

        return collect($icons)->filter(fn (string $icon) => str_contains($icon, $this->search));
    }

    public function render(): View
    {
        return view('shopper::livewire.components.forms.icon-picker', [
            'icons' => $this->paginate($this->loadIcons(), $this->perPage),
        ]);
    }
}
