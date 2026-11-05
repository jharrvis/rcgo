<?php

namespace App\Livewire\Admin\Article;

use App\Models\Article;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleIndex extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $statusFilter = 'all';

    #[Url]
    public $categoryFilter = '';

    #[Url]
    public $sortBy = 'published_at';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    #[Layout('layouts.dashboard')]
    #[Title('Artikel | RCGO Admin')]
    public function render()
    {
        $categories = Article::withTrashed()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $query = Article::with('author')
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                        ->orWhere('category', 'like', '%' . $this->search . '%')
                        ->orWhereHas('author', function ($author) {
                            $author->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->categoryFilter, function ($q) {
                $q->where('category', $this->categoryFilter);
            });

        if ($this->statusFilter === 'draft') {
            $query->where('status', 'draft');
        } elseif ($this->statusFilter === 'scheduled') {
            $query->scheduled();
        } elseif ($this->statusFilter === 'published') {
            $query->published();
        } elseif ($this->statusFilter === 'trashed') {
            $query->onlyTrashed();
        }

        $sortColumn = in_array($this->sortBy, ['created_at', 'published_at'], true)
            ? $this->sortBy
            : 'published_at';

        $articles = $query->orderByDesc($sortColumn)->orderByDesc('id')->paginate(10);

        return view('livewire.admin.article.article-index', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    public function togglePublish($id)
    {
        $article = Article::findOrFail($id);
        $article->status = $article->status === 'published' ? 'draft' : 'published';
        if ($article->status === 'published' && ! $article->published_at) {
            $article->published_at = now();
        }
        $article->save();
        session()->flash('message', 'Status artikel berhasil diubah.');
    }

    public function delete($id)
    {
        // Soft delete: the article disappears from public pages and the
        // index, but can be restored from the "Dihapus" filter.
        Article::findOrFail($id)->delete();
        session()->flash('message', 'Artikel berhasil dihapus (dapat dipulihkan dari filter Dihapus).');
    }

    public function restore($id)
    {
        Article::onlyTrashed()->findOrFail($id)->restore();
        session()->flash('message', 'Artikel berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $article = Article::onlyTrashed()->findOrFail($id);
        if ($article->featured_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($article->featured_image);
        }
        $article->forceDelete();
        session()->flash('message', 'Artikel berhasil dihapus permanen.');
    }
}
