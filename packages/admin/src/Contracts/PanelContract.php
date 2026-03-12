<?php

declare(strict_types=1);

namespace Shopper\Contracts;

interface PanelContract
{
    public static function closePanelOnClickAway(): bool;

    public static function closePanelOnEscape(): bool;

    public static function closePanelOnEscapeIsForceful(): bool;

    public static function dispatchCloseEvent(): bool;

    public static function destroyOnClose(): bool;

    public static function panelMaxWidth(): string;

    public static function panelMaxWidthClass(): string;
}
