<style>
    /* Toast Notification */
    .toast {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: rgba(31, 41, 55, 0.95);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        border: 1px solid rgba(249, 115, 22, 0.3);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 100;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
    }

    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast i {
        color: #f97316;
    }

    /* Cart Badge Animation */
    @keyframes cart-bounce {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.3);
        }
    }

    .cart-animate {
        animation: cart-bounce 0.3s ease-in-out;
    }
</style>

<!-- Top Bar - Hidden on mobile -->
<div class="bg-gradient-to-r from-gray-900 to-black border-b border-gray-800 py-2 hidden md:block">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center text-xs text-gray-400">
        <div class="flex gap-6 items-center">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company_phone) }}" target="_blank"
                class="flex items-center gap-2 hover:text-orange-500 transition-colors">
                <i class="fab fa-whatsapp text-green-500"></i>
                <span>{{ $company_phone }}</span>
            </a>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-gray-500 text-xs uppercase tracking-wider mr-2">Follow Us</span>
            <a href="{{ $social_facebook }}" target="_blank"
                class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-800 hover:bg-orange-500 text-gray-400 hover:text-white transition-all"
                title="Facebook">
                <i class="fab fa-facebook-f text-xs"></i>
            </a>
            <a href="{{ $social_instagram }}" target="_blank"
                class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-800 hover:bg-gradient-to-tr hover:from-purple-600 hover:to-pink-500 text-gray-400 hover:text-white transition-all"
                title="Instagram">
                <i class="fab fa-instagram text-xs"></i>
            </a>
            <a href="{{ $social_tiktok }}" target="_blank"
                class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-800 hover:bg-black hover:ring-1 hover:ring-white text-gray-400 hover:text-white transition-all"
                title="TikTok">
                <i class="fab fa-tiktok text-xs"></i>
            </a>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="bg-black/95 backdrop-blur-md sticky top-0 z-50 py-3 md:py-4 border-b border-gray-800"
    x-data="{ mobileMenu: false }">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex-shrink-0">
            <img src="{{ asset('assets/img/rcgo-logo.svg') }}" alt="RC GO Logo" class="h-10 md:h-12 lg:h-14">
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex gap-2 items-center">
            <a href="{{ route('home') }}"
                class="group relative px-4 py-2 rounded-lg text-white hover:text-orange-400 font-medium transition-all text-sm hover:bg-gray-800/50">
                Beranda
            </a>
            <a href="{{ route('home') }}#tentang"
                class="group relative px-4 py-2 rounded-lg text-white hover:text-orange-400 font-medium transition-all text-sm hover:bg-gray-800/50">
                Tentang
            </a>
            <a href="{{ route('home') }}#keuntungan"
                class="group relative px-4 py-2 rounded-lg text-white hover:text-orange-400 font-medium transition-all text-sm hover:bg-gray-800/50">
                Keuntungan
            </a>
            <a href="{{ route('home') }}#paket"
                class="group relative px-4 py-2 rounded-lg text-white hover:text-orange-400 font-medium transition-all text-sm hover:bg-gray-800/50">
                Paket Usaha
            </a>
            <a href="{{ route('home') }}#ai-bonus"
                class="group relative px-4 py-2 rounded-lg text-white hover:text-purple-400 font-medium transition-all text-sm hover:bg-gradient-to-r hover:from-purple-900/30 hover:to-blue-900/30">
                <span class="flex items-center gap-2">
                    Bonus
                    <span
                        class="px-1.5 py-0.5 bg-gradient-to-r from-purple-500 to-blue-500 text-white text-[10px] font-bold rounded uppercase">AI</span>
                </span>
            </a>
        </div>

        <!-- Right Section: Auth & CTA -->
        <div class="flex items-center gap-3 md:gap-4">


            @auth
                <!-- Logged In User Dropdown -->
                <div class="hidden md:flex items-center gap-3" x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-3 py-2 rounded-full bg-gray-800/50 hover:bg-gray-800 border border-gray-700 transition-all">
                            <div
                                class="w-7 h-7 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span
                                class="text-white text-sm font-medium max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-gray-500 text-xs transition-transform"
                                :class="{ 'rotate-180': open }"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.outside="open = false" x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-gray-900 rounded-2xl border border-gray-800 shadow-2xl overflow-hidden z-50">

                            <div class="px-4 py-3 border-b border-gray-800">
                                <p class="text-white font-semibold text-sm truncate">{{ auth()->user()->name }}</p>
                                <p class="text-gray-500 text-xs truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <div class="py-2">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-800 transition-all">
                                        <i class="fas fa-chart-line text-orange-500 w-5 text-center"></i>
                                        <span class="text-sm">Dashboard Admin</span>
                                    </a>
                                    <a href="{{ route('admin.orders.index') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-800 transition-all">
                                        <i class="fas fa-shopping-bag text-orange-500 w-5 text-center"></i>
                                        <span class="text-sm">Kelola Pesanan</span>
                                    </a>
                                @else
                                    <a href="{{ route('mitra.dashboard') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-800 transition-all">
                                        <i class="fas fa-columns text-orange-500 w-5 text-center"></i>
                                        <span class="text-sm">Dashboard Mitra</span>
                                    </a>
                                    <a href="{{ route('mitra.orders.index') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-800 transition-all">
                                        <i class="fas fa-shopping-bag text-orange-500 w-5 text-center"></i>
                                        <span class="text-sm">Pesanan Saya</span>
                                    </a>
                                    <a href="{{ route('mitra.settings') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-800 transition-all">
                                        <i class="fas fa-cog text-orange-500 w-5 text-center"></i>
                                        <span class="text-sm">Pengaturan Akun</span>
                                    </a>
                                @endif
                            </div>

                            <div class="border-t border-gray-800 py-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-gray-400 hover:text-red-500 hover:bg-gray-800 transition-all">
                                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                                        <span class="text-sm">Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Guest: Login Button -->
                <a href="{{ route('login') }}"
                    class="hidden md:flex items-center gap-2 text-gray-400 hover:text-orange-500 font-medium transition-colors text-sm px-4 py-2 rounded-full hover:bg-gray-800/50">
                    <i class="fas fa-user"></i>
                    <span>Masuk</span>
                </a>
            @endauth

            <!-- CTA Button (Hidden on Checkout) -->
            @if(!request()->routeIs('checkout*'))
                <a id="nav-cta-btn" href="{{ route('home') }}#paket"
                    class="btn-primary flex items-center gap-2 px-5 md:px-6 py-2.5 md:py-3 rounded-full text-white font-bold text-sm shadow-lg shadow-orange-500/20 hover:shadow-orange-500/30 transition-all whitespace-nowrap">
                    <i class="fas fa-rocket"></i>
                    <span class="hidden sm:inline">Mulai Usaha</span>
                    <span class="sm:hidden">Daftar</span>
                </a>
            @endif

            <!-- Mobile Menu Button -->
            <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-white p-2">
                <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" x-cloak x-transition class="lg:hidden bg-gray-900 border-t border-gray-800 mt-4">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-2">
            <a href="{{ route('home') }}"
                class="block text-white hover:text-orange-500 font-medium py-2.5 px-3 rounded-lg hover:bg-gray-800/50 transition-all">
                Beranda
            </a>
            <a href="{{ route('home') }}#tentang"
                class="block text-white hover:text-orange-500 font-medium py-2.5 px-3 rounded-lg hover:bg-gray-800/50 transition-all">
                Tentang
            </a>
            <a href="{{ route('home') }}#keuntungan"
                class="block text-white hover:text-orange-500 font-medium py-2.5 px-3 rounded-lg hover:bg-gray-800/50 transition-all">
                Keuntungan
            </a>
            <a href="{{ route('home') }}#paket"
                class="block text-white hover:text-orange-500 font-medium py-2.5 px-3 rounded-lg hover:bg-gray-800/50 transition-all">
                Paket Usaha
            </a>
            <a href="{{ route('home') }}#ai-bonus"
                class="block text-white hover:text-purple-400 font-medium py-2.5 px-3 rounded-lg hover:bg-gradient-to-r hover:from-purple-900/30 hover:to-blue-900/30 transition-all">
                <span class="flex items-center gap-2">
                    Bonus
                    <span
                        class="px-1.5 py-0.5 bg-gradient-to-r from-purple-500 to-blue-500 text-white text-[10px] font-bold rounded uppercase">AI</span>
                </span>
            </a>
            {{-- <a href="{{ route('home') }}#addons"
                class="block text-white hover:text-orange-500 font-medium py-2">Add-ons</a> --}}

            <div class="pt-4 border-t border-gray-800">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-2 text-orange-500 font-medium py-2">
                            <i class="fas fa-chart-line"></i> Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('mitra.dashboard') }}"
                            class="flex items-center gap-2 text-orange-500 font-medium py-2">
                            <i class="fas fa-columns"></i> Dashboard Mitra
                        </a>
                        <a href="{{ route('mitra.orders.index') }}"
                            class="flex items-center gap-2 text-orange-500 font-medium py-2">
                            <i class="fas fa-shopping-bag"></i> Pesanan Saya
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 text-gray-400 hover:text-red-500 font-medium py-2">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-2 text-gray-400 hover:text-orange-500 font-medium py-2">
                        <i class="fas fa-user"></i> Masuk
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Toast Container -->
<div id="toast-container"></div>

