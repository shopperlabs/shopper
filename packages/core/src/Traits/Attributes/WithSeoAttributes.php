<?php

declare(strict_types=1);

namespace Shopper\Core\Traits\Attributes;

trait WithSeoAttributes
{
    public bool $updateSeo = false;

    public ?string $seoTitle = null;

    public ?string $seoDescription = null;

    public function isUpdate(): bool
    {
        return false;
    }

    public function updateSeo(): void
    {
        $this->updateSeo = true;
        $this->seoTitle = $this->isUpdate()
            ? $this->seoTitle
            : $this->{$this->seoAttributes['name']};
        $this->seoDescription = $this->isUpdate()
            ? str_limit(strip_tags(nl2br($this->seoDescription ?? '')), 157)
            : str_limit(strip_tags(nl2br($this->{$this->seoAttributes['description']} ?? '')), 157);
    }
}
