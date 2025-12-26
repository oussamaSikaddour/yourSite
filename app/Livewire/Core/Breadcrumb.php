<?php

namespace App\Livewire\Core;

use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Breadcrumb extends Component
{
    use GeneralTrait;


    /**
     * Expected format:
     * [
     *   ['route' => 'dashboard', 'label' => 'Dashboard', 'params' => []],
     *   ['route' => '/articles', 'label' => 'Articles'],
     *   ['route' => '#', 'label' => 'Create'],
     * ]
     */
    public array $breadcrumbLinks = [];

    public function mount(): void
    {
        $this->breadcrumbLinks = collect($this->breadcrumbLinks)->map(function ($link) {
            return [
                'label'    => $link['label'] ?? '',
                'url'      => $this->resolveUrl($link),
            ];
        })->toArray();
    }

    protected function resolveUrl(array $link): string
    {
        $routeName = $this->resolveRouteName($link['route']);
        return $routeName
            ? route($routeName, $link['params']?? [])
            : '#';
    }




    public function render()
    {
        return view('livewire.core.breadcrumb');
    }
}
