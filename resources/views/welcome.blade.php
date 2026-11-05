<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RC GO - Paket Usaha Mobil RC untung 900%</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @include('partials.landing.styles')
</head>

<body class="bg-black text-white selection:bg-orange-500 selection:text-white">

    @include('partials.landing.nav')

    <main>
        <!-- Section 1: Hero -->
        @include('partials.landing.hero')

        <!-- Section 2: Tentang Kami -->
        @include('partials.landing.about')

        <!-- Sistem Usaha Modular -->
        @include('partials.landing.sistem')

        <!-- Section 3: Keunggulan (Mengapa Memilih RC GO) -->
        @include('partials.landing.keunggulan')

        <!-- Section 4: Perbandingan Usaha -->
        @include('partials.landing.perbandingan')

        <!-- Section 5: Potensi Keuntungan (Profit Calculator) -->
        @include('partials.landing.profit')

        <!-- Section 6: Paket Usaha -->
        @include('partials.landing.packages')

        <!-- Section 6.5: Addons -->
        {{-- @include('partials.landing.addons') --}}

        <!-- Section 6.8: AI Bonus -->
        {{-- @include('partials.landing.ai-bonus') --}}

        <!-- Section 6: Testimoni -->
        @include('partials.landing.testimoni')

        <!-- Section 7: FAQ -->
        @include('partials.landing.faq')

        <!-- Section 8: CTA -->
        @include('partials.landing.cta')
    </main>

    <!-- Footer -->
    @include('partials.landing.footer')

    <!-- Modals -->
    @include('partials.landing.modals')

    <!-- Scripts -->
    @include('partials.landing.scripts')

    <!-- Cekat.biz.id Chatbot Widget -->
    <script>
        window.CSAIConfig = {
            widgetId: 'widget-3-ZYcwZDAW'
        };
    </script>
    <script src="https://cekat.biz.id/widget/widget.min.js" async></script>

    <!-- Live Notifications Container -->
    <div id="live-notifications-container"></div>

</body>

</html>