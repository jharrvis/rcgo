<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Package;
use App\Models\Addon;
use App\Models\LandingPageContent;

class LandingPageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        $addons = Addon::all();
        // Latest articles for the landing page: featured first, then newest
        // published, max 3. Drafts and scheduled articles never appear here.
        $latestArticles = Article::published()
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();
        return view('welcome', compact('packages', 'addons', 'latestArticles'));
    }
}
