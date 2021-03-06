<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Livewire\Component;
use Shopper\Framework\Services\Mailable;

class Templates extends Component
{
    /**
     * Define if the we can create template in production.
     *
     * @var bool
     */
    public $isLocal = false;

    /**
     * Template name to removed.
     *
     * @var string
     */
    public $itemToDelete;

    /**
     * Define Modal to removed Template.
     *
     * @var bool
     */
    public $deleteModalConfirmation = false;

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
     * Removed Template file.
     *
     * @return void
     */
    public function removedTemplate()
    {

    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.mails.templates.browse', [
            'templates' => Mailable::getTemplates()
        ]);
    }
}
