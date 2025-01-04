<x-shopper::alert
    icon="untitledui-exclamation-triangle"
    :title="__('shopper::words.attention_needed')"
    :message="__('shopper::words.feature_enabled', ['feature' => \Illuminate\Support\Str::title($getFeature())])"
/>
