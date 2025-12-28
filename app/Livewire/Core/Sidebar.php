<?php

namespace App\Livewire\Core;

use App\Models\Menu;
use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Sidebar extends Component
{



    public string $superAdminDropdownLink;
     public array $superAdminDropdownItems=[];
    public string $adminDropdownLink;
     public array $adminDropdownItems=[];




         #[Computed()]
    public function services()
    {
        return Service::query()->get();
    }


    protected function getRoutesOfServiceForAuthor(): array
    {
        $serviceIcon = 'service';
        return [
            'dropdownName' => __('menus.author.services') ?? __('menus.default'),
            'icon' => $serviceIcon,
            'routes' => $this->services()
                ->map(fn($service) => [
                    'route' => 'service_route',
                    'label' => $service->name,
                    'parameters' => ['id'=>$service->id ],
                    'icon' => $serviceIcon,
                    'directLink' => false
                ])
                ->toArray(),
        ];
    }


    public function  mount() {

        $this->superAdminDropdownLink=__('sidebar.dropdowns.super_admin');

$this->superAdminDropdownItems= [
            [
                'route' =>"wilayates",
                'label' => __('pages.wilayates.name'),
                'icon' => 'wilaya',

            ],
            [
                'route' =>"occupation_fields",
                'label' => __('pages.occupation_fields.name'),
                'icon' => 'field',
            ],
            [
                'route' =>"banks",
                'label' => __('pages.banks.name'),
                'icon' => 'bank',
            ]

        ];

$this->adminDropdownLink=__('sidebar.dropdowns.admin');

$this->adminDropdownItems=[
    [
        'route'=>"messages",
        'label'=>__('pages.messages.name'),
         'icon'=>'message'
],
    [
        'route'=>"menus_route",
        'label'=>__('pages.menus.name'),
         'icon'=>'menu'
],
    [
        'route'=>"trends_route",
        'label'=>__('pages.trends.name'),
         'icon'=>'trend'
],
];

    }
    public function render()
    {
        return view('livewire.core.sidebar');
    }
}