<!-- Cart Drawer/Modal -->
<div id="cart-modal" class="fixed inset-0 bg-black/80 z-[60] hidden justify-end transition-opacity duration-300">
    <div class="w-full max-w-md bg-gray-900 h-full shadow-2xl overflow-y-auto transform translate-x-full transition-transform duration-300"
        id="cart-content">
        <div class="p-6 h-full flex flex-col">
            <div class="flex justify-between items-center mb-6 border-b border-gray-800 pb-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-shopping-cart text-orange-500"></i> Keranjang
                </h2>
                <div class="flex items-center gap-3">
                    <button onclick="clearCart()"
                        class="text-xs text-red-500 hover:text-red-400 font-medium transition-colors"
                        title="Hapus Semua">
                        <i class="fas fa-trash-alt mr-1"></i> Reset
                    </button>
                    <button onclick="closeCart()" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <div id="cart-items" class="flex-grow space-y-4 overflow-y-auto mb-6">
                <!-- Cart items will be injected here -->
                <div class="text-center text-gray-500 py-12 flex flex-col items-center">
                    <i class="fas fa-shopping-basket text-4xl mb-3 opacity-30"></i>
                    <p>Keranjang masih kosong</p>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-4 mt-auto">
                <div class="flex justify-between items-center mb-4 text-white">
                    <span class="text-gray-400">Total</span>
                    <span class="text-xl font-bold text-orange-500" id="cart-total">Rp 0</span>
                </div>
                <a href="/checkout" onclick="validateCheckout(event)"
                    class="block w-full btn-primary py-3.5 rounded-full text-white font-bold text-center mb-3 hover:shadow-lg hover:shadow-orange-500/20 transition-all">
                    Checkout Sekarang
                </a>
                <button onclick="closeCart()"
                    class="block w-full border border-gray-700 text-gray-300 py-3 rounded-full font-medium hover:bg-gray-800 transition-colors">
                    Lanjut Belanja
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function validateCheckout(e) {
        e.preventDefault();

        // Check if main package exists
        if (cartState.mainPackages.length === 0) {
            openCustomModal('modal-warning-package');
            return;
        }

        // Check if addons exist (Upsell) - TEMPORARILY DISABLED
        /* if (cartState.addons.length === 0) {
            openCustomModal('modal-upsell-addon');
            return;
        } */

        // Add loading state
        const btn = e.currentTarget;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;

        // Proceed to checkout
        setTimeout(() => {
            window.location.href = '/checkout';
        }, 500);
    }

    function openCustomModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Animation
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95', 'opacity-0');
                modal.firstElementChild.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    }

    function closeCustomModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.firstElementChild.classList.add('scale-95', 'opacity-0');
            modal.firstElementChild.classList.remove('scale-100', 'opacity-100');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    }
