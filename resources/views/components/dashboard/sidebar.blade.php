<aside
    class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 smooth-transition"
    :class="[
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
        isSidebarOpen ? 'w-64' : 'md:w-20 w-64'
    ]">
    <!-- Logo Area -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-slate-200 dark:border-slate-800">
        <a href="{{ route('home') }}" class="flex items-center gap-3 overflow-hidden group">
            <img src="{{ asset('assets/img/rcgo-logo.svg') }}" alt="RCGO" class="h-8 w-auto">
        </a>
        <button @click="toggleSidebar()" class="hidden md:block p-1 text-slate-400 hover:text-brand-500">
            <i class="fas" :class="isSidebarOpen ? 'fa-angle-left' : 'fa-angle-right'"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto custom-scrollbar">
        @if (Auth::user()->isAdmin())
            <!-- GROUP ADMIN -->
            <div>
                <p class="px-3 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider" x-show="isSidebarOpen">
                    Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('admin.dashboard') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                    <i class="fas fa-th-large w-6 text-center"></i>
                    <span class="ml-3 font-medium smooth-transition"
                        :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Dashboard</span>
                </a>
                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('admin.orders.*') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                    <i class="fas fa-shopping-cart w-6 text-center"></i>
                    <span class="ml-3 font-medium smooth-transition"
                        :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Pesanan</span>
                </a>
                <a href="{{ route('admin.partners.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('admin.partners.*') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span class="ml-3 font-medium smooth-transition"
                        :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Mitra</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('admin.users.*') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                    <i class="fas fa-user-shield w-6 text-center"></i>
                    <span class="ml-3 font-medium smooth-transition"
                        :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Pengguna</span>
                </a>
            </div>

            <!-- Group Produk -->
            <div class="mt-6" x-data="{ open: true }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider hover:text-slate-600 outline-none">
                    <span x-show="isSidebarOpen">Produk</span>
                    <i class="fas fa-chevron-down text-[10px] smooth-transition" :class="open ? '' : '-rotate-90'"
                        x-show="isSidebarOpen"></i>
                </button>
                <div x-show="open" x-transition class="space-y-1">
                    <a href="{{ route('admin.packages.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('admin.packages.index') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                        <i class="fas fa-box w-6 text-center"></i>
                        <span class="ml-3 font-medium smooth-transition"
                            :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Paket Usaha</span>
                    </a>
                    <a href="{{ route('admin.addons.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('admin.addons.index') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                        <i class="fas fa-puzzle-piece w-6 text-center"></i>
                        <span class="ml-3 font-medium smooth-transition"
                            :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Add-on</span>
                    </a>
                </div>
            </div>

            <!-- Group Konten -->
            <div class="mt-6" x-data="{ open: true }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider hover:text-slate-600 outline-none">
                    <span x-show="isSidebarOpen">Konten</span>
                    <i class="fas fa-chevron-down text-[10px] smooth-transition" :class="open ? '' : '-rotate-90'"
                        x-show="isSidebarOpen"></i>
                </button>
                <div x-show="open" x-transition class="space-y-1">
                    <a href="{{ route('admin.articles.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('admin.articles.*') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                        <i class="fas fa-newspaper w-6 text-center"></i>
                        <span class="ml-3 font-medium smooth-transition"
                            :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Artikel</span>
                    </a>
                </div>
            </div>

            <!-- Group Pengaturan -->
            <div class="mt-6">
                <p class="px-3 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider" x-show="isSidebarOpen">
                    Sistem</p>
                <a href="{{ route('admin.settings.shipping.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('admin.settings.shipping.*') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                    <i class="fas fa-truck w-6 text-center"></i>
                    <span class="ml-3 font-medium smooth-transition"
                        :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Ongkir</span>
                </a>
                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('admin.settings.index') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                    <i class="fas fa-cog w-6 text-center"></i>
                    <span class="ml-3 font-medium smooth-transition"
                        :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Website</span>
                </a>
            </div>
        @else
            <!-- GROUP MITRA -->
            <div>
                <p class="px-3 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider" x-show="isSidebarOpen">
                    Menu Mitra</p>
                <a href="{{ route('mitra.dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('mitra.dashboard') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                    <i class="fas fa-th-large w-6 text-center"></i>
                    <span class="ml-3 font-medium smooth-transition"
                        :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Dashboard</span>
                </a>
                <!-- Mitra Orders Link -->
                <a href="{{ route('mitra.orders.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('mitra.orders.*') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                    <i class="fas fa-shopping-bag w-6 text-center"></i>
                    <span class="ml-3 font-medium smooth-transition"
                        :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Pesanan Saya</span>
                </a>
                <a href="{{ route('mitra.settings') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl group {{ request()->routeIs('mitra.settings') ? 'sidebar-item-active' : 'sidebar-item-inactive' }}">
                    <i class="fas fa-cog w-6 text-center"></i>
                    <span class="ml-3 font-medium smooth-transition"
                        :class="isSidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">Pengaturan</span>
                </a>
            </div>
        @endif
    </nav>

    <!-- User Footer Mini -->
    <div class="p-4 border-t border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3 overflow-hidden">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=f97316&color=fff"
                class="w-10 h-10 rounded-xl shrink-0">
            <div class="smooth-transition shrink-0"
                :class="isSidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0 invisible'">
                <p class="text-sm font-bold truncate">{{ Auth::user()->name ?? 'Admin RCGO' }}</p>
                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email ?? 'Super Admin' }}</p>
            </div>
        </div>
    </div>
</aside>