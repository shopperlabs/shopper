<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Livewire\Component;
use Shopper\Framework\Services\Mailable;

class AddTemplate extends Component
{
    /**
     * Current skeleton name.
     *
     * @var string
     */
    public $currentSkeleton;

    /**
     * SubSkeletons depend of the current skeleton selected.
     *
     * @var array
     */
    public $subSkeletons = [];

    /**
     * Define the status of the skeleton modal.
     *
     * @var bool
     */
    public $skeletonDisplayModal = false;

    /**
     * Load selected subSkeletons of the current
     * skeleton on a modal.
     *
     * @param  string  $name
     * @param  array  $subSkeletons
     * @return void
     */
    public function loadSubSkeletons(string $name, array $subSkeletons = [])
    {
        $this->currentSkeleton = $name;

        $this->subSkeletons = $subSkeletons;

        $this->skeletonDisplayModal = true;
    }

    /**
     * Close subSkeletons modal.
     *
     * @return void
     */
    public function closeModal()
    {
        $this->emit('modal-close');

        $this->skeletonDisplayModal = false;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.mails.templates.add-template', [
            'skeletons' => Mailable::getTemplateSkeletons()
        ]);
    }
}
