@props(['number', 'indicator', 'category'])

<div class="q-card w-full bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 md:p-6 shadow-[0_2px_10px_rgba(15,23,42,0.04)] transition-all duration-200 hover:shadow-[0_8px_24px_rgba(15,23,42,0.06)] hover:border-slate-300"
     id="card-{{ $indicator['code'] }}"
     data-id="{{ $indicator['code'] }}"
     data-category="{{ $category['name'] ?? '' }}"
     data-category-id="{{ $category['name'] ?? '' }}"
     data-category-en="{{ $category['name_en'] ?? ($category['name'] ?? '') }}"
     data-category-code="{{ $category['code'] ?? '' }}"
     data-text="{{ $indicator['text'] }}"
     data-text-id="{{ $indicator['text'] }}"
     data-text-en="{{ $indicator['text_en'] ?? $indicator['text'] }}">

    {{-- Header Pertanyaan / Indikator --}}
    <div class="flex items-start gap-3 mb-4">
        <span class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-blue-50 text-[#003B73] font-black text-xs sm:text-sm flex items-center justify-center shrink-0 border border-blue-100 shadow-2xs mt-0.5">
            {{ $indicator['code'] }}
        </span>
        <div class="flex-1 min-w-0">
            <h4 class="indicator-text text-sm md:text-base font-bold text-slate-800 leading-snug">
                {{ $indicator['text'] }}
            </h4>
        </div>
    </div>

    {{-- Blok Penilaian Dual-Metric (Kepentingan & Kepuasan) --}}
    <div class="space-y-3.5 pt-3 border-t border-slate-100">

        {{-- Baris 1: Tingkat Kepentingan (1 = Sangat Tidak Penting, 5 = Sangat Penting) --}}
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <span class="label-imp text-xs font-bold text-slate-700 tracking-wide flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    <span class="label-imp-text">Tingkat Kepentingan</span>
                </span>
                <span class="sublabel-imp text-[11px] text-slate-400 font-medium hidden sm:inline">
                    1: Sangat Tidak Penting → 5: Sangat Penting
                </span>
            </div>

            {{-- Pilihan Angka 1-5 (Touch-friendly & Responsive) --}}
            <div class="grid grid-cols-5 gap-1.5 sm:gap-2" id="imp-{{ $indicator['code'] }}">
                @for ($n = 1; $n <= 5; $n++)
                    <button type="button"
                            class="score-btn w-full h-11 sm:h-12 rounded-xl border border-slate-200 bg-slate-50/90 text-slate-700 font-extrabold text-sm sm:text-base flex items-center justify-center transition-all duration-150 shadow-2xs hover:bg-white hover:border-slate-300 active:scale-95 focus:outline-none touch-manipulation"
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

        {{-- Visualisasi Panduan Skala di Tengah (Garis Gradasi Panah: Kurang – Netral – Sangat) --}}
        <div class="my-2 px-1 select-none" aria-hidden="true">
            <div class="relative w-full h-1 bg-gradient-to-r from-red-500 via-slate-300 to-emerald-500 rounded-full">
                <div class="absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-red-500 shadow-2xs"></div>
                <div class="absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-slate-400 shadow-2xs"></div>
                <div class="absolute -right-1 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-emerald-500 shadow-2xs"></div>
            </div>
            <div class="flex justify-between items-center text-[10px] mt-1.5 font-bold tracking-tight">
                <span class="scale-label-left text-red-500 flex items-center gap-1">
                    <span>Kurang Puas / Tidak Penting</span>
                </span>
                <span class="scale-label-mid text-slate-400 font-semibold">
                    Netral
                </span>
                <span class="scale-label-right text-emerald-600 flex items-center gap-1">
                    <span>Sangat Puas / Sangat Penting</span>
                </span>
            </div>
        </div>

        {{-- Baris 2: Tingkat Kepuasan (1 = Sangat Tidak Puas, 5 = Sangat Puas) --}}
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <span class="label-sat text-xs font-bold text-slate-700 tracking-wide flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="label-sat-text">Tingkat Kepuasan</span>
                </span>
                <span class="sublabel-sat text-[11px] text-slate-400 font-medium hidden sm:inline">
                    1: Sangat Tidak Puas → 5: Sangat Puas
                </span>
            </div>

            {{-- Pilihan Angka 1-5 (Touch-friendly & Responsive) --}}
            <div class="grid grid-cols-5 gap-1.5 sm:gap-2" id="sat-{{ $indicator['code'] }}">
                @for ($n = 1; $n <= 5; $n++)
                    <button type="button"
                            class="score-btn w-full h-11 sm:h-12 rounded-xl border border-slate-200 bg-slate-50/90 text-slate-700 font-extrabold text-sm sm:text-base flex items-center justify-center transition-all duration-150 shadow-2xs hover:bg-white hover:border-slate-300 active:scale-95 focus:outline-none touch-manipulation"
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

        {{-- Blok Keterangan Wajib Kondisional (Muncul jika Skor Kepuasan < 4: 1, 2, atau 3) --}}
        <div class="reason-block hidden mt-3 pt-3 border-t border-rose-100 transition-all duration-300" id="reason-block-{{ $indicator['code'] }}">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                <label class="text-xs font-bold text-rose-600 flex flex-wrap items-center gap-1.5" for="reason-{{ $indicator['code'] }}">
                    <span class="reason-label-req text-rose-600 font-extrabold">*Keterangan Wajib Diisi</span>
                    <span class="reason-label-hint text-slate-400 font-normal text-[11px]">(nilai kepuasan rendah · minimal 10 karakter)</span>
                </label>
                <span class="reason-char-count text-[11px] font-bold text-slate-400 tabular-nums" id="reason-count-{{ $indicator['code'] }}">
                    0 / min. 10 karakter
                </span>
            </div>
            <textarea id="reason-{{ $indicator['code'] }}"
                      rows="2"
                      minlength="10"
                      oninput="handleReasonInput('{{ $indicator['code'] }}', this.value)"
                      class="reason-textarea w-full rounded-xl border border-rose-300 bg-rose-50/30 p-3 text-xs md:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-100 shadow-2xs outline-none transition-all resize-y leading-relaxed"
                      placeholder="Mohon berikan penjelasan singkat mengenai kendala yang menyebabkan penilaian rendah ini (min. 10 karakter)..."></textarea>
            <p class="reason-err hidden text-xs font-bold text-rose-600 mt-1 flex items-center gap-1" id="reason-err-{{ $indicator['code'] }}">
                <span>⚠️</span>
                <span class="reason-err-text">Penjelasan kendala wajib diisi minimal 10 karakter untuk nilai kepuasan rendah.</span>
            </p>
        </div>

    </div>

    {{-- Pesan Validasi Error (Muncul jika ada penilaian yang belum dipilih) --}}
    <div class="error-hint hidden text-xs font-bold text-red-600 mt-3 pt-2.5 border-t border-red-100 flex items-center gap-1.5" id="err-{{ $indicator['code'] }}">
        <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
        <span class="error-hint-text">Mohon pilih penilaian Kepentingan dan Kepuasan (skala 1-5).</span>
    </div>

</div>

