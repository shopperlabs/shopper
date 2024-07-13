<span class="{{ $order->status->badge() }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
    {{ $order->status->translateValue() }}
</span>
