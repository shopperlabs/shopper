<?php

namespace Shopper\Framework\Traits;

trait WithSorting
{
    /**
     * Sort column item.
     *
     * @var string
     */
    public $sortBy;

    /**
     * Sort direction
     *
     * @var string
     */
    public $sortDirection = 'desc';

    /**
     * Sort by field on the table view.
     *
     * @param  string  $field
     * @return void
     */
    public function sortBy(string $field)
    {
        $this->sortBy = $field;

        $this->sortDirection = $this->sortBy === $field
            ? $this->reverseSort()
            : 'asc';
    }

    /**
     * Reverse table sort.
     *
     * @return string
     */
    public function reverseSort()
    {
        return $this->sortDirection === 'asc'
            ? 'desc'
            : 'asc';
    }
}
