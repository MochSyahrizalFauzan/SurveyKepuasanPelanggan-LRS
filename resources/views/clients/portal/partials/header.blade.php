<header class="bg-[#003B73] rounded-2xl p-5 sm:p-6 md:p-8 text-white shadow-xl shadow-[#003B73]/25 relative overflow-hidden border border-white/10">

    {{-- Garis Aksen Tri-Warna Khas Corporate Len (Merah, Emas, Cyan) --}}
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#D32F2F] via-[#F59E0B] to-[#38BDF8]"></div>

    {{-- Hiasan Gambar Pusat Kendali Operasi Rel (OCC) PT Len Railway Systems di Sisi Kanan --}}
    <div class="absolute -right-8 -bottom-6 md:right-0 md:bottom-0 w-[240px] sm:w-[360px] md:w-[480px] h-[180px] md:h-full opacity-25 md:opacity-35 bg-no-repeat bg-right-bottom bg-cover md:bg-contain pointer-events-none mix-blend-luminosity filter contrast-125"
         style="background-image: url('{{ asset('images/bg-railway-control.jpg') }}'); mask-image: linear-gradient(to left, rgba(0,0,0,1) 25%, transparent 95%); -webkit-mask-image: linear-gradient(to left, rgba(0,0,0,1) 25%, transparent 95%);">
    </div>

    {{-- Pendaran Halus Ambient Orb di Belakang Gambar --}}
    <div class="absolute right-10 bottom-0 w-72 h-72 rounded-full bg-sky-400/15 blur-2xl pointer-events-none"></div>
    <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>

    {{-- Baris Identitas Perusahaan & Formulir --}}
    <div class="flex items-center gap-3 sm:gap-3.5 mb-3 sm:mb-4 relative z-10">
        <div class="h-10 sm:h-11 md:h-12 px-2.5 sm:px-3 rounded-xl bg-white flex items-center justify-center shrink-0 shadow-md border border-white/20">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo PT Len Railway Systems"
                 class="h-6 sm:h-7 md:h-8 w-auto object-contain">
        </div>
        <div>
            <h2 id="client-header-title" class="text-lg sm:text-xl md:text-2xl font-extrabold tracking-tight text-white leading-tight">
                Survei Kepuasan Pelanggan
            </h2>
        </div>
    </div>

    {{-- Penjelasan Singkat --}}
    <p id="client-header-subtitle" class="text-xs sm:text-xs md:text-sm text-blue-100/90 mt-1 max-w-xl leading-relaxed relative z-10">
        Setiap evaluasi dan masukan Anda sangat berharga bagi peningkatan keandalan sistem perkeretaapian dan mutu layanan kami.
    </p>
</header>
