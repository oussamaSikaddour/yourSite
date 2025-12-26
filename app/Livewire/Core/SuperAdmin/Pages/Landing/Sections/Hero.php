<?php

namespace App\Livewire\Core\SuperAdmin\Pages\Landing\Sections;

use App\Livewire\Forms\Core\Hero\ManageForm;
use App\Models\Hero as ModelsHero;
use App\Models\Image;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Hero extends Component
{

    use GeneralTrait,WithFileUploads;
    public ManageForm $form;
    public ModelsHero $hero;
    public $temporaryImageUrls=[];





    public function updated($property)
    {
        try {
            if ($property === "form.images" && $this->form->images) {

                $this->temporaryImageUrls= [];
                foreach ($this->form->images as $image) {
                    if (!$image->temporaryUrl()) {
                        $this->temporaryImageUrls = []; // Set to empty array if any image doesn't have a temporary URL
                        break; // Exit the loop
                    }
                    $this->temporaryImageUrls[] = $image->temporaryUrl();
                }
            }
        } catch (\Exception $e) {
            $this->dispatch('open-errors', [__('forms.common.errors.img.not_imgs')]);
        }
    }


    public function mount()
    {


            try {
                $this->hero = ModelsHero::first();

                $images = Image::where('imageable_id', $this->hero->id)
                ->where('imageable_type','App\Models\Hero')
                ->where('use_case','hero')->get();
                foreach($images as $image){
                 $this->temporaryImageUrls[] = $image?->url ?? "";
                }

                $this->form->fill([
                    'id' => $this->hero->id,
                    'title_fr' => $this->hero->title_fr,
                    'title_ar' => $this->hero->title_ar,
                    'title_en' => $this->hero->title_ar,
                    'sub_title_ar' => $this->hero->sub_title_ar,
                    'sub_title_fr' => $this->hero->sub_title_fr,
                    'sub_title_en' => $this->hero->sub_title_fr,
                    'introduction_fr'=>$this->hero->introduction_fr,
                    'introduction_ar'=>$this->hero->introduction_ar,
                    'introduction_en'=>$this->hero->introduction_en,
                    'primary_call_to_action_fr'=>$this->hero->primary_call_to_action_fr,
                    'primary_call_to_action_ar'=>$this->hero->primary_call_to_action_ar,
                    'primary_call_to_action_en'=>$this->hero->primary_call_to_action_en,
                    'secondary_call_to_action_ar'=>$this->hero->secondary_call_to_action_ar,
                    'secondary_call_to_action_fr'=>$this->hero->secondary_call_to_action_fr,
                    'secondary_call_to_action_en'=>$this->hero->secondary_call_to_action_en,
                ]);

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                Log::error('Error in Hero mount method: ' . $e->getMessage());
                $this->dispatch('open-errors', __('forms.common.errors.default'));
            }

    }


    public function handleSubmit()
    {
        $this->dispatch('form-submitted');

        $response =$this->form->save($this->hero);
        if ($response['status']) {
            $this->dispatch('open-toast', $response['message']);

        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }


    public function render()
    {
        return view('livewire.core.super-admin.pages.landing.sections.hero');
    }
}
