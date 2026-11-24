<div id="panel-masukan" class="panel" role="tabpanel" aria-labelledby="tab-masukan">
    <div class="space-y-6">

        {{-- Section Header --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)]">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-[#003B73] text-xs font-bold uppercase tracking-wider mb-2 border border-blue-100 shadow-2xs">
                <span>💬</span>
                <span id="txt-feedback-badge">Voice of Customer</span>
            </div>
            <h2 id="txt-feedback-title" class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                Sampaikan Masukan & Saran
            </h2>
            <p id="txt-feedback-subtitle" class="text-xs md:text-sm text-slate-500 mt-1 leading-relaxed">
                Tanggapan, apresiasi, atau masukan strategis Anda menjadi bahan pertimbangan penting bagi peningkatan layanan berkelanjutan.
            </p>
        </div>

        {{-- Form Masukan --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)]">
            <form id="form-masukan" novalidate class="space-y-5">

                {{-- Rating Bintang --}}
                <div class="space-y-2">
                    <label id="lbl-feedback-rating" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                        Penilaian Keseluruhan Layanan PT Len Railway Systems<span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="flex items-center gap-2" id="star-row">
                        @for ($s = 1; $s <= 5; $s++)
                            <button type="button"
                                    class="star-btn text-3xl text-slate-300 hover:text-amber-400 transition-colors focus:outline-none"
                                    data-val="{{ $s }}"
                                    onclick="setFeedbackStar({{ $s }})"
                                    aria-label="Bintang {{ $s }}">
                                ★
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" id="feedback-rating" value="0">
                </div>

                {{-- Kategori --}}
                <div class="space-y-1.5">
                    <label id="lbl-feedback-category" class="block text-xs font-bold text-slate-700 uppercase tracking-wide" for="f-kategori">
                        Kategori Masukan<span class="text-red-500 ml-1">*</span>
                    </label>
                    <select class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs md:text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs outline-none transition-all"
                            id="f-kategori"
                            required>
                        <option value="" id="opt-fb-placeholder">— Pilih kategori masukan —</option>
                        <option value="Layanan" id="opt-fb-service">Layanan & Komunikasi Personal</option>
                        <option value="Produk" id="opt-fb-product">Kualitas & Keandalan Produk</option>
                        <option value="Teknis" id="opt-fb-technical">Kinerja Teknis & Operasional</option>
                        <option value="K3L" id="opt-fb-hse">Penerapan K3L & Keselamatan Kerja</option>
                        <option value="Lainnya" id="opt-fb-others">Lainnya</option>
                    </select>
                </div>

                {{-- Subjek --}}
                <div class="space-y-1.5">
                    <label id="lbl-feedback-subject" class="block text-xs font-bold text-slate-700 uppercase tracking-wide" for="f-subjek">
                        Subjek Masukan<span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs md:text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs outline-none transition-all"
                           id="f-subjek"
                           placeholder="Contoh: Kecepatan respon tim teknis interlocking"
                           required>
                </div>

                {{-- Isi Masukan --}}
                <div class="space-y-1.5">
                    <label id="lbl-feedback-content" class="block text-xs font-bold text-slate-700 uppercase tracking-wide" for="f-isi">
                        Detail Masukan & Apresiasi<span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea class="w-full min-h-[110px] px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs md:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs outline-none transition-all resize-y leading-relaxed"
                              id="f-isi"
                              placeholder="Tuliskan pengalaman atau saran spesifik Anda..."
                              required></textarea>
                </div>

                <button type="submit"
                        id="btn-submit-feedback"
                        class="w-full py-3.5 px-6 rounded-xl bg-[#003B73] hover:bg-[#002D59] text-white font-extrabold text-sm shadow-md shadow-[#003B73]/25 hover:shadow-lg hover:shadow-[#003B73]/35 flex items-center justify-center gap-2 transition-all active:scale-[0.99] focus:outline-none">
                    <span>✉️</span>
                    <span id="txt-btn-submit-feedback">Kirim Masukan Anda</span>
                </button>
            </form>
        </div>

    </div>
</div>

