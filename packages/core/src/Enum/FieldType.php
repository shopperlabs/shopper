<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;

enum FieldType: string implements HasLabel
{
    use ArrayableEnum;

    case Checkbox = 'checkbox';

    case ColorPicker = 'colorpicker';

    case DatePicker = 'datepicker';

    case RichText = 'richtext';

    case Select = 'select';

    case Text = 'text';

    case Number = 'number';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Checkbox => __('shopper-core::forms.checkbox'),
            self::ColorPicker => __('shopper-core::forms.color_picker'),
            self::DatePicker => __('shopper-core::forms.datepicker'),
            self::RichText => __('shopper-core::forms.rich_text'),
            self::Select => __('shopper-core::forms.select'),
            self::Text => __('shopper-core::forms.text_field', ['type' => '(input)']),
            self::Number => __('shopper-core::forms.text_field', ['type' => '(number)']),
        };
    }
}
