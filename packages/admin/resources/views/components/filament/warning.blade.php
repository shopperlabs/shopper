<div class="rounded-lg bg-yellow-50 p-4">
    <div class="flex">
        <div class="shrink-0">
            <x-heroicon-s-exclamation-triangle class="size-5 text-yellow-400" aria-hidden="true" />
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">{{ __('shopper::words.attention_needed') }}</h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>
                    {{ __('shopper::words.feature_enabled', ['feature' => \Illuminate\Support\Str::title($getFeature())]) }}
                </p>
            </div>
        </div>
    </div>
</div>
