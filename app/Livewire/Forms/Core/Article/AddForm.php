<?php

namespace App\Livewire\Forms\Core\Article;

use App\Models\Article;
use App\Models\Service;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait,ModelImageTrait;

    public $user_id;
    public $title_fr;
    public $title_ar;
    public $title_en;
    public $content_ar;
    public $content_fr;
    public $content_en;
    public $published_at;
    public $articleable_type;
    public $articleable_id;
    public $images;

    /**
     * Define validation rules.
     */
    public function rules()
    {

        $localizedTitleRules = [
            'required', 'string', 'min:5', 'max:60',
            Rule::unique('articles')
                    ->whereNull('deleted_at')
        ];
        $localizedContentRules = [
            'required', 'string', 'min:100', 'max:2000',
            Rule::unique('articles')
                    ->whereNull('deleted_at')
        ];
     $commonRules = [
        'title_ar' => $localizedTitleRules,
        'title_fr' => $localizedTitleRules,
        'title_en'=>$localizedTitleRules,
        'content_ar' => $localizedContentRules,
        'content_fr' => $localizedContentRules,
         'content_en' => $localizedContentRules,
         'user_id' => 'required|exists:users,id',
          'published_at' => 'required|date|date_format:Y-m-d|after_or_equal:today',
          'articleable_type' => 'required|string|min:10',
           'images.*' => 'nullable|file|mimes:jpeg,png,gif,ico,webp|max:10000',
          'images' => 'nullable|array|max:4',
        ];


        return match ($this->articleable_type) {
            Service::class => array_merge($commonRules, [
                'articleable_id' => 'required|exists:services,id',
            ]),
            default => array_merge($commonRules, [
                'articleable_id' => 'required|integer'
            ]),
        };
    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('article', [
            'title_fr','title_en','title_ar','content_fr','content_en','content_ar','user_id','published_at','images'
        ]);
    }

    /**
     * Save the banking information.
     */
    public function save()
    {

        try {
            $data = $this->validate();
            return DB::transaction(function () use ($data) {
                // Create user
              $article=Article::create($data);
               if ($this->images) {
                $this->uploadAndCreateImages($this->images, $article->id, Article::class, "article");
            }
                return $this->response(true, message: __("forms.user.responses.add_success"));
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('User creation error: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
