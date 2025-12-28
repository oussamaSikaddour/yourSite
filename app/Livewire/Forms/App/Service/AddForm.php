<?php

namespace App\Livewire\Forms\App\Service;

use App\Models\Service;
use App\Rules\Core\LandLineNumberExist;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait,ModelImageTrait;

    public $name_fr = "";
    public $name_ar = "";
    public $name_en = "";
    public $head_of_service_id;
    public $tel = "";
    public $fax = "";
    public $specialty_id = ""; // if needed
    public $email = "";
    public $beds_number;
    public $specialists_number;
    public $physicians_number;
    public $paramedics_number;
    public $introduction_fr;
    public $introduction_ar;
    public $introduction_en;
    public $icon;


    public function rules()
    {
        $localizedStringRules = [
            'required',
            'string',
            'min:5',
            'max:255',
            Rule::unique('services')
                ->whereNull('deleted_at'),
        ];


        return [
            'name_ar' => $localizedStringRules,
            'name_fr' => $localizedStringRules,
            'name_en' => $localizedStringRules,
            'introduction_fr'=> $localizedStringRules,
            'introduction_ar'=> $localizedStringRules,
            'introduction_en'=> $localizedStringRules,
            'specialty_id' => ['required', 'exists:field_specialties,id'],
            'head_of_service_id' => [
                'required',
                'integer',
                'exists:persons,id',
                Rule::unique('services')
                    ->whereNull('deleted_at'),
            ],
            'tel' => ['required', 'digits:9', new LandLineNumberExist(new Service())],
            'fax' => ['nullable', 'digits:9', new LandLineNumberExist(new Service())],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('services')
                    ->whereNull('deleted_at')
            ],
            'beds_number' => 'nullable|integer|min:0',
            'specialists_number' => 'nullable|integer|min:0',
            'physicians_number' => 'nullable|integer|min:0',
            'paramedics_number' => 'nullable|integer|min:0',
            'icon' => 'nullable|file|mimes:jpeg,jpg,png,gif,ico,webp|max:10240',
        ];
    }

    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('service', [
            "name_ar",
            "name_fr",
            "name_en",
            'introduction_fr',
            'introduction_en',
            'introduction_ar',
            "head_of_service_id",
            'email',
            "tel",
            "fax",
            "specialty_id",
            'beds_number',
            'specialists_number',
            'physicians_number',
            'paramedics_number',
        ]);
    }

    public function save()
    {
        try {

  $data = $this->validate();
            return DB::transaction(function () use ($data) {


            $service=Service::create($data);


        if ($this->icon) {
            $this->uploadAndCreateImage(
                 $this->icon,
                 $service->id,
                 Service::class,
                'icon'
            );
        }



            return $this->response(true, message: __("forms.service.responses.add_success"));
               });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Service AddForm save method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
