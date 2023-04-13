<?php

declare(strict_types=1);

namespace Shopper\Framework\Traits;

use Shopper\Framework\Models\Shop\Legal;

trait WithLegalActions
{
    public ?int $legalId = null;

    public ?string $content = null;

    public bool $isEnabled = false;

    public function mount(): void
    {
        $legal = Legal::query()->where('slug', str_slug($this->title))->first();

        $this->initializeValues($legal);
    }

    public function initializeValues(?Legal $legal): void
    {
        $this->legalId = $legal?->id;
        $this->content = $legal?->content;
        $this->isEnabled = $legal ? $legal->is_enabled : false;
    }

    public function storeValues(string $title, string $content, bool $isEnabled = false): void
    {
        Legal::query()->updateOrCreate(['slug' => str_slug($title)], [
            'title' => $title,
            'slug' => $title,
            'content' => $content,
            'is_enabled' => $isEnabled,
        ]);
    }
}
