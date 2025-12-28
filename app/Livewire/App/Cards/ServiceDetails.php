<?php

namespace App\Livewire\App\Cards;

use App\Models\Person;
use App\Models\Service;
use App\Models\Slider;
use Livewire\Component;

class ServiceDetails extends Component
{
    public Service $service;
    public ?Slider $slider = null;
    public Person $headService;
    public array $slides = [];

    public function mount()
    {
        $this->loadHeadOfService();
        $this->loadSliderWithSlides();
    }

    protected function loadHeadOfService(): void
    {
        $this->headService = Person::with([
            'user.avatar',
            'occupations' => function ($query) {
                $query->where('is_active', true)
                      ->orderBy('created_at')
                      ->limit(1)
                      ->with('grade');
            },
        ])->findOrFail($this->service->head_of_service_id);
    }

    protected function loadSliderWithSlides(): void
    {
        $this->slider = Slider::with(['slides' => function ($query) {
            $query->whereHas('image');
        }])
        ->where('position', 1)
        ->where('state', 'published')
        ->first();

        if ($this->slider && $this->slider->slides && $this->slider->slides->isNotEmpty()) {
            $this->slides = $this->slider->slides
                ->map(function ($slide) {
                    return [
                        'image' => $slide->image->url ?? null,
                        'title' => $slide->title,
                        'caption' => $slide->content,
                    ];
                })
                ->filter(function ($slide) {
                    return !empty($slide['image']);
                })
                ->values()
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.app.cards.service-details');
    }
}
