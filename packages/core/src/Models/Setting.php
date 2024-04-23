<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $key
 * @property string $display_name
 * @property mixed $value
 */
class Setting extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'locked',
    ];

    protected $casts = [
        'value' => 'array',
        'locked' => 'boolean',
    ];

    public function getTable(): string
    {
        return shopper_table('settings');
    }

    public static function lockedAttributesDisplayName(string $key): string
    {
        return [
            'name' => __('shopper::layout.forms.label.store_name'),
            'legal_name' => __('shopper::layout.forms.label.legal_name'),
            'email' => __('shopper::layout.forms.label.email'),
            'logo' => __('shopper::layout.forms.label.logo'),
            'cover' => __('shopper::layout.forms.label.cover_photo'),
            'about' => __('shopper::layout.forms.label.about'),
            'country_id' => __('shopper::layout.forms.label.country'),
            'default_currency_id' => __('shopper::layout.forms.label.currency'),
            'currencies' => __('shopper::layout.forms.label.currencies'),
            'street_address' => __('shopper::layout.forms.label.street_address'),
            'postal_code' => __('shopper::layout.forms.label.postal_code'),
            'city' => __('shopper::layout.forms.label.city'),
            'phone_number' => __('shopper::layout.forms.label.phone_number'),
            'longitude' => __('shopper::layout.forms.label.longitude'),
            'latitude' => __('shopper::layout.forms.label.latitude'),
            'facebook_link' => __('shopper::words.socials.facebook'),
            'instagram_link' => __('shopper::words.socials.twitter'),
            'twitter_link' => __('shopper::words.socials.instagram'),
            'google_analytics_add_js' => __('shopper::layout.forms.label.ga_additional_script'),
        ][$key];
    }
}
