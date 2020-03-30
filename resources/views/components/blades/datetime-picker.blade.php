<div class="p-4 bg-gray-50" x-data="{ show: false }">
    <div class="flex items-center justify-between">
        <span class="text-sm text-gray-400">{{ __('Publication date') }}</span>
        <button type="button" @click="show = true">
            <svg class="h-5 w-5 text-gray-700 hover:text-gray-500" fill="currentColor" viewBox="0 0 32 32">
                <path d="M28.25 2.5A3.754 3.754 0 0 1 32 6.25v22A3.754 3.754 0 0 1 28.25 32H3.75A3.754 3.754 0 0 1 0 28.25v-22A3.754 3.754 0 0 1 3.75 2.5h1.5V0h2.5v2.5h16.5V0h2.5v2.5h1.5zm1.25 25.75v-16.5h-27v16.5c0 .689.561 1.25 1.25 1.25h24.5c.689 0 1.25-.561 1.25-1.25zm0-19v-3c0-.689-.561-1.25-1.25-1.25h-1.5v2.5h-2.5V5H7.75v2.5h-2.5V5h-1.5c-.689 0-1.25.561-1.25 1.25v3h27zM4.75 16.875v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm-20 5v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm-15 5v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5-5v-2.5h2.5v2.5h-2.5z" />
            </svg>
        </button>
    </div>
    <div class="flex space-x-2 items-center mt-3" x-show="show">
        <span id="date-picker" data-label="{{ __('Date') }}" data-value="{{ $published_at !== null ? $published_at->format('Y-M-D') : '' }}">&nbsp;</span>
        <span id="time-picker" data-label="{{ __('Hour') }}" data-value="{{ $published_at !== null ? $published_at->format('H:i') : '' }}">&nbsp;</span>
        <button type="button" @click="show = false;">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>
