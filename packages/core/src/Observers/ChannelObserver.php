<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Models\Channel;
use Shopper\Core\Repositories\ChannelRepository;

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

    protected function ensureOnlyOneIsDefault(Channel $channel): void
    {
        if ($channel->is_default) {
            /** @var Channel | null $defaultChannel */
            $defaultChannel = (new ChannelRepository)
                ->makeModel()
                ->where('id', '!=', $channel->id)
                ->where('is_default', true)
                ->first();

            if ($defaultChannel) {
                $defaultChannel->is_default = false;
                $defaultChannel->saveQuietly();
            }
        }
    }
}
