<?php

declare(strict_types=1);

namespace Shopper\Theme;

use Illuminate\Support\Str;

final readonly class Theme
{
    public function __construct(
        public string $id,
        public string $name,
        public string $author = 'Shopper',
        public bool $hasDark = true,
        public ?string $css = null,
    ) {}

    /**
     * @param  array{name?: string, author?: string, has_dark?: bool, css?: string}  $attributes
     */
    public static function fromArray(string $id, array $attributes): self
    {
        return new self(
            id: $id,
            name: $attributes['name'] ?? Str::headline($id),
            author: $attributes['author'] ?? 'Shopper',
            hasDark: $attributes['has_dark'] ?? true,
            css: $attributes['css'] ?? null,
        );
    }

    /**
     * @return array{id: string, name: string, author: string, has_dark: bool, css: ?string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author,
            'has_dark' => $this->hasDark,
            'css' => $this->css,
        ];
    }
}
