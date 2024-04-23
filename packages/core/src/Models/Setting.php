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
            'name' => __('shopper::forms.label.store_name'),
            'legal_name' => __('shopper::forms.label.legal_name'),
            'email' => __('shopper::forms.label.email'),
            'logo' => __('shopper::forms.label.logo'),
            'cover' => __('shopper::forms.label.cover_photo'),
            'about' => __('shopper::forms.label.about'),
            'country_id' => __('shopper::forms.label.country'),
            'default_currency_id' => __('shopper::forms.label.currency'),
            'currencies' => __('shopper::forms.label.currencies'),
            'street_address' => __('shopper::forms.label.street_address'),
            'postal_code' => __('shopper::forms.label.postal_code'),
            'city' => __('shopper::forms.label.city'),
            'phone_number' => __('shopper::forms.label.phone_number'),
            'longitude' => __('shopper::forms.label.longitude'),
            'latitude' => __('shopper::forms.label.latitude'),
            'facebook_link' => __('shopper::words.socials.facebook'),
            'instagram_link' => __('shopper::words.socials.twitter'),
            'twitter_link' => __('shopper::words.socials.instagram'),
            'google_analytics_add_js' => __('shopper::forms.label.ga_additional_script'),
        ][$key];
    }
}
