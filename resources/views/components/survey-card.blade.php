@props(['number', 'indicator', 'category'])

<div class="q-card w-full bg-white rounded-2xl border border-slate-200 p-5 md:p-6 shadow-sm transition-all duration-200 hover:shadow-md hover:border-slate-300"
     id="card-{{ $indicator['code'] }}"
     data-id="{{ $indicator['code'] }}"
     data-category="{{ $category['name'] }}"
     data-text="{{ $indicator['text'] }}">

    {{-- Header Pertanyaan --}}
    <div class="flex items-start gap-3.5 mb-5">
        <!-- <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#003B73] font-extrabold text-xs flex items-center justify-center shrink-0 mt-0.5 border border-blue-100">
            {{ $number }}
        </div> -->
        <div class="text-md md:text-[15px] font-bold text-slate-800 leading-snug">
            {{ $indicator['text'] }}
        </div>
    </div>

    {{-- Blok Penilaian Dual-Metric --}}
    <div class="space-y-3 pt-2 border-t border-slate-100">

        {{-- Baris 1: Tingkat Kepentingan (Biru bg-blue-600 saat aktif) --}}
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700 tracking-wide">
                    Tingkat Kepentingan
                </span>
            </div>

            {{-- Deretan Tombol Angka 1-5 (w-10 h-10 di HP, md:w-12 md:h-12 di Desktop) --}}
            <div class="flex items-center justify-between gap-2" id="imp-{{ $indicator['code'] }}">
                @for ($n = 1; $n <= 5; $n++)
                    <button type="button"
                            class="score-btn flex-1 md:flex-none w-10 h-10 md:w-12 md:h-12 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 font-bold text-sm md:text-base flex items-center justify-center transition-all duration-150 hover:bg-white hover:border-slate-300 active:scale-95 focus:outline-none"
                            data-id="{{ $indicator['code'] }}"
                            data-type="imp"
                            data-val="{{ $n }}"
                            onclick="setScore('{{ $indicator['code'] }}', 'imp', {{ $n }})"
                            aria-label="Kepentingan {{ $n }}">
                        {{ $n }}
                    </button>
                @endfor
            </div>
        </div>

        {{-- Visual Guide Skala (Disisipkan di antara baris Kepentingan dan Kepuasan) --}}
        <x-visual-guide-skala />

        {{-- Baris 2: Tingkat Kepuasan (Emas bg-amber-500 saat aktif) --}}
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700 tracking-wide">
                    Tingkat Kepuasan
                </span>
            </div>

            {{-- Deretan Tombol Angka 1-5 (w-10 h-10 di HP, md:w-12 md:h-12 di Desktop) --}}
            <div class="flex items-center justify-between gap-2" id="sat-{{ $indicator['code'] }}">
                @for ($n = 1; $n <= 5; $n++)
                    <button type="button"
                            class="score-btn flex-1 md:flex-none w-10 h-10 md:w-12 md:h-12 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 font-bold text-sm md:text-base flex items-center justify-center transition-all duration-150 hover:bg-white hover:border-slate-300 active:scale-95 focus:outline-none"
                            data-id="{{ $indicator['code'] }}"
                            data-type="sat"
                            data-val="{{ $n }}"
                            onclick="setScore('{{ $indicator['code'] }}', 'sat', {{ $n }})"
                            aria-label="Kepuasan {{ $n }}">
                        {{ $n }}
                    </button>
                @endfor
            </div>
        </div>

    </div>

    {{-- Keterangan Wajib Diisi (Hanya muncul jika Kepuasan <= 2) --}}
    <div class="keterangan-wrap mt-4 pt-3 border-t border-red-100 hidden" id="ket-wrap-{{ $indicator['code'] }}">
        <div class="flex items-center gap-1.5 text-xs font-bold text-red-600 mb-2">
            <span>*Keterangan Wajib Diisi</span>
            <span class="text-[11px] font-normal text-slate-500">(nilai kepuasan rendah)</span>
        </div>
        <textarea
            class="keterangan-textarea w-full min-h-[76px] rounded-xl border border-red-200 bg-red-50/60 p-3 text-xs md:text-sm text-slate-800 placeholder-red-300 focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all resize-y"
            id="ket-{{ $indicator['code'] }}"
            placeholder="Mohon berikan penjelasan singkat mengenai kendala yang menyebabkan penilaian rendah ini..."
            oninput="updateKeterangan('{{ $indicator['code'] }}', this.value)"
        ></textarea>
    </div>

    {{-- Pesan Validasi Error --}}
    <div class="error-hint text-[11px] font-semibold text-red-600 mt-2.5 hidden" id="err-{{ $indicator['code'] }}">
        Mohon lengkapi penilaian Kepentingan, Kepuasan, dan Keterangan wajib.
    </div>

</div>
