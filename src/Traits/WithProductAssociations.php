<?php

namespace Shopper\Framework\Traits;

trait WithProductAssociations
{
    public array $category_ids = [];
    public array $collection_ids = [];
    public array $associateCategories = [];
    public array $associateCollections = [];

    public function updatedCategoryIds($choices)
    {
        if (! in_array($choices['value'], $this->associateCategories)) {
            array_push($this->associateCategories, $choices['value']);
        } else {
            $key = array_search($choices['value'], $this->associateCategories);
            unset($this->associateCategories[$key]);
        }
    }

    public function updatedCollectionIds($choices)
    {
        if (! in_array($choices['value'], $this->associateCollections)) {
            array_push($this->associateCollections, $choices['value']);
        } else {
            $key = array_search($choices['value'], $this->associateCollections);
            unset($this->associateCollections[$key]);
        }
    }
}
