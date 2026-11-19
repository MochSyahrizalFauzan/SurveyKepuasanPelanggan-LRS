<header class="bg-[#003B73] rounded-2xl p-6 md:p-8 text-white shadow-lg shadow-[#003B73]/20 relative overflow-hidden">
    {{-- Elemen Dekoratif Halus --}}
    <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute right-1/4 -bottom-12 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>

    {{-- Baris Identitas Perusahaan --}}
    <div class="flex items-center gap-3 mb-4 relative z-10">
        <div class="h-10 md:h-12 px-2.5 rounded-xl bg-white flex items-center justify-center shrink-0 shadow-sm">
            <img src="{{ asset('images/logo.png') }}" alt="Logo PT. Len Railways Systems" class="h-7 md:h-8 w-auto object-contain">
        </div>
        <div>
            <h2 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight text-white">
                Survei Kepuasan Pelanggan
            </h2>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-[#003B73] text-xs font-bold uppercase tracking-wider mb-2 border border-blue-100">
                <span>Formulir CSI FRM-QOP-06-02</span>
            </div>
        </div>
    </div>

       
    <div class="relative z-10 mb-2">
        
        {{-- Legend Warna Metrik --}}
        <div class="flex flex-wrap items-center gap-3 mt-4 pt-4 border-t border-slate-100">
        <h5 class="text-sm md:text-2sm font-bold text-white tracking-tight">
            Mohon berikan penilaian Tingkat Kepentingan dan Tingkat Kepuasan pada 2 indikator di bawah ini.
        </h5>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-200 text-[#003B73] text-xs font-bold">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                <span>Tingkat Kepentingan (Biru)</span>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span>Tingkat Kepuasan (Emas)</span>
            </div>
        </div>
    </div>
    <p class="text-xs md:text-sm text-white/80 mt-1 max-w-2xl leading-relaxed">
        Setiap evaluasi dan masukan Anda sangat berharga bagi peningkatan keandalan sistem dan mutu layanan kami.
    </p>
</header>
