<?php

declare(strict_types=1);

return [

    'menu' => 'Reviews',
    'single' => 'review',
    'title' => 'Customer Reviews',
    'description' => 'See what customers are saying about your products.',

    'stats' => [
        'average' => 'Average rating',
        'based_on' => 'Based on :count reviews',
        'total' => 'Total reviews',
        'last_30_days' => 'in the last 30 days',
        'no_recent' => 'No new reviews in the last 30 days',
        'no_data' => 'No reviews yet',
        'five_star' => '5-star reviews',
        'excellent' => 'Excellent ratings',
        'pending' => 'Pending moderation',
        'pending_description' => 'Needs your input',
        'pending_empty' => 'Inbox is clear',
    ],

    'breakdown' => [
        'title' => 'Ratings breakdown',
        'description' => 'How customers rate you',
    ],

    'recommended' => [
        'title' => 'Recommended',
        'description' => ':percent% of customers recommend',
        'empty' => 'No recommendations yet',
    ],

    'tabs' => [
        'all' => 'All',
        'pending' => 'Pending',
        'approved' => 'Approved',
    ],

    'actions' => [
        'approve' => 'Approve',
        'reject' => 'Reject',
        'mark_as_spam' => 'Mark as spam',
        'approved_message' => 'Review approved.',
        'rejected_message' => 'Review rejected.',
        'spam_message' => 'Review flagged as spam.',
        'spam_confirmation' => 'Flagging this review as spam will hide it from your customers. You can revert this from the moderation queue.',
    ],

];
