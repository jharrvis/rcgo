<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">
                {{ $articleId ? 'Edit Artikel' : 'Buat Artikel Baru' }}
            </h1>
            <p class="text-slate-500 text-sm mt-1">Isi artikel ditulis dalam Markdown. Pratinjau di bawah mengikuti tampilan publik.</p>
        </div>
        <div class="flex gap-2">
            @if ($articleId)
                <a href="{{ route('admin.articles.preview', $articleId) }}" target="_blank"
                    class="px-4 py-2 bg-white border border-slate-200 dark:border-slate-800 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-all flex items-center gap-2">
                    <i class="fas fa-eye"></i> Pratinjau Halaman
                </a>
            @endif
            <a href="{{ route('admin.articles.index') }}"
                class="px-4 py-2 bg-white border border-slate-200 dark:border-slate-800 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-all flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 md:p-8">
        <form wire:submit="save" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Judul *</label>
                <input wire:model.live="title" type="text"
                    class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                    placeholder="Contoh: 5 Tips Memulai Usaha Sewa Mobil RC">
                @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Slug</label>
                    <input wire:model.live="slug" type="text"
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                        placeholder="Otomatis dari judul, bisa diedit">
                    <p class="text-xs text-slate-500 mt-1">Kosongkan untuk slug otomatis. Slug yang sudah dipakai akan dibuat unik otomatis.</p>
                    @error('slug') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Kategori</label>
                    <input wire:model="category" type="text"
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                        placeholder="Contoh: Tips Bisnis">
                    @error('category') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Ringkasan (Excerpt)</label>
                <textarea wire:model="excerpt" rows="3"
                    class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                    placeholder="Ringkasan singkat untuk kartu artikel dan meta description."></textarea>
                @error('excerpt') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Isi Artikel (Markdown) *</label>
                <textarea wire:model.live.debounce.500ms="content" rows="14"
                    class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none transition-all font-mono text-sm"
                    placeholder="# Judul Bagian&#10;&#10;Tulis isi artikel dengan Markdown. HTML mentah akan di-escape, link tidak aman (javascript:) akan dihapus."></textarea>
                @error('content') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Pratinjau Isi</label>
                <div class="prose prose-slate max-w-none bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-6 min-h-[120px]">
                    @if (trim($content) !== '')
                        {!! $this->renderedContent !!}
                    @else
                        <p class="text-slate-400 text-sm">Pratinjau akan muncul di sini saat isi artikel ditulis.</p>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Gambar Unggulan</label>
                <div class="flex items-start gap-4">
                    <div
                        class="w-48 aspect-[16/9] bg-slate-100 dark:bg-slate-800 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 flex items-center justify-center relative">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-contain">
                        @elseif ($featured_image && ! $removeImage)
                            <img src="{{ str_starts_with($featured_image, '/') ? $featured_image : '/storage/' . $featured_image }}" class="w-full h-full object-contain">
                        @else
                            <i class="fas fa-image text-3xl text-slate-300"></i>
                        @endif
                        <div wire:loading wire:target="image"
                            class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <i class="fas fa-spinner fa-spin text-white"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <input wire:model="image" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-xl file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-brand-50 file:text-brand-700
                                      hover:file:bg-brand-100
                                      transition-all">
                        <p class="text-xs text-slate-500 mt-2">Format: JPG, PNG, WebP. Maks: 2MB. Disimpan di storage/app/public/articles/Tahun/Bulan.</p>
                        @if ($featured_image && ! $removeImage)
                            <label class="mt-2 inline-flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" wire:model="removeImage" class="rounded"> Hapus gambar lama saat disimpan
                            </label>
                        @endif
                        @error('image') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Alt Text Gambar</label>
                    <input wire:model="featured_image_alt" type="text"
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                        placeholder="Deskripsi gambar untuk aksesibilitas dan SEO">
                    @error('featured_image_alt') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tag (pisahkan dengan koma)</label>
                    <input wire:model="tagsInput" type="text"
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                        placeholder="usaha, mobil rc, tips">
                    @error('tagsInput') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status *</label>
                    <select wire:model="status"
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="draft">Draft</option>
                        <option value="published">Terbit</option>
                    </select>
                    <p class="text-xs text-slate-500 mt-1">Artikel berstatus Terbit dengan tanggal publikasi di masa depan dianggap terjadwal dan belum tampil publik.</p>
                    @error('status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tanggal &amp; Waktu Publikasi</label>
                    <input wire:model="published_at" type="datetime-local"
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                    @error('published_at') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-end pb-2">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                        <input type="checkbox" wire:model="is_featured" class="rounded"> Artikel Unggulan (diprioritaskan di landing &amp; daftar)
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">SEO Title (sekitar 60 karakter)</label>
                    <input wire:model="seo_title" type="text"
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                    <p class="text-xs text-slate-500 mt-1">{{ strlen($seo_title) }} karakter. Kosongkan untuk memakai judul artikel.</p>
                    @error('seo_title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">SEO Description (sekitar 160 karakter)</label>
                    <textarea wire:model="seo_description" rows="3"
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none transition-all"></textarea>
                    <p class="text-xs text-slate-500 mt-1">{{ strlen($seo_description) }} karakter. Kosongkan untuk memakai excerpt.</p>
                    @error('seo_description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2.5 bg-brand-500 text-white rounded-xl font-bold hover:bg-brand-600 transition-all shadow-lg shadow-brand-500/20 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px]">
                    <span wire:loading.remove><i class="fas fa-save"></i> Simpan Artikel</span>
                    <span wire:loading><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
