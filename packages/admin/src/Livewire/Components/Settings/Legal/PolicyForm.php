<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Legal;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Shopper\Core\Models\Legal;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 * @property-read string $heading
 * @property-read string $description
 */
class PolicyForm extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /**
     * @var array<string, string>
     */
    public const array SLUG_TRANSLATION_KEYS = [
        'terms' => 'terms_of_use',
    ];

    #[Locked]
    public Legal $legal;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->legal->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('shopper::forms.label.title'))
                    ->required()
                    ->maxLength(150)
                    ->unique(ignoreRecord: true),
                Toggle::make('is_enabled')
                    ->label(__('shopper::words.is_enabled'))
                    ->onColor('success'),
                RichEditor::make('content')
                    ->label(__('shopper::forms.label.content'))
                    ->id($this->legal->slug)
                    ->required(),
            ])
            ->statePath('data')
            ->model($this->legal);
    }

    public function store(): void
    {
        $this->authorize('system.settings');

        $this->legal->update(array_merge($this->form->getState(), [
            'slug' => $this->legal->slug,
        ]));

        Notification::make()
            ->title($this->legal->title)
            ->body(__('shopper::notifications.legal'))
            ->success()
            ->send();
    }

    #[Computed]
    public function heading(): string
    {
        $key = self::SLUG_TRANSLATION_KEYS[$this->legal->slug] ?? $this->legal->slug;

        return __("shopper::pages/settings/global.legal.{$key}");
    }

    #[Computed]
    public function description(): string
    {
        return __('shopper::pages/settings/global.legal.summary', ['policy' => $this->heading]);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.legal.form');
    }
}
