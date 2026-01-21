<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Shopper\Core\Enum\Operator;
use Shopper\Core\Enum\Rule;
use Shopper\Core\Models\Contracts\Collection;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property-read Schema $form
 */
class CollectionRules extends SlideOverComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Collection $collection;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function panelMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(): void
    {
        $this->form->fill($this->collection->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Radio::make('match_conditions')
                    ->label(__('shopper::pages/collections.conditions.products_match'))
                    ->inline()
                    ->options([
                        'all' => __('shopper::pages/collections.conditions.all'),
                        'any' => __('shopper::pages/collections.conditions.any'),
                    ]),
                Repeater::make('rules')
                    ->relationship()
                    ->label(__('shopper::pages/collections.conditions.title'))
                    ->addActionLabel(__('shopper::pages/collections.conditions.add'))
                    ->schema([
                        Select::make('rule')
                            ->label(__('shopper::pages/collections.conditions.choose_rule'))
                            ->options(Rule::class)
                            ->required(),
                        Select::make('operator')
                            ->label(__('shopper::pages/collections.conditions.select_operator'))
                            ->options(Operator::class)
                            ->required(),
                        TextInput::make('value')
                            ->label(__('shopper::forms.label.value'))
                            ->required(),
                    ])
                    ->columns(3)
                    ->defaultItems(1),
            ])
            ->statePath('data')
            ->model($this->collection); // @phpstan-ignore-line
    }

    public function store(): void
    {
        $this->collection->update($this->form->getState());

        $this->closePanel();

        Notification::make()
            ->title(__('shopper::pages/collections.conditions.update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.collection-rules');
    }
}
