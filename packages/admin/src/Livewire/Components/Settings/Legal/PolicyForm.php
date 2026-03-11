<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Legal;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Legal;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property Schema $form
 */
class PolicyForm extends Component implements HasActions, HasForms
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithForms;

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
                Hidden::make('title')
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
        $this->legal->update(array_merge($this->form->getState(), [
            'slug' => $this->legal->slug,
        ]));

        Notification::make()
            ->title($this->legal->title)
            ->body(__('shopper::notifications.legal'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.legal._form');
    }
}
