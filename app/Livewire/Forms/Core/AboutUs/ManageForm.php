<?php

namespace App\Livewire\Forms\Core\AboutUs;

use App\Models\AboutUs;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ManageForm extends Form
{
    use ResponseTrait, ModelImageTrait;

    public $id;
    public $sub_title_fr;
    public $sub_title_ar;
    public $sub_title_en;

    public $first_paragraph_fr;
    public $first_paragraph_ar;
    public $first_paragraph_en;
    public $second_paragraph_fr;
    public $second_paragraph_ar;
    public $second_paragraph_en;
    public $third_paragraph_fr;
    public $third_paragraph_ar;
    public $third_paragraph_en;
    public $image;




    // Livewire rules

    public function rules()
    {
        $id = $this->id;

        $languages = ['fr', 'ar', 'en'];

        $rules = [];

        foreach ($languages as $lang) {
            // Sub titles
            $rules["sub_title_{$lang}"] = [
                'required',
                'string',
                'min:5',
                'max:100',
                Rule::unique('about_us', "sub_title_{$lang}")->ignore($id),
            ];

            // First paragraph (required)
            $rules["first_paragraph_{$lang}"] = [
                'required',
                'string',
                'min:20',
                'max:300',
            ];

            // Second paragraph (optional)
            $rules["second_paragraph_{$lang}"] = [
                'nullable',
                'string',
                'min:20',
                'max:300',
            ];

            // Third paragraph (optional)
            $rules["third_paragraph_{$lang}"] = [
                'nullable',
                'string',
                'min:20',
                'max:300',
            ];
        }

        // Image rule
        $rules['image'] = 'nullable|file|mimes:jpeg,jpg,png,gif,ico,webp|max:10240';

        return $rules;
    }

    public function validationAttributes()
    {
        return $this->returnTranslatedResponseAttributes("about_us", [
            "sub_title_fr",
            "sub_title_ar",
            "sub_title_en",
            'first_paragraph_fr',
            'first_paragraph_ar',
            'first_paragraph_en',
            'second_paragraph_en',
            'second_paragraph_fr',
            'second_paragraph_ar',
            'third_paragraph_fr',
            'third_paragraph_ar',
            'third_paragraph_en',
        ]);
    }




    public function save($aUs)
    {
        // Validate the form data
        try {
            $data = $this->validate();
            return DB::transaction(function () use ($data, $aUs) {
                $image = $this->image;
                if ($image) {
                    $this->uploadAndUpdateImage($image, $aUs->id, AboutUs::class, "image");
                }
                $aUs->update($data);
                return $this->response(true, message: __("forms.manage_about_us.responses.success"));
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in manageform : ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
