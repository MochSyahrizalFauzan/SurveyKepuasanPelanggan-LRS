{{-- Komponen Visual Guide Skala (Panduan Arah Skala 1 - 5) --}}
<div class="my-2.5 px-0.5 select-none" aria-hidden="true">
    {{-- Garis Gradasi Utama dengan Panah di Kedua Ujung --}}
    <div class="relative w-full h-0.5 bg-gradient-to-r from-red-500 via-slate-300 to-green-500 rounded-full">
        {{-- Ikon Panah Kiri Merah --}}
        <svg class="absolute -left-1 top-1/2 -translate-y-1/2 w-2.5 h-2.5 text-red-800"
             viewBox="0 0 10 10"
             fill="currentColor">
            <polygon points="0,5 8,1.5 8,8.5" />
        </svg>

        {{-- Ikon Panah Kanan Hijau --}}
        <svg class="absolute -right-1 top-1/2 -translate-y-1/2 w-2.5 h-2.5 text-green-800"
             viewBox="0 0 10 10"
             fill="currentColor">
            <polygon points="10,5 2,1.5 2,8.5" />
        </svg>
    </div>

    {{-- Teks Keterangan Skala --}}
    <div class="flex justify-between items-start text-[10px] mt-1 font-semibold leading-tight">
        <span class="text-red-500 text-left">
            Kurang Puas / Tidak Penting
        </span>
        <span class="flex-1 text-center text-slate-400 font-medium">
            Netral
        </span>
        <span class="text-green-600 text-right">
            Sangat Puas / Sangat Penting
        </span>
    </div>
</div>
