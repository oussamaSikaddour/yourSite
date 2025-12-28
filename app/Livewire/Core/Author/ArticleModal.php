<?php

namespace App\Livewire\Core\Author;

use App\Livewire\Forms\Core\Article\AddForm;
use App\Livewire\Forms\Core\Article\UpdateForm;
use App\Models\Article;
use App\Traits\Core\Common\DateAndTimeTrait;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ArticleModal extends Component
{
    use WithFileUploads, GeneralTrait,DateAndTimeTrait;

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public Article $article;
    public $id;
    public $form = 'addForm';
    public $contentFr = '';
    public $contentAr = '';
    public $contentEn = '';
    public $local = 'fr';
    public $articleableId =null;
    public $articleableType= null;
    public $temporaryImageUrls = [];

    #[Computed]
    public function formEntity()
    {
        return $this->id ? $this->updateForm : $this->addForm;
    }





    private function updateTemporaryImagesUrl()
    {
        $this->temporaryImageUrls = [];

        foreach ($this->formEntity->images as $image) {
            if (!$image->temporaryUrl()) {
                $this->temporaryImageUrls = [];
                break;
            }

            $this->temporaryImageUrls[] = $image->temporaryUrl();
        }
    }

    protected function loadArticleData()
    {
        $this->article = Article::with(['images' => function ($query) {
            $query->where('use_case', 'article');
        }])->findOrFail($this->id);

        $this->loadImages();
        $this->fillUpdateForm();
    }

    protected function loadImages()
    {
        $this->temporaryImageUrls = $this->article->images->pluck('url')->filter()->all();
    }

    protected function fillUpdateForm()
    {
        $this->contentFr = $this->article->content_fr;
        $this->contentAr = $this->article->content_ar;
        $this->contentEn = $this->article->content_en;


        $this->updateForm->fill([
            'id' => $this->id,
            'user_id' => auth()->id(),
            'title_ar' => $this->article->title_ar,
            'title_fr' => $this->article->title_fr,
            'title_en' => $this->article->title_en,
            'content_ar' => $this->article->content_ar,
            'content_fr' => $this->article->content_fr,
            'content_en' => $this->article->content_en,
            'type' => $this->article->type,
            'published_at' =>$this->parseDate($this->article->published_at),
            'articleable_type' => $this->article->articleable_type,
            'articleable_id' => $this->article->articleable_id,
            'state' => $this->article->state,
        ]);
    }





    #[On('set-content-fr')]
    public function setContentFr($content)
    {
        $this->formEntity->fill(['content_fr' => $content]);
    }

    #[On('set-content-en')]
    public function setContentEn($content)
    {
        $this->formEntity->fill(['content_en' => $content]);
    }

    #[On('set-content-ar')]
    public function setContentAr($content)
    {
        $this->formEntity->fill(['content_ar' => $content]);
    }

    public function handleSubmit()
    {

        $response = $this->id
            ? $this->updateForm->save($this->article)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-articles-table');
            $this->dispatch('open-toast', $response['message']);

            if (!$this->id) {
                $this->addForm->reset();
                $this->temporaryImageUrls = [];
            }
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

        public function mount()
    {
        $this->local = app()->getLocale();



        $this->dispatch('initialize-tiny-mce');

        if ($this->id) {
            $this->form = 'updateForm';
            try {
                $this->loadArticleData();
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                Log::error('Error in ArticleModal mount:', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                    'article_id' => $this->id,
                ]);
                $this->dispatch('open-errors', __('forms.common.errors.default'));
            }
        } else {
            $this->addForm->fill([
                'user_id' => auth()->id(),
                'articleable_id'=>$this->articleableId,
                'articleable_type'=>$this->articleableType,
            ]);
        }
    }

        public function updated($property)
    {
        if (in_array($property, ['addForm.articleable_type', 'updateForm.articleable_type'])) {

            $this->populateArticleableIdsSelector();
        }

        if (str_contains($property, 'images')) {
            try {
                $this->updateTemporaryImagesUrl();
            } catch (\Exception $e) {
                $this->dispatch('open-errors', __('forms.common.errors.img.not_img'));
            }
        }
    }
    public function render()
    {
        return view('livewire.core.author.article-modal');
    }
}
