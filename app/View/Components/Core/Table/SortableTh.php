<?php

namespace App\View\Components\Core\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SortableTh extends Component
{
    public string $model;
    public string $label;
    public string $sortBy;
    public string $sortDirection;

    public function __construct(
        string $model,
        string $label,
        string $sortBy,
        string $sortDirection,
        bool $appLocal = false
    ) {
        $this->label = $label;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;

        // Apply localization if requested
        if (!$appLocal) {
            $this->model = $model;
        } else {
            $this->model = $model . '_' . app()->getLocale();
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.core.table.sortable-th');
    }
}
