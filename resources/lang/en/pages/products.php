<?php

return [

    'reviews' => [
        'title' => 'Customers reviews',
        'description' => 'This is where you will see the reviews of your customers and the ratings given to your products.',
        'view' => 'Reviews for :product',
        'published' => 'Published',
        'pending' => 'Pending',
        'approved' => 'Approved Review',
        'approved_status' => 'Approved status',

        'subtitle' => 'Review for this product.',
        'reviewer' => 'Reviewer',
        'review' => 'Review',
        'review_content' => 'Content',
        'status' => 'Status',
        'rating' => 'Rating',
        'remove' => 'Are you sure you want to delete this review? This review will can\'t be recover no more.',
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

];
