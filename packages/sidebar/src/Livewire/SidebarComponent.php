<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Sidebar\Contracts\Sidebar;
use Shopper\Sidebar\Presentation\SidebarRenderer;

class SidebarComponent extends Component
{
    public string $sidebarClass;

    public string $class = '';

    public bool $collapsible = true;

    public string $view = 'sidebar::livewire.sidebar';

    public function mount(
        string $sidebarClass,
        string $class = '',
        bool $collapsible = true,
        ?string $view = null,
    ): void {
        $this->sidebarClass = $sidebarClass;
        $this->class = $class;
        $this->collapsible = $collapsible;

        if ($view !== null) {
            $this->view = $view;
        }
    }

    #[On('sidebar:refresh')]
    public function refresh(): void
    {
        // This will trigger a re-render of the component
    }

    public function getSidebar(): Sidebar
    {
        return app($this->sidebarClass);
    }

    public function getRenderedSidebar(): ?View
    {
        $renderer = app(SidebarRenderer::class);

        return $renderer->render($this->getSidebar());
    }

    public function render(): View
    {
        return view($this->view, [
            'sidebar' => $this->getSidebar(),
            'renderedSidebar' => $this->getRenderedSidebar(),
        ]);
    }
}
