<?php

namespace App\Livewire\Forms\Core\GeneralInfos;

use App\Models\GeneralSetting;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ManageForm extends Form
{
    use ResponseTrait, ModelImageTrait;

    /*
    |--------------------------------------------------------------------------
    | Public Properties (Form Fields)
    |--------------------------------------------------------------------------
    */
    public $id;
    public $app_name;
    public $acronym;
    public $email;
    public $phone;
    public $landline;
    public $fax;
    public $address_fr;
    public $address_ar;
    public $address_en;
    public $map;
    public $logo;
    public $inaugural_year;
    // Social links (all fillable from model)
    public $youtube;
    public $facebook;
    public $linkedin;
    public $github;
    public $instagram;
    public $tiktok;
    public $twitter;        // Twitter / X
    public $threads;
    public $snapchat;
    public $pinterest;
    public $reddit;
    public $telegram;
    public $whatsapp;
    public $discord;
    public $twitch;

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */
    public function rules(): array
    {
        // Unique rule builder: ignore current record id and soft-deletes
        $uniqueBase = fn(string $field) => Rule::unique('general_settings', $field)
            ->whereNull('deleted_at')
            ->ignore($this->id);

        $localized = ['nullable', 'string', 'min:3', 'max:100'];

        // Social URL rule (nullable + url + optional unique)
        $socialUrl = fn(string $field) => array_filter([
            'nullable',
            'url',
            $uniqueBase($field),
        ]);

        return [
            // Basic / localized
            'app_name'   => $localized,
            'acronym'    => $localized,
            'address_fr' => $localized,
            'address_ar' => $localized,
            'address_en' => $localized,

            // Contact & identifiers
            'email' => ['required', 'email', 'max:255', $uniqueBase('email')],
            'phone' => ['nullable', 'regex:/^(05|06|07)\d{8}$/', $uniqueBase('phone')],
            'landline' => ['nullable', 'regex:/^(0)\d{8}$/', $uniqueBase('landline')],
            'fax' => ['nullable', 'regex:/^(0)\d{8}$/', $uniqueBase('fax')],

            // Map & maintenance
            'map' => 'nullable|string|max:1000',

            // Logo
            'logo' => 'nullable|file|mimes:jpeg,jpg,png,gif,ico,webp|max:10240',

            'inaugural_year' => [
                'required',
                'integer',
                Rule::in(range(1962, date('Y'))),
            ],
            // Socials (all urls, optional unique)
            'youtube'   => $socialUrl('youtube'),
            'facebook'  => $socialUrl('facebook'),
            'linkedin'  => $socialUrl('linkedin'),
            'github'    => $socialUrl('github'),
            'instagram' => $socialUrl('instagram'),
            'tiktok'    => $socialUrl('tiktok'),
            'twitter'         => $socialUrl('twitter'),
            'threads'   => $socialUrl('threads'),
            'snapchat'  => $socialUrl('snapchat'),
            'pinterest' => $socialUrl('pinterest'),
            'reddit'    => $socialUrl('reddit'),
            'telegram'  => $socialUrl('telegram'),
            'whatsapp'  => $socialUrl('whatsapp'),
            'discord'   => $socialUrl('discord'),
            'twitch'    => $socialUrl('twitch'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Validation Attributes (friendly names)
    |--------------------------------------------------------------------------
    */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('general_infos', [
            'app_name',
            'acronym',
            'email',
            'phone',
            'landline',
            'fax',
            'address_fr',
            'address_ar',
            'address_en',
            'map',
            'logo',
            'maintenance',
            'youtube',
            'facebook',
            'linkedin',
            'github',
            'instagram',
            'tiktok',
            'twitter',
            'threads',
            'snapchat',
            'pinterest',
            'reddit',
            'telegram',
            'whatsapp',
            'discord',
            'twitch',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Custom Messages
    |--------------------------------------------------------------------------
    */
    public function messages(): array
    {
        $attr = $this->validationAttributes();

        return [
            'phone.regex'    => __("forms.common.errors.not_match.phone", ['attribute' => $attr['phone'] ?? 'phone']),
            'landline.regex' => __('forms.common.errors.not_match.land_line', ['attribute' => $attr['landline'] ?? 'landline']),
            'fax.regex'      => __('forms.common.errors.not_match.land_line', ['attribute' => $attr['fax'] ?? 'fax']),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Save or Update Logic
    |--------------------------------------------------------------------------
    */
    public function save(GeneralSetting $gSetting)
    {
        try {
            $data = $this->validate();

            return DB::transaction(function () use ($data, $gSetting) {
                // Upload logo if provided (handles create/update)
                if ($this->logo) {
                    $this->uploadAndUpdateImage(
                        $this->logo,
                        $gSetting->id,
                        GeneralSetting::class,
                        'logo'
                    );
                }



                $gSetting->update($data);

                // keep model cache consistent
                $gSetting->clearCache();

                return $this->response(true, message: __("forms.general_infos.responses.success"));
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Throwable $e) {
            Log::error('ManageForm save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->response(false, errors: [__('forms.common.errors.default')]);
        }
    }
}