</script>
<script>
    // === Cart System Logic ===
    const cartState = {
        mainPackages: [],
        addons: [],
        total: 0
    };

    // Initialize Cart from LocalStorage
    function initCart() {
        const stored = localStorage.getItem('rcgo_cart_v4');
        if (stored) {
            try {
                const parsed = JSON.parse(stored);
                cartState.mainPackages = parsed.mainPackages || (parsed.mainPackage ? [parsed.mainPackage] : []);
                cartState.addons = parsed.addons || [];
                updateCartUI();
            } catch (e) {
                console.error('Cart parse error', e);
            }
        }
        updateBadge();
    }

    // Add Item to Cart
    function addToCart(id, name, price, type = 'addon', slug = null) {
        if (type === 'package') {
            const existing = cartState.mainPackages.find(item => item.id === id);
            if (existing) {
                existing.qty += 1;
                showToast(`${name} qty bertambah`);
            } else {
                cartState.mainPackages.push({
                    id,
                    name,
                    price,
                    qty: 1,
                    type,
                    slug: slug || id  // Save slug for checkout page
                });
                showToast(`${name} ditambahkan!`);
            }
        } else {
            // Addon logic
            const existing = cartState.addons.find(item => item.id === id);
            if (existing) {
                existing.qty += 1;
                showToast(`${name} qty bertambah`);
            } else {
                cartState.addons.push({
                    id,
                    name,
                    price,
                    qty: 1,
                    type
                });
                showToast(`${name} ditambahkan!`);
            }
        }

        saveCart();
        updateCartUI();
        updateBadge();

        // Auto open cart drawer
        openCart();

        // Bounce animation
        const badge = document.getElementById('cart-badge');
        badge.classList.add('cart-animate');
        setTimeout(() => badge.classList.remove('cart-animate'), 300);
    }

    // Remove Item
    function removeFromCart(id, type) {
        if (type === 'package') {
            cartState.mainPackages = cartState.mainPackages.filter(item => item.id !== id);
        } else {
            cartState.addons = cartState.addons.filter(item => item.id !== id);
        }
        saveCart();
        updateCartUI();
        updateBadge();
    }

    // Clear Cart
    function clearCart() {
        openConfirmModal(
            'Kosongkan Keranjang?',
            'Tindakan ini akan menghapus semua item yang telah Anda pilih. Anda yakin ingin melanjutkan?',
            function () {
                cartState.mainPackages = [];
                cartState.addons = [];
                saveCart();
                updateCartUI();
                updateBadge();
                showToast('Keranjang telah dikosongkan');
                // Auto close cart drawer if empty
                setTimeout(closeCart, 500);
            }
        );
    }

    // Update Qty
    function updateQty(id, change, type) {
        if (type === 'package') {
            const existing = cartState.mainPackages.find(item => item.id === id);
            if (existing) {
                existing.qty += change;
                if (existing.qty <= 0) {
                    removeFromCart(id, 'package');
                }
            }
        } else {
            const item = cartState.addons.find(i => i.id === id);
            if (item) {
                item.qty += change;
                if (item.qty <= 0) removeFromCart(id, 'addon');
            }
        }
        saveCart();
        updateCartUI();
        updateBadge();
    }

    // Save to LocalStorage
    function saveCart() {
        // Calc Total
        let total = 0;
        cartState.mainPackages.forEach(p => total += p.price * p.qty);
        cartState.addons.forEach(a => total += a.price * a.qty);

        cartState.total = total;
        localStorage.setItem('rcgo_cart_v4', JSON.stringify(cartState));
    }

    // Update Badge Count & CTA Logic
    function updateBadge() {
        const badge = document.getElementById('cart-badge');
        const ctaBtn = document.getElementById('nav-cta-btn');

        // Compute total even if elements are missing (good for debugging or other logic)
        let totalQty = 0;
        cartState.mainPackages.forEach(p => totalQty += p.qty);
        cartState.addons.forEach(a => totalQty += a.qty);

        // Update Badge if exists
        if (badge) {
            badge.innerText = totalQty;
            if (totalQty > 0) {
                badge.classList.remove('scale-0', 'opacity-0');
                badge.classList.add('scale-100', 'opacity-100');
            } else {
                badge.classList.add('scale-0', 'opacity-0');
                badge.classList.remove('scale-100', 'opacity-100');
            }
        }

        // Update CTA if exists
        if (ctaBtn) {
            if (totalQty > 0) {
                // Calc Total for Button
                let total = 0;
                cartState.mainPackages.forEach(p => total += p.price * p.qty);
                cartState.addons.forEach(a => total += a.price * a.qty);

                ctaBtn.innerHTML = `
                    <i class="fas fa-shopping-bag animate-pulse"></i>
                    <span class="hidden sm:inline">Checkout</span>
                    <span class="bg-white/20 px-2 py-0.5 rounded text-xs ml-1 font-mono">${formatRupiah(total)}</span>
                `;
                ctaBtn.setAttribute('href', '/checkout-wizard');
                ctaBtn.setAttribute('onclick', 'validateCheckout(event)');

                // Change Style to Green/Emerald to signify "Go/Finish"
                ctaBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                ctaBtn.classList.remove('shadow-orange-500/20', 'hover:shadow-orange-500/30');
                ctaBtn.classList.add('shadow-emerald-500/20', 'hover:shadow-emerald-500/30');
            } else {
                // Dynamic CTA: Revert to Default
                ctaBtn.innerHTML = `
                    <i class="fas fa-rocket"></i>
                    <span class="hidden sm:inline">Mulai Usaha</span>
                    <span class="sm:hidden">Daftar</span>
                `;
                ctaBtn.setAttribute('href', '{{ route("home") }}#paket');
                ctaBtn.removeAttribute('onclick');

                // Revert Style
                ctaBtn.style.background = '';
                ctaBtn.classList.add('shadow-orange-500/20', 'hover:shadow-orange-500/30');
                ctaBtn.classList.remove('shadow-emerald-500/20', 'hover:shadow-emerald-500/30');
            }
        }
    }

    // Update Cart Drawer UI
    function updateCartUI() {
        const container = document.getElementById('cart-items');
        const totalDisplay = document.getElementById('cart-total');

        // Recalc total just for display accuracy
        let total = 0;
        cartState.mainPackages.forEach(p => total += p.price * p.qty);
        cartState.addons.forEach(a => total += a.price * a.qty);
        totalDisplay.innerText = formatRupiah(total);

        if (cartState.mainPackages.length === 0 && cartState.addons.length === 0) {
            container.innerHTML = `
                    <div class="text-center text-gray-500 py-12 flex flex-col items-center">
                        <i class="fas fa-shopping-basket text-4xl mb-3 opacity-30"></i>
                        <p>Keranjang masih kosong</p>
                    </div>`;
            return;
        }

        let html = '';

        // Section Main Packages
        if (cartState.mainPackages.length > 0) {
            html += `<div class="mb-4">
                    <h5 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Paket Usaha</h5>
                    ${cartState.mainPackages.map(p => renderCartItem(p)).join('')}
                </div>`;
        }

        // Section Addons
        if (cartState.addons.length > 0) {
            html += `<div>
                    <h5 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Add-ons (Aksesoris)</h5>
                    ${cartState.addons.map(a => renderCartItem(a)).join('')}
                </div>`;
        }

        container.innerHTML = html;
    }

    function renderCartItem(item) {
        return `
                 <div class="bg-gray-800 p-3 rounded-xl flex items-center justify-between border border-gray-700 mb-2">
                    <div>
                        <h4 class="text-white font-semibold text-sm line-clamp-1">${item.name}</h4>
                        <p class="text-orange-500 text-xs font-bold">${formatRupiah(item.price)}</p>
                    </div>
                    <div class="flex items-center gap-3 bg-gray-900 rounded-lg p-1">
                        <button onclick="updateQty('${item.id}', -1, '${item.type}')" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-white transition-colors">-</button>
                        <span class="text-white text-sm font-medium w-4 text-center">${item.qty}</span>
                        <button onclick="updateQty('${item.id}', 1, '${item.type}')" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-white transition-colors">+</button>
                    </div>
                </div>
            `;
    }

    // Format Rupiah
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(amount);
    }

    // Open/Close Cart
    function openCart() {
        const modal = document.getElementById('cart-modal');
        const content = document.getElementById('cart-content');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // Small delay for animation
        setTimeout(() => {
            content.classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';
        updateCartUI();
    }

    function closeCart() {
        const modal = document.getElementById('cart-modal');
        const content = document.getElementById('cart-content');

        content.classList.add('translate-x-full');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
        document.body.style.overflow = 'auto';
    }

    // Close cart when clicking outside
    document.getElementById('cart-modal').addEventListener('click', function (e) {
        if (e.target === this) closeCart();
    });

    // Toast Notification
    function showToast(message) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `<i class="fas fa-check-circle"></i> <span>${message}</span>`;

        container.appendChild(toast);

        // Trigger animation
        setTimeout(() => toast.classList.add('show'), 10);

        // Remove after 3s
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Init
    document.addEventListener('DOMContentLoaded', initCart);
</script>