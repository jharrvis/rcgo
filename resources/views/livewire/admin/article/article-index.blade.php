<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Manajemen Artikel</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola artikel untuk edukasi calon mitra dan SEO.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}"
            class="px-4 py-2 bg-brand-500 text-white rounded-xl text-sm font-bold hover:bg-brand-600 transition-all shadow-lg shadow-brand-500/20 flex items-center gap-2">
            <i class="fas fa-plus"></i> Buat Artikel
        </a>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('message') }}
        </div>
    @endif

    <div
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
        <div
            class="p-4 border-b border-slate-100 dark:border-slate-800 flex flex-col lg:flex-row lg:items-center gap-4">
            <div class="relative w-full lg:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fas fa-search"></i>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul, kategori, author..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none transition-all">
            </div>
            <select wire:model.live="statusFilter"
                class="px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none">
                <option value="all">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="scheduled">Terjadwal</option>
                <option value="published">Terbit</option>
                <option value="trashed">Dihapus</option>
            </select>
            <select wire:model.live="categoryFilter"
                class="px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
            <select wire:model.live="sortBy"
                class="px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none">
                <option value="published_at">Urut: Tanggal Publikasi</option>
                <option value="created_at">Urut: Tanggal Dibuat</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Artikel</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Publikasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($articles as $article)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-slate-200 dark:bg-slate-700 overflow-hidden shrink-0">
                                        @if ($article->featured_image_url)
                                            <img src="{{ $article->featured_image_url }}"
                                                class="w-full h-full object-cover" alt="">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <i class="fas fa-newspaper"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm">{{ $article->title }}</p>
                                        <p class="text-xs text-slate-500 truncate max-w-[240px]">
                                            {{ $article->author->name ?? '—' }} &middot; {{ $article->slug }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $article->category ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 rounded-full text-xs font-bold {{ $article->status_label === 'Terbit' ? 'bg-green-100 text-green-700' : ($article->status_label === 'Terjadwal' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $article->status_label }}
                                </span>
                                @if ($article->is_featured)
                                    <span class="ml-1 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">Unggulan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ $article->published_at ? $article->published_at->format('d M Y H:i') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($article->trashed())
                                        <button wire:click="restore({{ $article->id }})"
                                            class="p-2 text-slate-400 hover:text-green-500 transition-colors" title="Pulihkan">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <button wire:click="forceDelete({{ $article->id }})"
                                            wire:confirm="Hapus permanen? Gambar unggulan ikut terhapus dan tidak bisa dipulihkan."
                                            class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Hapus permanen">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.articles.preview', $article->id) }}" target="_blank"
                                            class="p-2 text-slate-400 hover:text-brand-500 transition-colors" title="Pratinjau">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.articles.edit', $article) }}"
                                            class="p-2 text-slate-400 hover:text-brand-500 transition-colors" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button wire:click="togglePublish({{ $article->id }})"
                                            class="p-2 text-slate-400 hover:text-brand-500 transition-colors"
                                            title="{{ $article->status === 'published' ? 'Jadikan draft' : 'Terbitkan' }}">
                                            <i class="fas {{ $article->status === 'published' ? 'fa-eye-slash' : 'fa-paper-plane' }}"></i>
                                        </button>
                                        <button wire:click="delete({{ $article->id }})"
                                            wire:confirm="Yakin ingin menghapus artikel ini? Artikel masuk ke filter Dihapus dan bisa dipulihkan."
                                            class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-inbox text-4xl text-slate-300"></i>
                                    <p>Tidak ada artikel ditemukan.</p>
                                    <a href="{{ route('admin.articles.create') }}"
                                        class="mt-2 px-4 py-2 bg-brand-500 text-white rounded-xl text-sm font-bold">Buat Artikel</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
            {{ $articles->links() }}
        </div>
    </div>
</div>
