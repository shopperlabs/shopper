<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Shopper\Components\Section;
use Shopper\Core\Models\Contracts\Supplier;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class SupplierForm extends SlideOverComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Supplier $supplier;

    /** @var array<array-key, mixed>|null */
    public ?array $data = [];

    public function mount(?Supplier $supplier = null): void
    {
        $this->supplier = $supplier ?? resolve(Supplier::class)::query()->newModelInstance();

        $this->form->fill($this->supplier->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::words.general'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('shopper::forms.label.name'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Set $set): void {
                                $set('slug', Str::slug($state));
                            }),
                        Hidden::make('slug'),
                        TextInput::make('email')
                            ->label(__('shopper::forms.label.email'))
                            ->email(),
                        TextInput::make('phone')
                            ->label(__('shopper::forms.label.phone_number'))
                            ->tel(),
                        TextInput::make('contact_name')
                            ->label(__('shopper::pages/suppliers.contact')),
                        TextInput::make('website')
                            ->label(__('shopper::forms.label.website'))
                            ->placeholder('https://example.com')
                            ->url(),
                        Toggle::make('is_enabled')
                            ->label(__('shopper::forms.label.visibility'))
                            ->helperText(__('shopper::pages/suppliers.visibility')),
                    ]),
                Section::make(__('shopper::words.description'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        RichEditor::make('description')
                            ->label(__('shopper::forms.label.description'))
                            ->toolbarButtons([
                                ['bold', 'italic', 'link', 'strike', 'underline'],
                                ['bulletList', 'orderedList'],
                                ['undo', 'redo'],
                            ]),
                        Textarea::make('notes')
                            ->label(__('shopper::forms.label.notes'))
                            ->rows(3),
                    ]),
                Section::make(__('Metadata'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        KeyValue::make('metadata')
                            ->hiddenLabel()
                            ->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->supplier); // @phpstan-ignore-line
    }

    public function save(): void
    {
        if ($this->supplier->id) {
            $this->authorize('edit_suppliers', $this->supplier);

            $this->supplier->update($this->form->getState());
        } else {
            $this->authorize('add_suppliers');

            resolve(Supplier::class)::query()->create($this->form->getState());
        }

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => __('shopper::pages/suppliers.single')]))
            ->success()
            ->send();

        $this->redirectRoute(
            name: 'shopper.suppliers.index',
            navigate: true,
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.supplier-form');
    }
}
