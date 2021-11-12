<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Models\Shop\Legal;

trait WithLegalActions
{
    public ?int $legalId = null;
    public ?string $content = null;
    public bool $isEnabled = false;

    public function initializeValues($legal)
    {
        $this->legalId = $legal?->id;
        $this->content = $legal?->content;
        $this->isEnabled = $legal ? $legal->is_enabled : false;
    }

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
