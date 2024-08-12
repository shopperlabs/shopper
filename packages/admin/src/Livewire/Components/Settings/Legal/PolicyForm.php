<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Legal;

use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Shopper\Core\Models\Legal;

/**
 * @property Form $form
 */
class PolicyForm extends Component implements HasForms
{
    use InteractsWithForms;

    public Legal $legal;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->legal->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Hidden::make('title'),
                Components\Toggle::make('is_enabled')
                    ->label(__('shopper::words.is_enabled'))
                    ->onColor('success'),
                Components\RichEditor::make('content')
                    ->label(__('shopper::forms.label.content'))
                    ->required(),
            ])
            ->statePath('data')
            ->model($this->legal);
    }

    public function store(): void
    {
        Legal::query()->updateOrCreate([
            'slug' => Str::slug($this->form->getState()['title']),
        ], $this->form->getState());

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
