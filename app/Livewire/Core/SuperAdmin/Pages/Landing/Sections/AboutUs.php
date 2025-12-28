<?php

namespace App\Livewire\Core\SuperAdmin\Pages\Landing\Sections;

use App\Livewire\Forms\Core\AboutUs\ManageForm;
use App\Models\AboutUs as ModelsAboutUs;
use App\Models\Image;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class AboutUs extends Component
{

    use WithFileUploads,GeneralTrait;
    public ManageForm $form;
    public ModelsAboutUs $aUs;
    public $temporaryImageUrl;





    public function updated($property)
    {
        try {
            if ($property === "form.image" ) {


                    if ($this->form->image->temporaryUrl()) {
                    $this->temporaryImageUrl = $this->form->image->temporaryUrl();
                    }

            }
        } catch (\Exception $e) {
            $this->dispatch('open-errors', [__('forms.common.errors.img.not_img')]);
        }
    }


    public function mount()
    {
            try {
                $this->aUs = ModelsAboutUs::first();
              $image = Image::where('imageable_id', $this->aUs->id)
              ->where('imageable_type','App\Models\AboutUs')
              ->where('use_case','image')->first();
               $this->temporaryImageUrl= $image?->url ;
                $this->form->fill([
                    'id' => $this->aUs->id,
                    'sub_title_ar'=>$this->aUs->sub_title_ar,
                    'sub_title_fr'=>$this->aUs->sub_title_fr,
                    'sub_title_en'=>$this->aUs->sub_title_en,
                    'first_paragraph_fr'=>$this->aUs->first_paragraph_fr,
                    'first_paragraph_en'=>$this->aUs->first_paragraph_en,
                    'first_paragraph_ar'=>$this->aUs->first_paragraph_ar,
                    'second_paragraph_fr'=>$this->aUs->second_paragraph_fr,
                    'second_paragraph_en'=>$this->aUs->second_paragraph_en,
                    'second_paragraph_ar'=>$this->aUs->second_paragraph_ar,
                    'third_paragraph_fr'=>$this->aUs->third_paragraph_fr,
                    'third_paragraph_en'=>$this->aUs->third_paragraph_en,
                    'third_paragraph_ar'=>$this->aUs->third_paragraph_ar,
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                Log::error('Error in AboutUs mount method: ' . $e->getMessage());
                $this->dispatch('open-errors', __('forms.common.errors.default'));
            }

    }


    public function handleSubmit()
    {
        $this->dispatch('form-submitted');
        $response =$this->form->save($this->aUs);
        if ($response['status']) {
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }
    public function render()
    {
        return view('livewire.core.super-admin.pages.landing.sections.about-us');
    }
}
