<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Models\ProductImport;

class ImportProgress extends Component
{
    public ?int $watchedImportId = null;

    #[On('products.import.started')]
    public function refresh(): void {}

    public function render(): View
    {
        /** @var ?ProductImport $import */
        $import = ProductImport::query()
            ->whereIn('status', [ImportStatus::Pending, ImportStatus::Processing])
            ->latest()
            ->first();

        if ($import !== null) {
            $this->watchedImportId = $import->id;
        } elseif ($this->watchedImportId !== null) {
            $this->watchedImportId = null;

            $this->dispatch('products.import.finished');
        }

        return view('shopper::livewire.components.products.import-progress', [
            'import' => $import,
        ]);
    }
}
