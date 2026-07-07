<?php

declare(strict_types=1);

namespace Shopper\Core\Channel\Drivers;

use Shopper\Core\Channel\Contracts\ChannelDriver;

final class WebDriver implements ChannelDriver
{
    public function code(): string
    {
        return 'web';
    }

    public function name(): string
    {
        return 'Web';
    }

    public function logo(): string
    {
        return shopper_svg_data_uri(<<<'SVG'
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                <circle cx="32" cy="32" r="32" fill="#EFF6FF"/>
                <rect x="16" y="24" width="28" height="20" rx="2" fill="#3B82F6"/>
                <rect x="20" y="33" width="8" height="7" rx="1" fill="#93C5FD"/>
                <rect x="32" y="33" width="7" height="11" fill="#2563EB"/>
                <rect x="14" y="14" width="32" height="7" rx="1.5" fill="#3B82F6"/>
                <path d="M13 20h34v3a4.25 4.25 0 0 1-8.5 0 4.25 4.25 0 0 1-8.5 0 4.25 4.25 0 0 1-8.5 0 4.25 4.25 0 0 1-8.5 0z" fill="#2563EB"/>
                <circle cx="45" cy="44" r="8.5" fill="#FBBF24"/>
                <ellipse cx="45" cy="44" rx="3.5" ry="8.5" fill="none" stroke="#fff" stroke-width="1.5"/>
                <path d="M36.5 44h17" stroke="#fff" stroke-width="1.5"/>
            </svg>
            SVG);
    }

    public function isConfigured(): bool
    {
        return true;
    }
}
