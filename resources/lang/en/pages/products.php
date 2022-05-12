<?php

return [

    'title' => 'Manage Catalog',
    'content' => 'Get closer to your first sale by adding and manage products.',

    'cost_per_items_help_text' => 'Customers won’t see this.',
    'safety_security_help_text' => 'The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.',
    'quantity_inventory' => 'Quantity Inventory',
    'manage_inventories' => 'Manage Inventories',
    'inventory_name' => 'Inventory name',
    'product_can_returned' => 'This product can be returned',
    'product_can_returned_help_text' => 'Users have the option of returning this product if there is a problem or dissatisfaction.',
    'product_shipped' => 'This product will be shipped',
    'product_shipped_help_text' => 'Reassure to fill in the information concerning the shipment of the product.',
    'weight_dimension_help_text' => 'Used to calculate shipping charges during checkout and to label prices during order processing.',
    'status' => 'Product status',
    'visible_help_text' => 'This product will be hidden from all sales channels.',
    'availability_description' => 'Specify a publication date so that your product are scheduled on your store.',
    'product_associations' => 'Product associations',
    'product_categories' => 'Product categories',
    'no_category' => 'No Categories',
    'no_category_text' => 'Get started by creating a new category.',
    'new_category' => 'New category',
    'related_products' => 'Related Products',
    'quantity_available' => 'Quantity Available',
    'current_qty_inventory' => 'Current quantity on this inventory',

    'modals' => [
        'title' => 'Delete this :item',
        'message' => 'Are you sure you want to delete this product? All information associated with this product will be deleted.',

        'variants' => [
            'title' => 'Stock management for this variant',
            'select' => 'Select inventory',
        ],
    ],

    'variants' => [
        'title' => 'Products variations',
        'description' => 'All variations of your product. The variations can each have their stock and price.',
        'add' => 'Add variant',
        'empty' => 'No variant found',
        'search_label' => 'Search variant',
        'search_placeholder' => 'Search product variant',
        'variant_information' => 'Variant information',

        'actions' => [
            'update' => 'Update variant',
            'update_stock' => 'Update stock',
            'manage_inventory' => 'Manage Inventories',
        ],

        'modal' => [
            'title' => 'About the variation',
            'description' => 'Variant name and price. If the price is empty, the price of the product will be applied.',
        ],
    ],

    'reviews' => [
        'title' => 'Customers reviews',
        'description' => 'This is where you will see the reviews of your customers and the ratings given to your products.',
        'view' => 'Reviews for :product',
        'published' => 'Published',
        'pending' => 'Pending',
        'approved' => 'Approved Review',
        'approved_status' => 'Approved status',
        'approved_message' => 'Review approved status updated!',

        'subtitle' => 'Review for this product.',
        'reviewer' => 'Reviewer',
        'review' => 'Review',
        'review_content' => 'Content',
        'status' => 'Status',
        'rating' => 'Rating',

        'modal' => [
            'title' => 'Delete Review',
            'description' => 'Are you sure you want to delete this review? This review will can\'t be recover no more.',
            'success_message' => 'Review removed successfully!',
        ],
    ],

    'attributes' => [
        'title' => 'Product Attributes',
        'description' => 'All the attributes associated with this product.',
        'add' => 'Add attribute',
        'empty_values' => 'No attributes',

        'session' => [
            'delete' => 'Attribute removed',
            'delete_message' => 'You have successfully removed this attribute to product!',
            'delete_value' => 'Attribute value removed',
            'delete_value_message' => 'You have successfully removed the value of this attribute!',
            'added' => 'Attribute Added',
            'added_message' => 'You have successfully added an attribute to this product!',
        ],

        'modals' => [
            'title' => 'Add new attribute with value',
            'input_placeholder' => 'Select the attribute to add',
        ],
    ],

    'inventory' => [
        'title' => 'Inventory attributes',
        'description' => 'Fields related to stock management in your store.',
        'stock_title' => 'Stock management',
        'stock_description' => 'Stock management in your different inventories.',
        'empty' => 'No adjustments made to inventory.',
        'movement' => 'Quantity Movement',
        'initial' => 'Initial inventory',
    ],

    'seo' => [
        'title' => 'Search Engine Optimization',
        'description' => 'Improve your ranking and how your product page will appear in search engines results.',
        'sub_description' => 'Here is a preview of your search engine result, play with it!',
    ],

    'shipping' => [
        'description' => 'Product information about return product or define if product can be shipping to the customer.',
        'package_dimension' => 'Package dimension',
        'package_dimension_description' => 'Charge additional shipping costs based on packet dimensions covered here.',
    ],

    'related' => [
        'title' => 'Similar Products',
        'description' => 'All products that can be identified as similar or complementary to your product.',
    ],

    'notifications' => [
        'create' => 'Product successfully added!',
        'update' => 'Product successfully updated!',
        'stock_update' => 'Product Stock successfully updated!',
        'seo_update' => 'Product SEO successfully updated!',
        'shipping_update' => 'Product shipping successfully updated!',
        'variation_create' => 'Product variation successfully added!',
        'variation_delete' => 'The variation has successfully removed!',
        'variation_update' => 'Variant successfully updated!',
    ],

];
