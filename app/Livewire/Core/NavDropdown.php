<?php

namespace App\Livewire\Core;

use Livewire\Component;

class NavDropdown extends Component
{
    public string $dropdownName = '';
    public string $icon = '';
    public array $routes = [];
    public array $data = [];
    public string $dropdownLink = '';


    public function mount(): void
    {
        $this->initializeDropdown();
    }

    public function refreshDropdownInfo(): void
    {


        $this->initializeDropdown();
    }

    protected function initializeDropdown(): void
    {

        $this->dropdownLink = $this->dropdownName ?: __('pages.dropdown.default');
        $this->routes = $this->getDropdownRoutes($this->data);
    }

    /**
     * Transform data items into dropdown routes.
     */
    protected function getDropdownRoutes(array $links): array
    {
        $defaultIcon = 'link';
        $routes = [];

        foreach ($links as $link) {
            $routes[] = [
                'route' => $link['route'] ?? '#',
                'label' => $link['label'] ?? __('pages.dropdown.unknown'),
                'parameters' => $link['parameters'] ?? [],
                'icon' => $link['icon'] ?? $defaultIcon,
                'directLink' => $link['directLink'] //false or true,
            ];
        }

        return $routes;
    }

    /**
     * Refresh dropdown links dynamically.
     */
    public function refreshDropdownRoutes(?array $newData = null): void
    {
        // Allow dynamic replacement of data
        if ($newData !== null) {
            $this->data = $newData;
        }

        // Rebuild dropdown data
        $this->routes = $this->getDropdownRoutes($this->data);
        $this->dropdownLink = $this->dropdownName ?: __('pages.dropdown.default');
    }

    public function render()
    {
        return view('livewire.core.nav-dropdown');
    }
}
