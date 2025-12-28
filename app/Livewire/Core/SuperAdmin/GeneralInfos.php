<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Livewire\Forms\Core\GeneralInfos\ManageForm;
use App\Models\GeneralSetting;
use App\Models\Image;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class GeneralInfos extends Component
{
    use WithFileUploads, GeneralTrait;

    public ManageForm $form;
    public GeneralSetting $gSetting;
    public ?string $temporaryImageUrl = null;
    public bool $isSubmitting = false;
     public $yearsOptions = [];

    public function updated($property): void
    {
        if ($property === "form.logo") {

            try {
                $this->temporaryImageUrl = $this->form->logo?->temporaryUrl() ?? "";
            } catch (\Exception $e) {
                $this->dispatch('open-errors', __('forms.common.errors.img.not_img'));
            }
        }
    }

    public function mount(): void
    {



         $this->yearsOptions = config('core.dates.INAUGURAL_YEARS');


        try {
            $this->gSetting = GeneralSetting::first();
            $logo = Image::where('imageable_id', $this->gSetting->id)
                ->where('imageable_type', 'App\Models\GeneralSetting')
                ->where('use_case', 'logo')->first();
            $this->temporaryImageUrl = $logo?->url;

            $this->form->fill([
                'id'          => $this->gSetting->id,
                'app_name'    => $this->gSetting->app_name,
                'acronym'     => $this->gSetting->acronym,
                'email'       => $this->gSetting->email,
                'phone'       => $this->gSetting->phone,
                'landline'    => $this->gSetting->landline,
                'fax'         => $this->gSetting->fax,
                'address_fr'  => $this->gSetting->address_fr,
                'address_ar'  => $this->gSetting->address_ar,
                'address_en'  => $this->gSetting->address_en,
                'map'         => $this->gSetting->map,
                'inaugural_year'=> $this->gSetting->inaugural_year,
                'youtube'   => $this->gSetting->youtube,
                'facebook'  => $this->gSetting->facebook,
                'linkedin'  => $this->gSetting->linkedin,
                'github'    => $this->gSetting->github,
                'instagram' => $this->gSetting->instagram,
                'tiktok'    => $this->gSetting->tiktok,
                'twitter'   => $this->gSetting->twitter,
                'threads'   => $this->gSetting->threads,
                'snapchat'  => $this->gSetting->snapchat,
                'pinterest' => $this->gSetting->pinterest,
                'reddit'    => $this->gSetting->reddit,
                'telegram'  => $this->gSetting->telegram,
                'whatsapp'  => $this->gSetting->whatsapp,
                'discord'   => $this->gSetting->discord,
                'twitch'    => $this->gSetting->twitch,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('open-errors', __('modals.common.not-found'));
        } catch (\Exception $e) {
            Log::error('Error in GeneralInfos mount method: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    public function handleSubmit(): void
    {
        $this->isSubmitting = true;
        $this->dispatch('form-submitted');

        $response = $this->form->save($this->gSetting);

        if ($response['status']) {
            $this->dispatch('logo-updated');
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }

        $this->isSubmitting = false;
    }

    public function render()
    {

        return view('livewire.core.super-admin.general-infos');
    }
}
