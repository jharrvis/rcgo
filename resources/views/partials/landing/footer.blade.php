<footer class="bg-black border-t border-gray-800 py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start gap-8 border-b border-gray-800 pb-8 mb-8">
            <!-- Logo & Copyright -->
            <div class="text-center md:text-left">
                <img src="{{ asset('assets/img/rcgo-logo.svg') }}" alt="RC GO Logo"
                    class="h-10 md:h-12 mb-4 mx-auto md:mx-0">
                <p class="text-xs md:text-sm text-gray-400">&copy; 2025 RC GO. Seluruh Hak Cipta Dilindungi.</p>
            </div>

            <!-- Contact Info -->
            <div class="flex flex-col gap-4 text-center md:text-right">
                <div>
                    <h4 class="text-white font-semibold text-sm mb-1">Alamat</h4>
                    <p class="text-gray-400 text-xs md:text-sm max-w-xs mx-auto md:mx-0 whitespace-pre-line">
                        {{ $company_address }}</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-1">Email</h4>
                    <a href="mailto:{{ $company_email }}"
                        class="text-gray-400 hover:text-orange-500 text-xs md:text-sm transition-colors">{{ $company_email }}</a>
                </div>
                <div class="flex justify-center md:justify-end gap-4 md:gap-6 text-lg md:text-xl mt-2">
                    <a href="{{ $social_facebook }}" target="_blank"
                        class="text-gray-400 hover:text-orange-500 transition-colors"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="{{ $social_instagram }}" target="_blank"
                        class="text-gray-400 hover:text-orange-500 transition-colors"><i
                            class="fab fa-instagram"></i></a>
                    <a href="{{ $social_tiktok }}" target="_blank"
                        class="text-gray-400 hover:text-orange-500 transition-colors"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button -->
<div id="scroll-top">
    <i class="fas fa-arrow-up"></i>
</div>