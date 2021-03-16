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
    public bool $isLocal = false;

    /**
     * Template slug to removed.
     *
     * @var string|null
     */
    public ?string $itemToDelete;

    /**
     * Template name to delete.
     *
     * @var string|null
     */
    public ?string $itemName;

    /**
     * Define Modal to removed Template.
     *
     * @var bool
     */
    public bool $deleteModalConfirmation = false;

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
     * @param  string  $slug
     * @return void
     */
    public function openRemovedModal(string $item, string $slug)
    {
        $this->itemToDelete = $slug;
        $this->itemName = $item;

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
        $this->itemName = null;

        $this->deleteModalConfirmation = false;
    }

    /**
     * Removed Template file.
     *
     * @return void
     */
    public function removedTemplate()
    {
        Mailable::deleteTemplate($this->itemToDelete);

        $this->notify([
            'title' => __('Removed'),
            'message' => __("You have removed the :template", ['template' => $this->itemName])
        ]);

        $this->closeRemovedModal();
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
