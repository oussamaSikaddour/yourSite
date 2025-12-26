
<th {{ $attributes }} scope="col" wire:click="toggleSortBy('{{ $model }}')">
    <div>
        {{ $label }}
        @if ($sortBy !== $model)
            <i class="fa-solid fa-sort"></i>
        @elseif ($sortDirection === "ASC")
            <i class="fa-solid fa-sort-up"></i>
        @else
            <i class="fa-solid fa-sort-down"></i>
        @endif
    </div>
</th>

