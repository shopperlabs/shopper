<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Models\Shop\Legal;

trait WithLegalActions
{
    /**
     * Legal id.
     *
     * @var int|null
     */
    public ?int $legalId = null;

    /**
     * Legal page content.
     *
     * @var string|null
     */
    public ?string $content = null;

    /**
     * Legal page state to determine if page is enabled.
     *
     * @var bool
     */
    public bool $isEnabled = false;

    /**
     * Initialization of values.
     *
     * @param  Legal|null  $legal
     *
     * @return void
     */
    public function initializeValues($legal)
    {
        $this->legalId = $legal ? $legal->id : null;
        $this->content = $legal ? $legal->content : null;
        $this->isEnabled = $legal ? $legal->is_enabled : false;
    }

    /**
     * Update/Create values on the storage.
     *
     * @param  string  $title
     * @param  mixed  $content
     * @param  bool  $isEnabled
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
