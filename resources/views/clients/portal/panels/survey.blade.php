<div id="panel-survei" class="panel active" role="tabpanel" aria-labelledby="tab-survei">
    <div class="space-y-6">

        {{-- Section Header --}}


        {{-- Form Survei --}}
        <form id="form-survei" novalidate class="space-y-6">

            @php
                $categories = $categories ?? config('survey.categories', []);
                $currentNumber = 1;
            @endphp

            @foreach ($categories as $category)
                {{-- Header Kategori --}}
                <div class="pt-2">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 shadow-sm flex items-center justify-center text-lg shrink-0">
                            {{ $category['icon'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-extrabold text-slate-800 tracking-tight leading-tight">
                                Kategori {{ $category['code'] }}. {{ $category['name'] }}
                            </h3>
                            <div class="text-xs text-slate-400 font-medium mt-0.5">
                                {{ count($category['indicators']) }} indikator evaluasi
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Kartu Pertanyaan Kategori Ini --}}
                    <div class="space-y-3.5">
                        @foreach ($category['indicators'] as $indicator)
                            <x-survey-card
                                :number="$currentNumber++"
                                :indicator="$indicator"
                                :category="$category"
                            />
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Card: Saran / Masukan Umum --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 md:p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">Saran / Inputan Umum</h3>
                        <p class="text-xs text-slate-400">Sampaikan apresiasi, saran perbaikan, atau catatan strategis Anda (opsional)</p>
                    </div>
                </div>
                <textarea
                    id="saran-umum"
                    class="w-full min-h-[110px] rounded-xl border border-slate-200 bg-slate-50 p-3.5 text-xs md:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all resize-y leading-relaxed"
                    placeholder="Tuliskan saran umum atau evaluasi bebas Anda kepada PT. Len Railways Systems di sini..."
                    rows="4"
                ></textarea>
            </div>

            {{-- Progress & Tombol Submit Survei --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 md:p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between text-xs font-bold text-slate-600">
                    <span>Progress Pengisian Kuesioner</span>
                    <span class="text-[#003B73] font-extrabold" id="progress-text">0 / {{ $totalInputs ?? 42 }} diisi</span>
                </div>

                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#003B73] via-blue-600 to-blue-400 rounded-full transition-all duration-300"
                         id="progress-bar"
                         style="width: 0%;"></div>
                </div>

                <button type="submit"
                        class="w-full py-4 px-6 rounded-xl bg-[#003B73] hover:bg-[#002D59] text-white font-extrabold text-sm md:text-base shadow-lg shadow-[#003B73]/25 flex items-center justify-center gap-2.5 transition-all active:scale-[0.99] focus:outline-none"
                        id="btn-submit-survey">
                    <span>Kirim Survei Kepuasan Sekarang</span>
                </button>
            </div>

        </form>

    </div>
</div>
