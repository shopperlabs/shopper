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
final class Setting extends Model
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
        return shopper_table('system_settings');
    }

    public static function lockedAttributesDisplayName(string $key): string
    {
        return [
            'shop_name' => __('shopper::layout.forms.label.store_name'),
            'shop_legal_name' => __('shopper::layout.forms.label.legal_name'),
            'shop_email' => __('shopper::layout.forms.label.email'),
            'shop_logo' => __('shopper::layout.forms.label.logo'),
            'shop_cover' => __('shopper::layout.forms.label.cover_photo'),
            'shop_about' => __('shopper::layout.forms.label.about'),
            'shop_country_id' => __('shopper::layout.forms.label.country'),
            'shop_currency_id' => __('shopper::layout.forms.label.currency'),
            'shop_street_address' => __('shopper::layout.forms.label.street_address'),
            'shop_zipcode' => __('shopper::layout.forms.label.postal_code'),
            'shop_city' => __('shopper::layout.forms.label.city'),
            'shop_phone_number' => __('shopper::layout.forms.label.phone_number'),
            'shop_lng' => __('shopper::layout.forms.label.longitude'),
            'shop_lat' => __('shopper::layout.forms.label.latitude'),
            'shop_facebook_link' => __('shopper::words.socials.facebook'),
            'shop_instagram_link' => __('shopper::words.socials.twitter'),
            'shop_twitter_link' => __('shopper::words.socials.instagram'),
            'google_analytics_add_js' => __('shopper::layout.forms.label.ga_additional_script'),
        ][$key];
    }
}
