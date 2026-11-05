<?php

namespace App\Livewire;

use App\Models\Addon;
use App\Models\Article;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $search = '';
    public $results = [];

    public function updatedSearch()
    {
        $this->results = [];

        if (strlen($this->search) < 2) {
            return;
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            $this->searchAdmin();
        } else {
            $this->searchMitra();
        }
    }

    public function searchMitra()
    {
        $userId = Auth::id();

        // Search Orders
        $orders = Order::where('user_id', $userId)
            ->where(function ($q) {
                // Search by Order ID
                $q->where('id', 'like', '%' . $this->search . '%')
                    // Or by Invoice ID
                    ->orWhere('xendit_invoice_id', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->limit(5)
            ->get();

        foreach ($orders as $order) {
            $this->results[] = [
                'type' => 'Pesanan',
                'title' => 'Order #' . $order->id,
                'subtitle' => $order->status . ' - ' . ($order->xendit_invoice_id ?? 'No Invoice'),
                'url' => route('mitra.orders.show', $order->id),
                'icon' => 'fas fa-shopping-cart'
            ];
        }
    }

    public function searchAdmin()
    {
        // Search Orders
        $orders = Order::with('user')
            ->where('id', 'like', '%' . $this->search . '%')
            ->orWhere('xendit_invoice_id', 'like', '%' . $this->search . '%')
            ->orWhereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->limit(3)
            ->get();

        foreach ($orders as $order) {
            $this->results[] = [
                'type' => 'Pesanan',
                'title' => 'Order #' . $order->id . ' - ' . ($order->user->name ?? 'Unknown'),
                'subtitle' => $order->status,
                'url' => route('admin.orders.show', $order->id), // Assuming route exists
                'icon' => 'fas fa-shopping-cart'
            ];
        }

        // Search Users
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->limit(3)
            ->get();

        foreach ($users as $user) {
            $this->results[] = [
                'type' => 'Pengguna',
                'title' => $user->name,
                'subtitle' => $user->email . ' (' . $user->role . ')',
                'url' => route('admin.users.index', ['search' => $user->email]), // Search via index as redirect usually
                'icon' => 'fas fa-user'
            ];
        }

        // Search Packages
        $packages = Package::where('name', 'like', '%' . $this->search . '%')
            ->limit(2)
            ->get();

        foreach ($packages as $pkg) {
            $this->results[] = [
                'type' => 'Produk (Paket)',
                'title' => $pkg->name,
                'subtitle' => 'Rp ' . number_format($pkg->price),
                // Assuming edit route, or index with search
                'url' => route('admin.packages.edit', $pkg->id),
                'icon' => 'fas fa-box'
            ];
        }

        // Search Addons
        $addons = Addon::where('name', 'like', '%' . $this->search . '%')
            ->limit(2)
            ->get();

        foreach ($addons as $addon) {
            $this->results[] = [
                'type' => 'Produk (Addon)',
                'title' => $addon->name,
                'subtitle' => 'Rp ' . number_format($addon->price),
                'url' => route('admin.addons.edit', $addon->id),
                'icon' => 'fas fa-puzzle-piece'
            ];
        }

        // Search Articles
        $articles = Article::where('title', 'like', '%' . $this->search . '%')
            ->limit(3)
            ->get();

        foreach ($articles as $article) {
            $this->results[] = [
                'type' => 'Artikel',
                'title' => $article->title,
                'subtitle' => ($article->category ?? 'Artikel') . ' - ' . $article->status_label,
                'url' => route('admin.articles.edit', $article),
                'icon' => 'fas fa-newspaper'
            ];
        }
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
