<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Models\Shop\Legal;

trait WithLegalAttributes
{
    /**
     * Legal id.
     *
     * @var int
     */
    public $legalId;

    /**
     * Legal page content.
     *
     * @var string
     */
    public $content;

    /**
     * Legal page state to determine if page is enabled.
     *
     * @var bool
     */
    public $isEnabled = false;

    /**
     * Initialization of values.
     *
     * @param  Legal|null  $legal
     * @return void
     */
    public function initializeValues($legal)
    {
        $this->legalId = $legal ? $legal->id : null;
        $this->content = $legal ? $legal->content : null;
        $this->isEnabled = $legal ? $legal->is_enabled : false;
    }
}
