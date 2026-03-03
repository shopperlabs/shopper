<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Form\CustomAttributeKeyInput;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Livewire\Components\SlideOverComponent;

class AttributeValues extends SlideOverComponent implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public ?Attribute $attribute = null;

    /** @var Collection<int, AttributeValue> */
    public Collection $values;

    public static function panelMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(int $attributeId): void
    {
        $this->authorize('edit_attributes');

        $this->attribute = Attribute::with('values')->find($attributeId);
        $this->values = $this->attribute->values;
    }

    /**
     * @return array<array-key, \Filament\Schemas\Components\Component>
     */
    public function formSchema(): array
    {
        return [
            CustomAttributeKeyInput::make('key', $this->attribute->type) // @phpstan-ignore-line
                ->label(__('shopper::forms.label.key'))
                ->helperText(__('shopper::modals.attributes.key_description'))
                ->required()
                ->unique(
                    table: AttributeValue::class,
                    column: 'key',
                    ignoreRecord: true,
                    modifyRuleUsing: fn ($rule) => $rule->where('attribute_id', $this->attribute->id),
                ),
            TextInput::make('value')
                ->label(__('shopper::forms.label.value'))
                ->placeholder('My value')
                ->maxLength(75)
                ->required(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AttributeValue::query()
                    ->where('attribute_id', $this->attribute->id)
            )
            ->columns([
                TextColumn::make('key')
                    ->label(__('shopper::forms.label.key')),
                TextColumn::make('id')
                    ->label('Hex')
                    ->formatStateUsing(fn (AttributeValue $record): View => view(
                        'shopper::components.filament.attribute-color-badge',
                        ['key' => $record->key]
                    ))
                    ->visible($this->attribute->type === FieldType::ColorPicker),
                TextColumn::make('value')
                    ->label(__('shopper::forms.label.value')),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->modalHeading(__('shopper::forms.actions.edit'))
                    ->modalWidth(Width::ExtraLarge)
                    ->fillForm(fn (AttributeValue $record): array => [
                        'key' => $record->key,
                        'value' => $record->value,
                    ])
                    ->schema($this->formSchema())
                    ->action(function (array $data, AttributeValue $record): void {
                        $record->update([
                            'key' => mb_strtolower($data['key']),
                            'value' => $data['value'],
                        ]);

                        $this->dispatch('$refresh');
                    }),
                Action::make('delete')
                    ->icon(Untitledui::Trash03)
                    ->color('danger')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->action(fn (AttributeValue $record) => $record->delete()),
            ])
            ->toolbarActions([
                BulkAction::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->color('danger')
                    ->badge()
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),
            ])
            ->headerActions([
                Action::make('add')
                    ->label(__('shopper::forms.actions.add_label', ['label' => __('shopper::forms.label.value')]))
                    ->badge()
                    ->modalHeading(__('shopper::modals.attributes.new_value', ['attribute' => $this->attribute->name]))
                    ->modalWidth(Width::ExtraLarge)
                    ->schema($this->formSchema())
                    ->action(function (array $data): void {
                        $this->attribute->values()->create([
                            'key' => mb_strtolower($data['key']),
                            'value' => $data['value'],
                        ]);

                        $this->dispatch('$refresh');
                    }),
            ])
            ->emptyStateIcon(Untitledui::File02)
            ->emptyStateHeading(__('shopper::words.no_values'));
    }

    #[On('updateValues')]
    public function updateValues(): void
    {
        $this->values = AttributeValue::query()
            ->where('attribute_id', $this->attribute->id)
            ->get();
    }

    public function removeValue(int $id): void
    {
        $this->authorize('edit_attributes');

        AttributeValue::query()->find($id)->delete();

        $this->dispatch('updateValues')->self();

        Notification::make()
            ->title(__('shopper::layout.status.delete'))
            ->body(__('shopper::pages/attributes.notifications.value_removed'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.attribute-values');
    }
}
