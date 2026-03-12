<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Shopper\Core\Enum\Operator;
use Shopper\Core\Enum\Rule;
use Shopper\Core\Jobs\SyncCollectionProductsJob;
use Shopper\Core\Models\Contracts\Collection;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class CollectionRules extends SlideOverComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Collection $collection;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function panelMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(): void
    {
        $this->authorize('edit_collections');

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
                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                        $rule = Rule::tryFrom($data['rule'] ?? '');

                        if ($rule?->isPrice() && isset($data['value'])) {
                            $data['value'] = (string) ((int) $data['value'] / 100);
                        } elseif ($rule?->isBoolean() && isset($data['value'])) {
                            $data['boolean_value'] = $data['value'];
                        } elseif ($rule?->isDate() && isset($data['value'])) {
                            $data['date_value'] = $data['value'];
                        }

                        return $data;
                    })
                    ->mutateRelationshipDataBeforeCreateUsing(fn (array $data): array => $this->mutateRuleData($data))
                    ->mutateRelationshipDataBeforeSaveUsing(fn (array $data): array => $this->mutateRuleData($data))
                    ->schema([
                        Select::make('rule')
                            ->label(__('shopper::pages/collections.conditions.choose_rule'))
                            ->options(Rule::options())
                            ->live()
                            ->afterStateUpdated(function (Select $component): void {
                                $component->getContainer()->getComponent('operator')?->state(null);
                                $component->getContainer()->getComponent('value')?->state(null);
                            })
                            ->required(),
                        Select::make('operator')
                            ->key('operator')
                            ->label(__('shopper::pages/collections.conditions.select_operator'))
                            ->options(fn (Get $get): array => collect(Rule::tryFrom($get('rule') ?? '')?->allowedOperators() ?? [])
                                ->mapWithKeys(fn (Operator $op): array => [$op->value => $op->getLabel()])
                                ->all())
                            ->required(),
                        DatePicker::make('date_value')
                            ->label(__('shopper::forms.label.value'))
                            ->visible(fn (Get $get): bool => Rule::tryFrom($get('rule') ?? '')?->isDate() ?? false)
                            ->native(false),
                        Select::make('boolean_value')
                            ->label(__('shopper::forms.label.value'))
                            ->options([
                                '1' => __('shopper::forms.label.yes'),
                                '0' => __('shopper::forms.label.no'),
                            ])
                            ->visible(fn (Get $get): bool => Rule::tryFrom($get('rule') ?? '')?->isBoolean() ?? false),
                        TextInput::make('value')
                            ->label(__('shopper::forms.label.value'))
                            ->numeric(fn (Get $get): bool => Rule::tryFrom($get('rule') ?? '')?->isNumeric() ?? false)
                            ->visible(fn (Get $get): bool => ! (Rule::tryFrom($get('rule') ?? '')?->isDate() ?? false) && ! (Rule::tryFrom($get('rule') ?? '')?->isBoolean() ?? false)),
                    ])
                    ->columns(3)
                    ->defaultItems(1),
            ])
            ->statePath('data')
            ->model($this->collection); // @phpstan-ignore-line
    }

    public function store(): void
    {
        $this->authorize('edit_collections');

        $this->collection->update($this->form->getState());
        $this->form->model($this->collection)->saveRelationships(); // @phpstan-ignore-line

        if ($this->collection->isAutomatic()) {
            SyncCollectionProductsJob::dispatch($this->collection);
        }

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

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function mutateRuleData(array $data): array
    {
        $rule = $data['rule'] instanceof Rule
            ? $data['rule']
            : Rule::tryFrom((string) ($data['rule'] ?? ''));

        if ($rule?->isDate()) {
            $data['value'] = $data['date_value'] ?? null;
        } elseif ($rule?->isBoolean()) {
            $data['value'] = $data['boolean_value'] ?? null;
        }

        unset($data['date_value'], $data['boolean_value']);

        if (! isset($data['value']) || $data['value'] === '') {
            throw ValidationException::withMessages([
                'value' => __('validation.required', ['attribute' => __('shopper::forms.label.value')]),
            ]);
        }

        if ($rule?->isPrice()) {
            $data['value'] = (string) ((int) ((float) $data['value'] * 100));
        }

        return $data;
    }
}
