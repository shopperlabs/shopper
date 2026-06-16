<x-shopper::empty-state
    :title="__('shopper::pages/campaigns.title')"
    :content="__('shopper::pages/campaigns.description')"
    :button="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/campaigns.single')])"
    permission="campaigns.create"
    :url="route('shopper.campaigns.create')"
>
    <div class="shrink-0 text-sh-fg-muted">
        <x-filament::icon icon="phosphor-megaphone" class="h-40 w-40" />
    </div>
</x-shopper::empty-state>
