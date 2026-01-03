<?php

namespace App\Livewire\App\Cards;

use App\Models\Article as ModelsArticle;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Article extends Component
{
    public ModelsArticle $article;
    public ?int $articleId= null;
    public bool $less = false;

    public function mount(): void
    {
        if($this->articleId){
        $this->article = ModelsArticle::with('images')->findOrFail($this->articleId);
        }
    }


    public function formatLocalizedDate(string|\DateTimeInterface $date, string $format = 'j F Y'): string
    {
        return Carbon::parse($date)
            ->locale(app()->getLocale())
            ->translatedFormat($format);
    }

    public function toggleLess(): void
    {
        $this->less = ! $this->less;
    }

    public function render()
    {
        return view('livewire.app.cards.article');
    }
}
