<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Models\Shop\Legal;

trait WithLegalActions
{
    /**
     * Legal id.
     */
    public ?int $legalId = null;

    /**
     * Legal page content.
     */
    public ?string $content = null;

    /**
     * Legal page state to determine if page is enabled.
     */
    public bool $isEnabled = false;

    /**
     * Initialization of values.
     *
     * @param null|Legal $legal
     */
    public function initializeValues($legal)
    {
        $this->legalId = $legal?->id;
        $this->content = $legal?->content;
        $this->isEnabled = $legal ? $legal->is_enabled : false;
    }

    /**
     * Update/Create values on the storage.
     */
    public function storeValues(string $title, $content, bool $isEnabled = false)
    {
        Legal::query()->updateOrCreate(['slug' => str_slug($title)], [
            'title' => $title,
            'slug' => str_slug($title),
            'content' => $content,
            'is_enabled' => $isEnabled,
        ]);
    }
}
