<?php

namespace App\Livewire\App;

use App\Models\Article;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ArticlesPagesViewer extends Component
{
    use WithPagination;

    public ?int $articleableId = null;
    public ?string $articleableType = null;

    #[Computed]
    public function articles()
    {

        return Article::with('images')
            ->when($this->articleableId, function ($query, $value) {
                return $query->where('articleable_id', $value);
            })
            ->when($this->articleableType, function ($query, $value) {
                return $query->where('articleable_type', $value);
            })

            ->where('state', 'published')
            ->where('published_at', '<=', now())
            ->latest()
            ->paginate(5);
    }

    public function render()
    {
        return view('livewire.app.articles-pages-viewer');
    }
}
