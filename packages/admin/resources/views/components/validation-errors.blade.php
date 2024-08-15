@if ($errors->count() > 0)
    <div class="my-2 rounded-lg bg-danger-50 p-4">
        <div class="flex">
            <div class="shrink-0">
                <svg class="size-5 text-danger-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"
                    />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium leading-5 text-danger-800">
                    {{ __('shopper::forms.error') }}
                </h3>
                <div class="mt-2 text-sm leading-5 text-danger-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
