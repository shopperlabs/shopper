<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Models\Channel;

final class ChannelObserver
{
    public function creating(Channel $channel): void
    {
        $this->ensureOnlyOneIsDefault($channel);
    }

    public function updating(Channel $channel): void
    {
        $this->ensureOnlyOneIsDefault($channel);
    }

    private function ensureOnlyOneIsDefault(Channel $channel): void
    {
        if ($channel->is_default) {
            $defaultChannel = Channel::resolvedQuery()
                ->where('id', '!=', $channel->id)
                ->where('is_default', true)
                ->first();

            $defaultChannel?->updateQuietly(['is_default' => false]);
        }
    }
}
