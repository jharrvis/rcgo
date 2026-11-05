<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;

class ArticlePreviewController extends Controller
{
    /**
     * Admin-only preview of any article, including drafts and scheduled
     * ones. The view adds a noindex robots tag and a draft banner, so a
     * preview URL can never be indexed as a published article.
     */
    public function __invoke(Article $article)
    {
        $article->load('author');
        $related = collect();

        return view('articles.show', [
            'article' => $article,
            'related' => $related,
            'isPreview' => true,
        ]);
    }
}
