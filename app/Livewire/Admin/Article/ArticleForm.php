<?php

namespace App\Livewire\Admin\Article;

use App\Models\Article;
use App\Services\ArticleMarkdown;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class ArticleForm extends Component
{
    use WithFileUploads;

    public $articleId = null;

    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $featured_image = ''; // Existing stored path
    public $featured_image_alt = '';
    public $category = '';
    public $tagsInput = '';
    public $status = 'draft';
    public $is_featured = false;
    public $published_at = '';
    public $seo_title = '';
    public $seo_description = '';

    public $image; // Temporary upload
    public $removeImage = false;
    public $slugEdited = false;

    public function mount($article = null)
    {
        if (! $article) {
            return;
        }

        // Route binding gives a model (by slug); a raw id or slug string
        // is also accepted so the form never opens empty on a valid article.
        if ($article instanceof Article) {
            $model = $article;
        } elseif (is_numeric($article)) {
            $model = Article::withTrashed()->find($article);
        } else {
            $model = Article::withTrashed()->where('slug', $article)->first();
        }
        if (! $model || ! $model->exists) {
            return;
        }
        // An existing article already has its slug: editing the title must
        // not silently rewrite it (old public URLs must keep working).
        $this->slugEdited = true;

        $this->articleId = $model->id;
        $this->title = $model->title;
        $this->slug = $model->slug;
        $this->excerpt = $model->excerpt ?? '';
        $this->content = $model->content;
        $this->featured_image = $model->featured_image ?? '';
        $this->featured_image_alt = $model->featured_image_alt ?? '';
        $this->category = $model->category ?? '';
        $this->tagsInput = is_array($model->tags) ? implode(', ', $model->tags) : '';
        $this->status = $model->status;
        $this->is_featured = (bool) $model->is_featured;
        $this->published_at = $model->published_at ? $model->published_at->format('Y-m-d\TH:i') : '';
        $this->seo_title = $model->seo_title ?? '';
        $this->seo_description = $model->seo_description ?? '';
    }

    public function updatedTitle()
    {
        if (! $this->slugEdited) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function updatedSlug()
    {
        $this->slugEdited = true;
        $this->slug = Str::slug($this->slug);
    }

    public function getRenderedContentProperty(): string
    {
        return ArticleMarkdown::render($this->content);
    }

    #[Layout('layouts.dashboard')]
    #[Title('Form Artikel')]
    public function render()
    {
        return view('livewire.admin.article.article-form');
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'featured_image_alt' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'tagsInput' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $article = $this->articleId ? Article::withTrashed()->find($this->articleId) : new Article();
        $oldImage = $article->featured_image ?? null;

        $slug = $this->slug ? Str::slug($this->slug) : '';
        if ($slug === '') {
            $slug = Article::generateUniqueSlug($this->title, $this->articleId);
        } else {
            // Keep an explicitly edited slug, but never collide with another article.
            $existing = Article::withTrashed()->where('slug', $slug)
                ->when($this->articleId, fn ($q) => $q->where('id', '!=', $this->articleId))
                ->exists();
            if ($existing) {
                $slug = Article::generateUniqueSlug($this->title, $this->articleId);
            }
        }

        // Featured image: store the new upload first, delete the old one
        // only after the article saves, so a failed save never loses it.
        $newImage = $oldImage;
        if ($this->image) {
            $year = now()->format('Y');
            $month = now()->format('m');
            $newImage = $this->image->store("articles/{$year}/{$month}", 'public');
        } elseif ($this->removeImage) {
            $newImage = null;
        }

        $tags = [];
        if (trim($this->tagsInput) !== '') {
            $tags = array_values(array_filter(array_map('trim', explode(',', $this->tagsInput))));
        }

        $article->fill([
            'title' => $this->title,
            'slug' => $slug,
            'excerpt' => $this->excerpt ?: null,
            'content' => $this->content,
            'featured_image' => $newImage,
            'featured_image_alt' => $this->featured_image_alt ?: null,
            'category' => $this->category ?: null,
            'tags' => $tags,
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'published_at' => $this->published_at ?: null,
            'seo_title' => $this->seo_title ?: null,
            'seo_description' => $this->seo_description ?: null,
        ]);

        // author_id is never taken from the browser: it is always the
        // admin who is currently logged in.
        if (! $article->exists) {
            $article->author_id = Auth::id();
        }

        $article->save();

        if ($oldImage && $oldImage !== $newImage) {
            Storage::disk('public')->delete($oldImage);
        }

        session()->flash('message', 'Artikel berhasil disimpan.');
        return redirect()->route('admin.articles.index');
    }
}
