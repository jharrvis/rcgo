<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $categories = Article::published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $articles = Article::published()
            ->with('author')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('excerpt', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%');
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->input('category'));
            })
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('articles.index', compact('articles', 'categories'));
    }

    public function show(Article $article)
    {
        // Route-model binding on slug alone is not enough: drafts and
        // scheduled articles must 404 on the public routes.
        $article = Article::published()->where('slug', $article->slug)->with('author')->firstOrFail();

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->when($article->category, function ($query) use ($article) {
                $query->where('category', $article->category);
            })
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        if ($related->count() < 3) {
            $related = $related->merge(
                Article::published()
                    ->where('id', '!=', $article->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->orderByDesc('published_at')
                    ->limit(3 - $related->count())
                    ->get()
            );
        }

        return view('articles.show', compact('article', 'related'));
    }
}
