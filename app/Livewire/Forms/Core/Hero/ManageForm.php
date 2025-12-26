<?php

namespace App\Livewire\Forms\Core\Hero;

use App\Models\Hero;
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
    public $title_fr;
    public $title_ar;
    public $title_en;
    public $sub_title_ar;
    public $sub_title_fr;
    public $sub_title_en;

    public $introduction_fr;
    public $introduction_ar;
    public $introduction_en;
    public $primary_call_to_action_fr;
    public $primary_call_to_action_ar;
    public $primary_call_to_action_en;
    public $secondary_call_to_action_fr;
    public $secondary_call_to_action_ar;
    public $secondary_call_to_action_en;
    public $images;

    // Livewire rules
    public function rules()
    {
        $stringRule = [
            'required',
            'string',
            'min:10',
            'max:100',
            Rule::unique('heros')->ignore($this->id),
        ];
        $introductionRule = [
            'required',
            'string',
            'min:10',
            'max:500',
            Rule::unique('heros')->ignore($this->id),
        ];

        return [
            'title_ar' => $stringRule,
            'title_fr' => $stringRule,
            'title_en' => $stringRule,
            'sub_title_fr' => $stringRule,
            'sub_title_ar' => $stringRule,
            'sub_title_en' => $stringRule,
            'primary_call_to_action_fr' => $stringRule,
            'primary_call_to_action_ar' => $stringRule,
            'primary_call_to_action_en' => $stringRule,
            'secondary_call_to_action_fr' => $stringRule,
            'secondary_call_to_action_ar' => $stringRule,
            'secondary_call_to_action_en' => $stringRule,
            'introduction_ar' => $introductionRule,
            'introduction_fr' => $introductionRule,
            'introduction_en' => $introductionRule,

            'images' => 'nullable|array|max:5',
            'images.*' => 'nullable|file|mimes:jpeg,png,gif,ico,webp|max:10000',
        ];
    }

    public function validationAttributes()
    {
        return $this->returnTranslatedResponseAttributes("hero", [
            'images',
            'title_ar',
            'title_fr',
            'title_en',
            'sub_title_fr',
            'sub_title_en',
            'sub_title_ar',
            'introduction_fr',
            'introduction_ar',
            'introduction_en',
            'inaugural_year',
            'primary_call_to_call_action_fr',
            'primary_call_to_call_action_ar',
            'primary_call_to_call_action_en',
            'secondary_call_to_call_action_fr',
            'secondary_call_to_call_action_ar',
            'secondary_call_to_call_action_en',
        ]);
    }

    public function save($hero)
    {
        // Validate the form data
        try {
            $data = $this->validate();

            return DB::transaction(function () use ($data, $hero) {
                $hero->update($data);
                if ($this->images) {
                    $this->uploadAndUpdateImages($this->images, $hero->id,  Hero::class, "hero");
                }
                return $this->response(true, message: __("forms.manage_hero.responses.success"));
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('manage Hero form error: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
