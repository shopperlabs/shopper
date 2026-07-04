<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

enum SocialPlatform: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Facebook = 'facebook';

    case Instagram = 'instagram';

    case X = 'x';

    case Linkedin = 'linkedin';

    case Youtube = 'youtube';

    case Tiktok = 'tiktok';

    case Pinterest = 'pinterest';

    case Snapchat = 'snapchat';

    case Whatsapp = 'whatsapp';

    case Telegram = 'telegram';

    case Github = 'github';

    case Website = 'website';

    public function getLabel(): string
    {
        return match ($this) {
            self::X => 'X (Twitter)',
            self::Linkedin => 'LinkedIn',
            self::Youtube => 'YouTube',
            self::Tiktok => 'TikTok',
            self::Whatsapp => 'WhatsApp',
            self::Github => 'GitHub',
            default => ucfirst($this->value),
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Website => 'phosphor-globe-duotone',
            default => "phosphor-{$this->value}-logo-duotone",
        };
    }

    /**
     * @return array<int, string>
     */
    public function getHosts(): array
    {
        return match ($this) {
            self::Facebook => ['facebook.com', 'fb.com', 'fb.me'],
            self::Instagram => ['instagram.com', 'instagr.am'],
            self::X => ['x.com', 'twitter.com'],
            self::Linkedin => ['linkedin.com', 'lnkd.in'],
            self::Youtube => ['youtube.com', 'youtu.be'],
            self::Tiktok => ['tiktok.com'],
            self::Pinterest => ['pinterest.com'],
            self::Snapchat => ['snapchat.com'],
            self::Whatsapp => ['whatsapp.com', 'wa.me'],
            self::Telegram => ['telegram.me', 't.me'],
            self::Github => ['github.com'],
            self::Website => [],
        };
    }

    public function matchesUrl(string $url): bool
    {
        $hosts = $this->getHosts();

        if ($hosts === []) {
            return true;
        }

        $host = mb_strtolower((string) parse_url($url, PHP_URL_HOST));

        if (str_starts_with($host, 'www.')) {
            $host = mb_substr($host, 4);
        }

        foreach ($hosts as $allowedHost) {
            if ($host === $allowedHost || str_ends_with($host, '.'.$allowedHost)) {
                return true;
            }
        }

        return false;
    }
}
