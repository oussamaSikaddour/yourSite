<?php

namespace App\Livewire\Forms\Core\OurQuality;

use App\Models\OurQuality;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    use ResponseTrait,ModelImageTrait ;
    public $id;
    public $name_fr;
    public $name_ar;
    public $name_en;
    public $image;




public function rules()
{
return [
    'name_fr' => ['required', 'string', 'max:255', Rule::unique('our_qualities')->ignore($this->id)],
    'name_ar' => ['required', 'string', 'max:255', Rule::unique('our_qualities')->ignore($this->id)],
    'name_en' => ['required', 'string', 'max:255', Rule::unique('our_qualities')->ignore($this->id)],
    'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,ico,webp|max:10000',
];
 }



 public function validationAttributes()
 {
     return [
         'name_fr' => __("forms.our_quality.name_fr"),
         'name_ar' => __("forms.our_quality.name_ar"),
         'name_en' => __("forms.our_quality.name_en"),
         'image' => __("forms.our_quality.image"),
     ];
 }


 public function save($oq)
 {

            // Validate the form data
            try {
                $data = $this->validate();

         return DB::transaction(function () use ($data,$oq ) {

             $oq->update($data);
               if ($this->image) {
                   $this->uploadAndUpdateImage($this->image, $oq->id, OurQuality::class, "our_quality");
               }
               return $this->response(true,message:__("forms.our_quality.responses.update_success"));

         });
     } catch (\Illuminate\Validation\ValidationException $e) {
        // Return all validation errors
        return $this->response(false, errors: $e->validator->errors()->all());
    }
     catch (\Exception $e) {
         return $this->response(false, errors: [$e->getMessage()]);
     }
 }
}
