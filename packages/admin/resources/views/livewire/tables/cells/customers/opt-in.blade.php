<span @class([
    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
    'bg-green-100 text-green-800' => $row->opt_in,
    'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300' => ! $row->opt_in,
])>
    {{ $row->opt_in ? __('shopper::layout.forms.label.subscribed') : __('shopper::layout.forms.label.not_subscribed') }}
</span>
