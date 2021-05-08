@props([
    'formAction' => false,
    'headerClasses' => '',
    'footerClasses' => 'bg-white px-4 pb-5 sm:px-4 sm:flex',
])

<div>
    @if($formAction)
        <form wire:submit.prevent="{{ $formAction }}">
    @endif
        <div class="{{ $headerClasses }}">
            @if(isset($title))
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $title }}
                </h3>
            @endif

            @if(isset($subtitle))
                <div class="mt-2">
                    <p class="text-sm leading-5 text-gray-500">
                        {{ $subtitle }}
                    </p>
                </div>
            @endif
        </div>

        @if(isset($content))
            <div class="bg-white px-4 sm:p-6">
                {{ $content }}
            </div>
        @endif

        <div class="{{ $footerClasses }}">
            {{ $buttons ?? null }}
        </div>
    @if($formAction)
        </form>
    @endif
</div>
