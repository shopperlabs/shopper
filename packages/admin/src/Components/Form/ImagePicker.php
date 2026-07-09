<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\Radio;

class ImagePicker extends Radio
{
    protected string $view = 'shopper::filament.form.image-picker';
}
