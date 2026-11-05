<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel &amp; Tips Bisnis RC | RC GO</title>
    <meta name="description" content="Artikel dan tips bisnis RC GO: edukasi calon mitra, strategi usaha sewa mobil RC, dan insight bisnis.">
    <link rel="canonical" href="{{ route('articles.index') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @include('partials.landing.styles')
</head>

<body class="bg-black text-white selection:bg-orange-500 selection:text-white">

    @include('partials.landing.nav')

    <main>
        <section class="py-16 md:py-24 bg-gray-900 border-b border-gray-800">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">Artikel &amp; Tips Bisnis RC</h1>
                <p class="text-gray-400 text-base md:text-lg max-w-2xl mx-auto">Edukasi dan insight untuk calon mitra RC GO: memulai usaha, mengelola arena, dan mempromosikan bisnis sewa mobil RC.</p>
            </div>
        </section>

        @if ($featured)
            <section class="py-12 md:py-16">
                <div class="max-w-7xl mx-auto px-4">
                    <a href="{{ route('articles.show', $featured) }}" class="block bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden hover:border-orange-500/50 transition-all">
                        @if ($featured->featured_image_url)
                            <img src="{{ $featured->featured_image_url }}" alt="{{ $featured->featured_image_alt ?? $featured->title }}" class="w-full aspect-[16/9] object-contain bg-gray-900">
                        @endif
                        <div class="p-6 md:p-8">
                            <p class="text-xs text-orange-400 font-bold uppercase tracking-wider mb-2">Artikel Unggulan &middot; {{ $featured->category ?? 'Artikel' }}</p>
                            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">{{ $featured->title }}</h2>
                            @if ($featured->excerpt)
                                <p class="text-gray-400 leading-relaxed">{{ $featured->excerpt }}</p>
                            @endif
                            <p class="text-gray-500 text-sm mt-4">{{ $featured->published_at ? $featured->published_at->format('d F Y') : '' }}</p>
                        </div>
                    </a>
                </div>
            </section>
        @endif

        <section class="py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4">
                <form method="GET" action="{{ route('articles.index') }}" class="flex flex-col md:flex-row gap-4 mb-10">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel..."
                        class="flex-1 px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white text-base outline-none focus:border-orange-500">
                    <select name="category"
                        class="px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white text-base outline-none focus:border-orange-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="px-6 py-3 bg-orange-500 text-white rounded-xl font-bold hover:bg-orange-600 transition-all min-h-[44px]">Cari</button>
                </form>

                @if ($articles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($articles as $article)
                            <a href="{{ route('articles.show', $article) }}"
                                class="block bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden hover:border-orange-500/50 transition-all">
                                @if ($article->featured_image_url)
                                    <img src="{{ $article->featured_image_url }}" alt="{{ $article->featured_image_alt ?? $article->title }}" class="w-full aspect-[16/9] object-contain bg-gray-900">
                                @endif
                                <div class="p-6">
                                    <p class="text-xs text-orange-400 font-bold uppercase tracking-wider mb-2">
                                        {{ $article->category ?? 'Artikel' }} &middot; {{ $article->published_at ? $article->published_at->format('d M Y') : '' }}
                                    </p>
                                    <h3 class="text-lg font-bold text-white mb-2" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $article->title }}</h3>
                                    @if ($article->excerpt)
                                        <p class="text-gray-400 text-sm leading-relaxed" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">{{ $article->excerpt }}</p>
                                    @endif
                                    <p class="text-orange-400 text-sm font-bold mt-4">Baca Selengkapnya &rarr;</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    {{ $articles->links('vendor.pagination.rcgo') }}
                @else
                    <div class="text-center py-16">
                        <p class="text-gray-400 text-lg">Belum ada artikel yang cocok.</p>
                        <a href="{{ route('articles.index') }}" class="text-orange-400 font-bold mt-4 inline-block">Lihat semua artikel</a>
                    </div>
                @endif
            </div>
        </section>
    </main>

    @include('partials.landing.footer')

    <!-- Cekat.biz.id Chatbot Widget -->
    <script>
        window.CSAIConfig = {
            widgetId: 'widget-3-ZYcwZDAW'
        };
    </script>
    <script src="https://cekat.biz.id/widget/widget.min.js" async></script>
</body>

</html>
