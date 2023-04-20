<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Services\Mailable;
use WireUi\Traits\Actions;

class CreateMailable extends ModalComponent
{
    use Actions;

    public string $name = '';

    public ?string $markdownView = null;

    public bool $isMarkdown = false;

    public bool $isForce = false;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function generateMailable(): void
    {
        $this->validate(['name' => 'required']);

        if ($this->isMarkdown) {
            $this->validate(['markdownView' => 'required']);
        }

        $name = ucwords(Str::camel(preg_replace('/\s+/', '_', $this->name)));

        if (! Mailable::getMailable('name', $name)->isEmpty() && ! $this->isForce) {
            $this->addError(
                'name',
                __('This mailable name already exists. Name should be unique! to override it, enable "force" option.')
            );

            return;
        }

        if (mb_strtolower($name) === 'mailable') {
            $this->addError('name', __('You cannot use this name'));

            return;
        }

        $params = collect(['name' => $name]);

        if ($this->isMarkdown) {
            $params->put('--markdown', $this->isMarkdown);
        }

        if ($this->isForce) {
            $params->put('--force', true);
        }

        $exitCode = Artisan::call('make:mail', $params->all());

        if ($exitCode > -1) {
            $this->notification()->success(__('Mailable Created'), __('You Mailable class has been created under the app/Mail folder on your project!'));

            $this->emit('onMailableAction');

            $this->closeModal();
        }
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.create-mailable');
    }
}
