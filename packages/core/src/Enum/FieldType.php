<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Traits\ArrayableEnum;

enum FieldType: string
{
    use ArrayableEnum;

    case CHECKBOX = 'checkbox';

    case COLORPICKER = 'colorpicker';

    case DATEPICKER = 'datepicker';

    case RICHTEXT = 'richtext';

    case SELECT = 'select';

    case TEXT = 'text';

    case NUMBER = 'number';

    public function label(): string
    {
        return match ($this) {
            self::CHECKBOX => __('shopper::layout.forms.label.checkbox'),
            self::COLORPICKER => __('shopper::layout.forms.label.colorpicker'),
            self::DATEPICKER => __('shopper::layout.forms.label.datepicker'),
            self::RICHTEXT => __('shopper::layout.forms.label.richtext'),
            self::SELECT => __('shopper::layout.forms.label.select'),
            self::TEXT => __('shopper::layout.forms.label.text_field', ['type' => '(input)']),
            self::NUMBER => __('shopper::layout.forms.label.text_field', ['type' => '(number)']),
        };
    }
}
