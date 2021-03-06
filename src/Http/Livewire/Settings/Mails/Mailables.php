<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Livewire\Component;
use Shopper\Framework\Services\Mailable;

class Mailables extends Component
{
    /**
     * Modal confirmation action.
     *
     * @var bool
     */
    public $createModalConfirmation = false;

    /**
     * Define Modal to removed Mailable class.
     *
     * @var bool
     */
    public $deleteModalConfirmation = false;

    /**
     * Mailable class to generate.
     *
     * @var string
     */
    public $name;

    /**
     * Markdown view.
     *
     * @var string
     */
    public $markdownView;

    /**
     * Define if the mailable class is markdown.
     *
     * @var bool
     */
    public $isMarkdown = false;

    /**
     * Force option to erase mailable class.
     *
     * @var bool
     */
    public $isForce = false;

    /**
     * Define if the we can create mailable in production.
     *
     * @var bool
     */
    public $isLocal = false;

    /**
     * Mailable class name to removed to the Mail folder.
     *
     * @var string
     */
    public $itemToDelete;

    /**
     * Component Mount instance.
     *
     * @return void
     */
    public function mount()
    {
        if (in_array(app()->environment(), config('shopper.mails.allowed_environments'))) {
            $this->isLocal = true;
        }
    }

    /**
     * Launch creation mailable modal.
     *
     * @return void
     */
    public function openModal()
    {
        $this->createModalConfirmation = true;
    }

    /**
     * Launch removed modal.
     *
     * @param  string  $item
     * @return void
     */
    public function openRemovedModal(string $item)
    {
        $this->itemToDelete = $item;

        $this->deleteModalConfirmation = true;
    }

    /**
     * Close creation mailable modal.
     *
     * @return void
     */
    public function closeModal()
    {
        $this->createModalConfirmation = false;

        $this->name = '';
        $this->isMarkdown = false;
        $this->isForce = false;
        $this->markdownView = '';

        $this->resetErrorBag();
    }

    /**
     * Close Removed Modal.
     *
     * @return void
     */
    public function closeRemovedModal()
    {
        $this->itemToDelete = null;

        $this->deleteModalConfirmation = false;
    }

    /**
     * Removed Mailable class.
     *
     * @return void
     */
    public function removedMailable()
    {
        $mailableFile = config('shopper.mails.mailables_dir').$this->itemToDelete.'.php';

        if (file_exists($mailableFile)) {
            unlink($mailableFile);

            $this->notify([
                'title' => __('Removed'),
                'message' => __("You have removed the :class", ['class' => $this->itemToDelete])
            ]);
        } else {
            session()->flash('error', __("This file don't exist."));
        }

        $this->closeRemovedModal();
    }

    /**
     * Generate Mailable class to the app/Mail folder.
     *
     * @return void
     */
    public function generateMailable()
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

        if (strtolower($name) === 'mailable') {
            $this->addError(
                'name',
                __('You cannot use this name')
            );
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
            $this->notify([
                'title' => __('Mailable Created'),
                'message' => __('You Mailable class has been created under the app/Mail folder on your project.')
            ]);

            $this->closeModal();
        }
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.mails.mailables', [
            'mailables' => (null !== $mailables = Mailable::getMailables())
                ? $mailables->sortBy('name')
                : collect([])
        ]);
    }
}
