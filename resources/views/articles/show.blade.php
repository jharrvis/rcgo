@php
    $isPreview = $isPreview ?? false;
    $metaTitle = $article->seo_title ?: $article->title;
    $metaDescription = $article->seo_description ?: ($article->excerpt ?: $article->title);
    $canonical = $isPreview ? null : route('articles.show', $article);
    $ogImage = $article->featured_image_url ? url($article->featured_image_url) : null;
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle }} | RC GO</title>
    <meta name="description" content="{{ $metaDescription }}">
    @if ($isPreview)
        <meta name="robots" content="noindex, nofollow">
    @endif
    @if ($canonical)
        <link rel="canonical" href="{{ $canonical }}">
    @endif
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    @if ($canonical)
        <meta property="og:url" content="{{ $canonical }}">
    @endif
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if ($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif
    @if (! $isPreview)
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $metaDescription,
            'image' => $ogImage ? [$ogImage] : [],
            'datePublished' => $article->published_at ? $article->published_at->toIso8601String() : null,
            'dateModified' => $article->updated_at ? $article->updated_at->toIso8601String() : null,
            'author' => ['@type' => 'Person', 'name' => $article->author->name ?? 'RC GO'],
            'publisher' => ['@type' => 'Organization', 'name' => 'RC GO', 'url' => url('/')],
            'mainEntityOfPage' => $canonical,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @include('partials.landing.styles')
    <style>
        .article-body { color: #d1d5db; font-size: 1.05rem; line-height: 1.7; max-width: 72ch; }
        .article-body h1, .article-body h2, .article-body h3, .article-body h4 { color: #fff; font-weight: 700; line-height: 1.3; margin: 1.6em 0 0.6em; }
        .article-body h1 { font-size: 1.9rem; }
        .article-body h2 { font-size: 1.6rem; }
        .article-body h3 { font-size: 1.3rem; }
        .article-body h4 { font-size: 1.1rem; }
        .article-body p { margin: 0 0 1.1em; }
        .article-body a { color: #fb923c; text-decoration: underline; }
        .article-body a:focus { outline: 2px solid #fb923c; outline-offset: 2px; }
        .article-body ul, .article-body ol { margin: 0 0 1.1em 1.4em; }
        .article-body li { margin-bottom: 0.4em; }
        .article-body img { border-radius: 1rem; margin: 1.2em 0; max-width: 100%; height: auto; }
        .article-body blockquote { border-left: 4px solid #f97316; padding-left: 1rem; color: #9ca3af; margin: 1.2em 0; }
        .article-body code { background: #1f2937; padding: 0.15em 0.4em; border-radius: 0.375rem; font-size: 0.9em; }
        .article-body pre { background: #1f2937; padding: 1rem; border-radius: 0.75rem; overflow-x: auto; margin: 1.2em 0; }
        .article-body pre code { background: transparent; padding: 0; }
        .article-body hr { border-color: #374151; margin: 1.6em 0; }
        .article-body table { width: 100%; border-collapse: collapse; margin: 1.2em 0; }
        .article-body th, .article-body td { border: 1px solid #374151; padding: 0.6em 0.8em; text-align: left; }
    </style>
</head>

<body class="bg-black text-white selection:bg-orange-500 selection:text-white">

    @include('partials.landing.nav')

    <main>
        @if ($isPreview)
            <div class="bg-yellow-500 text-black text-center text-sm font-bold py-3 px-4">
                Pratinjau Admin — artikel ini {{ $article->status_label }} dan belum tentu tampil publik.
            </div>
        @endif

        <article class="py-12 md:py-16">
            <div class="max-w-7xl mx-auto px-4">
                <nav class="text-sm text-gray-400 mb-6" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="hover:text-orange-400">Beranda</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('articles.index') }}" class="hover:text-orange-400">Artikel</a>
                    @if ($article->category)
                        <span class="mx-2">/</span>
                        <a href="{{ route('articles.index', ['category' => $article->category]) }}" class="hover:text-orange-400">{{ $article->category }}</a>
                    @endif
                </nav>

                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 max-w-4xl">{{ $article->title }}</h1>
                <p class="text-gray-400 text-base mb-8">
                    @if ($article->category)
                        {{ $article->category }} &middot;
                    @endif
                    {{ $article->published_at ? $article->published_at->format('d F Y') : '' }}
                    @if ($article->author)
                        &middot; oleh {{ $article->author->name }}
                    @endif
                </p>

                @if ($article->featured_image_url)
                    <img src="{{ $article->featured_image_url }}" alt="{{ $article->featured_image_alt ?? $article->title }}"
                        class="w-full max-w-5xl aspect-[2/1] object-cover bg-gray-900 rounded-3xl mb-10">
                @endif

                <div class="article-body">
                    {!! \App\Services\ArticleMarkdown::render($article->content) !!}
                </div>

                <div class="mt-12 bg-gray-900 border border-gray-800 rounded-3xl p-8 text-center max-w-4xl">
                    <h2 class="text-2xl font-bold text-white mb-3">Siap Memulai Usaha RC Go?</h2>
                    <p class="text-gray-400 mb-6">Lihat paket usaha yang tersedia dan mulai dari yang paling sesuai dengan modal Anda.</p>
                    <a href="{{ route('home') }}#paket"
                        class="inline-block px-8 py-4 bg-orange-500 text-white rounded-full font-bold hover:bg-orange-600 transition-all min-h-[44px]">Lihat Paket Usaha</a>
                </div>

                <div class="mt-10">
                    <a href="{{ route('articles.index') }}" class="text-orange-400 font-bold hover:text-orange-300">&larr; Kembali ke Daftar Artikel</a>
                </div>
            </div>
        </article>

        @if ($related->count() > 0)
            <section class="py-12 border-t border-gray-800">
                <div class="max-w-7xl mx-auto px-4">
                    <h2 class="text-2xl font-bold text-white mb-8">Artikel Terkait</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach ($related as $relatedArticle)
                            <a href="{{ route('articles.show', $relatedArticle) }}"
                                class="block bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden hover:border-orange-500/50 transition-all">
                                @if ($relatedArticle->featured_image_url)
                                    <img src="{{ $relatedArticle->featured_image_url }}" alt="{{ $relatedArticle->featured_image_alt ?? $relatedArticle->title }}" class="w-full aspect-[2/1] object-cover bg-gray-900">
                                @endif
                                <div class="p-6">
                                    <p class="text-xs text-orange-400 font-bold uppercase tracking-wider mb-2">
                                        {{ $relatedArticle->category ?? 'Artikel' }} &middot; {{ $relatedArticle->published_at ? $relatedArticle->published_at->format('d M Y') : '' }}
                                    </p>
                                    <h3 class="text-lg font-bold text-white" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $relatedArticle->title }}</h3>
                                    <p class="text-orange-400 text-sm font-bold mt-4">Baca Selengkapnya &rarr;</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    @include('partials.landing.footer')

    <script>
        window.CSAIConfig = {
            widgetId: 'widget-3-ZYcwZDAW'
        };
    </script>
    <script src="https://cekat.biz.id/widget/widget.min.js" async></script>
</body>

</html>
