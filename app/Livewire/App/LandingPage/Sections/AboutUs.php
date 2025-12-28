<?php

namespace App\Livewire\App\LandingPage\Sections;

use App\Models\AboutUs as ModelsAboutUs;
use App\Models\GeneralSetting;
use App\Models\Service;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Throwable;

class AboutUs extends Component
{
    public ?ModelsAboutUs $aboutUs = null;
    public ?int $years=null;

#[Computed()]
public function services()
{

 return Service::query()
    ->select([
        DB::raw('COUNT(*) as total_services'),          // total number of services
        DB::raw('SUM(beds_number) as total_beds'),      // sum of beds
        // DB::raw('SUM(specialists_number) as total_specialists'),
        // DB::raw('SUM(physicians_number) as total_physicians'),
        // DB::raw('SUM(paramedics_number) as total_paramedics'),
    ])
    ->first();

}


    public function mount(): void
    {
        try {
            $setting = GeneralSetting::first();

$this->years = now()->year - ($setting->inaugural_year ?? 0);
            $this->aboutUs = ModelsAboutUs::first() ?? new ModelsAboutUs();
        } catch (Throwable $e) {
            Log::error('Error loading AboutUs section: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    public function render()
    {
        return view('livewire.app.landing-page.sections.about-us');
    }
}
