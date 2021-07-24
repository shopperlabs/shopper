<?php

namespace Shopper\Framework\Traits;

trait WithSeoAttributes
{
    /**
     * Update SEO elements.
     *
     * @var bool
     */
    public $updateSeo = false;

    /**
     * Map default SEO title and Description to a Model.
     *
     * @var array<string>
     */
    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    /**
     * Seo Display title.
     *
     * @var string
     */
    public $seoTitle;

    /**
     * Seo description.
     *
     * @var string
     */
    public $seoDescription;

    /**
     * Define is the current action is create or update.
     *
     * @return false
     */
    public function isUpdate()
    {
        return false;
    }

    /**
     * Display the block to update SEO values.
     */
    public function updateSeo()
    {
        $this->updateSeo = true;
        $this->seoTitle = $this->isUpdate()
            ? $this->seoTitle
            : $this->{$this->seoAttributes['name']};
        $this->seoDescription = $this->isUpdate()
            ? str_limit(strip_tags(nl2br($this->seoDescription)), 157)
            : str_limit(strip_tags(nl2br($this->{$this->seoAttributes['description']})), 157);
    }
}
