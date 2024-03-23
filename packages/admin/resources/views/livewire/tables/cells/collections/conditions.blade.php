<span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
    @if($row->rules->isNotEmpty())
        {{ ucfirst($row->firstRule()) }}
    @else
        <span class="inline-flex text-secondary-500 dark:text-secondary-400">&mdash;</span>
    @endif
</span>
