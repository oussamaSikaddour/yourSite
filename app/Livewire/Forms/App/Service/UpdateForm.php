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


class UpdateForm extends Form
{
    use ResponseTrait, ModelImageTrait;

    public $id;
    public $name_fr = "";
    public $name_ar = "";
    public $name_en = "";
    public $head_of_service_id;
    public $tel = "";
    public $fax = "";
    public $specialty_id = ""; // change to nullable if optional
    public $email = "";
    public $beds_number;
    public $specialists_number;
    public $physicians_number;
    public $paramedics_number;
    public $icon;


    /**
     * Define validation rules.
     */
    public function rules(): array
    {


        $localizedNameRules = [
            'required',
            'string',
            'min:5',
            'max:255',
            Rule::unique('services')
                ->whereNull('deleted_at')
                ->ignore($this->id),
        ];

        return [
            'name_ar' => $localizedNameRules,
            'name_fr' => $localizedNameRules,
            'name_en' => $localizedNameRules,
            'specialty_id' => ['required', 'exists:field_specialties,id'],
            'head_of_service_id' => [
                'required',
                'integer',
                 'exists:persons,id',
                Rule::unique('services')
                    ->whereNull('deleted_at')
                    ->ignore($this->id),
            ],
            'tel' => ['required', 'digits:9', new LandLineNumberExist(new Service(), $this->id)],
            'fax' => ['nullable', 'digits:9', new LandLineNumberExist(new Service(), $this->id)],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('services')
                    ->whereNull('deleted_at')
                    ->ignore($this->id),
            ],
            'beds_number' => 'nullable|integer|min:0',
            'specialists_number' => 'nullable|integer|min:0',
            'physicians_number' => 'nullable|integer|min:0',
            'paramedics_number' => 'nullable|integer|min:0',
            'icon' => 'nullable|file|mimes:jpeg,jpg,png,gif,ico,webp|max:10240',
        ];
    }

    /**
     * Custom validation attribute names for localization.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('service', [
            "name_ar",
            "name_fr",
            "name_en",
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

    /**
     * Optional: custom validation messages.
     */
    public function messages(): array
    {
        return [
            // Example: 'name_ar.unique' => __('forms.service.errors.name_ar_unique'),
        ];
    }

    /**
     * Update the service with validated data.
     *
     * @param Service $service
     * @return array
     */
    public function save(Service $service): array
    {
        try {
            $data = $this->validate();
            return DB::transaction(function () use ($data, $service) {
                $service->fill($data)->save();

                if ($this->icon) {
                    $this->uploadAndUpdateImage(
                        $this->icon,
                        $service->id,
                        Service::class,
                        'icon'
                    );
                }


                return $this->response(true, message: __("forms.service.responses.update_success"));
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Service UpdateForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
