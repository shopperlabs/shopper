<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Attributes;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Shopper\Core\Models\AttributeProduct;

trait Actions
{
    public bool $activated;

    public function saveAction(): Action
    {
        return Action::make('save')
            ->label(__('shopper::layout.forms.actions.save'))
            ->badge()
            ->action(
                fn () => $this->saveData(['attribute_value_id' => $this->value])
            )
            ->visible($this->value !== null);
    }

    public function removeAction(): Action
    {
        return Action::make('remove')
            ->badge()
            ->color('danger')
            ->label(__('shopper::layout.forms.actions.remove'))
            ->action(function (array $arguments): void {
                AttributeProduct::query()->find($arguments['id'])->delete();

                Notification::make()
                    ->title(__('shopper::pages/products.attributes.session.delete'))
                    ->body(__('shopper::pages/products.attributes.session.delete_message'))
                    ->success()
                    ->send();

                $this->reset('model', 'value');

                $this->dispatch('onProductAttributeUpdated');
            })
            ->visible($this->value !== null);
    }

    protected function saveData(array $data): void
    {
        /** @var AttributeProduct $productAttribute */
        $productAttribute = AttributeProduct::query()->updateOrCreate(
            attributes: [
                'product_id' => $this->product->id,
                'attribute_id' => $this->attribute->id,
            ],
            values: $data,
        );

        $this->model = $productAttribute;

        Notification::make()
            ->title(__('shopper::pages/products.attributes.session.added'))
            ->body(__('shopper::pages/products.attributes.session.added_message'))
            ->success()
            ->send();

        $this->dispatch('$refresh')->self();
    }
}
