<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Seeds one example published article. Run manually only:
     * php artisan db:seed --class=ArticleSeeder
     * It is NOT registered in DatabaseSeeder, so it never runs by accident.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        if (! $admin) {
            return;
        }

        Article::firstOrCreate(
            ['slug' => 'tips-memulai-usaha-sewa-mobil-rc'],
            [
                'author_id' => $admin->id,
                'title' => 'Tips Memulai Usaha Sewa Mobil RC',
                'excerpt' => 'Langkah awal memulai usaha sewa mobil RC: pilih lokasi, siapkan unit, dan promosikan arena Anda.',
                'content' => "# Tips Memulai Usaha Sewa Mobil RC\n\nMulailah dari lokasi yang ramai anak dan keluarga, siapkan unit yang terawat, dan buat aturan sewa yang jelas.\n\n## Promosi\n\nDokumentasikan keseruan penyewa dan bagikan secara rutin agar arena Anda dikenal di sekitar lokasi.",
                'category' => 'Tips Bisnis',
                'tags' => ['usaha', 'mobil rc', 'tips'],
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now(),
            ]
        );
    }
}
