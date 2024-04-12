<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

enum FieldType: string
{
    case CHECKBOX = 'checkbox';

    case COLORPICKER = 'colorpicker';

    case DATEPICKER = 'datepicker';

    case NUMBER = 'number';

    case RADIO = 'radio';

    case RICHTEXT = 'richtext';

    case SELECT = 'select';

    case TEXT = 'text';

    public function label(): string
    {
        return match ($this) {
            self::CHECKBOX => __('shopper::layout.forms.label.checkbox'),
            self::COLORPICKER => __('shopper::layout.forms.label.colorpicker'),
            self::DATEPICKER => __('shopper::layout.forms.label.datepicker'),
            self::NUMBER => __('shopper::layout.forms.label.text_field', ['type' => '(number)']),
            self::RICHTEXT => __('shopper::layout.forms.label.richtext'),
            self::RADIO => __('shopper::layout.forms.label.radio'),
            self::SELECT => __('shopper::layout.forms.label.select'),
            self::TEXT => __('shopper::layout.forms.label.text_field', ['type' => '(input)']),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $enum) => [
                $enum->value => $enum->label(),
            ])
            ->toArray();
    }
}
