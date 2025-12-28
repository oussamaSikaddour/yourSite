<?php

namespace App\Livewire\Forms\Core\OurQuality;

use App\Models\OurQuality;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait, ModelImageTrait;

    public $name_fr;
    public $name_ar;
    public $name_en;
    public $image;

    public function rules()
    {
        return [
            'name_fr' => ['required', 'string', 'max:255', Rule::unique('our_qualities')],
            'name_ar' => ['required', 'string', 'max:255', Rule::unique('our_qualities')],
            'name_en' => ['required', 'string', 'max:255', Rule::unique('our_qualities')],
            'image' => ['required', 'file', 'mimes:jpeg,jpg,png,gif,ico,webp', 'max:10240'], // 10MB
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

    public function save()
    {
        try {
            $data = $this->validate();
            return DB::transaction(function () use ($data) {
                $oq = OurQuality::create($data);
                if ($this->image) {
                    $this->uploadAndCreateImage(
                        $this->image,
                        $oq->id,
                        OurQuality::class,
                        "our_quality"
                    );
                }
                return $this->response(true, message: __("forms.our_quality.responses.add_success"));
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('OurQuality add form error: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
