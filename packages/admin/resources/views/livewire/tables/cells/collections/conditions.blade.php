<span class="text-sm leading-5 text-gray-500 dark:text-gray-400">
    @if($row->rules->isNotEmpty())
        {{ ucfirst($row->firstRule()) }}
    @else
        <span class="inline-flex text-gray-500 dark:text-gray-400">&mdash;</span>
    @endif
</span>
