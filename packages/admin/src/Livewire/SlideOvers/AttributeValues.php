<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Shopper\Components\Form\CustomAttributeKeyInput;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Livewire\Components\SlideOverComponent;

class AttributeValues extends SlideOverComponent implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public ?Attribute $attribute = null;

    public Collection $values;

    public function mount(int $attributeId): void
    {
        $this->attribute = Attribute::with('values')->find($attributeId);
        $this->values = $this->attribute->values;
    }

    public static function panelMaxWidth(): string
    {
        return '2xl';
    }

    public function formSchema(): array
    {
        return [
            CustomAttributeKeyInput::make('key', $this->attribute->type)
                ->label(__('shopper::layout.forms.label.key'))
                ->helperText(__('shopper::modals.attributes.key_description'))
                ->required()
                ->unique(table: AttributeValue::class, column: 'key', ignoreRecord: true),

            Forms\Components\TextInput::make('value')
                ->label(__('shopper::layout.forms.label.value'))
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
                Tables\Columns\TextColumn::make('key')
                    ->label(__('shopper::layout.forms.label.key')),

                Tables\Columns\TextColumn::make('id')
                    ->label('Hex')
                    ->formatStateUsing(fn (AttributeValue $record): View => view(
                        'shopper::components.filament.attribute-color-badge',
                        ['key' => $record->key]
                    ))
                    ->visible($this->attribute->type === FieldType::COLORPICKER),

                Tables\Columns\TextColumn::make('value')
                    ->label(__('shopper::layout.forms.label.value')),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->icon('untitledui-edit-04')
                    ->color('gray')
                    ->iconButton()
                    ->modal()
                    ->modalHeading(__('shopper::layout.forms.actions.edit'))
                    ->modalWidth(MaxWidth::ExtraLarge)
                    ->fillForm(fn (AttributeValue $record): array => [
                        'key' => $record->key,
                        'value' => $record->value,
                    ])
                    ->form($this->formSchema())
                    ->action(function (array $data, AttributeValue $record) {
                        $record->update([
                            'key' => mb_strtolower($data['key']),
                            'value' =>$data['value'],
                        ]);

                        $this->dispatch('$refresh');
                    }),

                Tables\Actions\Action::make('delete')
                    ->icon('untitledui-trash-03')
                    ->color('danger')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->action(fn (AttributeValue $record) => $record->delete()),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('delete')
                    ->label(__('shopper::layout.forms.actions.delete'))
                    ->icon('untitledui-trash-03')
                    ->color('danger')
                    ->badge()
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete())
            ])
            ->headerActions([
                Tables\Actions\Action::make('add')
                    ->label(__('shopper::words.actions_label.add_new', ['name' => __('shopper::layout.forms.label.value')]))
                    ->badge()
                    ->modal()
                    ->modalHeading(__('shopper::modals.attributes.new_value', ['attribute' => $this->attribute->name]))
                    ->modalWidth(MaxWidth::ExtraLarge)
                    ->form($this->formSchema())
                    ->action(function (array $data) {
                        $this->attribute->values()->create([
                            'key' => mb_strtolower($data['key']),
                            'value' => $data['value'],
                        ]);

                        $this->dispatch('$refresh');
                    })
            ])
            ->emptyStateIcon('untitledui-file-02')
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
