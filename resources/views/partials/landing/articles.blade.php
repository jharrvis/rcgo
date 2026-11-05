@if (isset($latestArticles) && $latestArticles->count() > 0)
    <section id="artikel" class="py-16 md:py-24 bg-black border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Tips &amp; Insight Bisnis RC</h2>
                <p class="text-gray-400 text-base md:text-lg max-w-2xl mx-auto">Edukasi singkat untuk calon mitra: memulai, mengelola, dan mempromosikan usaha sewa mobil RC.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($latestArticles as $article)
                    <a href="{{ route('articles.show', $article) }}"
                        class="block bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden hover:border-orange-500/50 transition-all">
                        @if ($article->featured_image_url)
                            <img src="{{ $article->featured_image_url }}" alt="{{ $article->featured_image_alt ?? $article->title }}" class="w-full aspect-[2/1] object-contain bg-gray-900">
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

            <div class="text-center mt-10">
                <a href="{{ route('articles.index') }}"
                    class="inline-block px-8 py-4 border-2 border-orange-500 text-orange-500 rounded-full font-bold hover:bg-orange-500 hover:text-white transition-all min-h-[44px]">Lihat Semua Artikel</a>
            </div>
        </div>
    </section>
@endif
