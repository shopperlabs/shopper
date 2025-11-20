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

    private function ensureOnlyOneIsDefault(Channel $channel): void
    {
        if ($channel->is_default) {
            /** @var Channel|null $defaultChannel */
            $defaultChannel = (new ChannelRepository)
                ->query()
                ->where('id', '!=', $channel->id)
                ->where('is_default', true)
                ->first();

            if ($defaultChannel instanceof Channel) {
                $defaultChannel->updateQuietly(['is_default' => false]);
            }
        }
    }
}
