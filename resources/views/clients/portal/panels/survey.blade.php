<div id="panel-survei" class="panel active" role="tabpanel" aria-labelledby="tab-survei">
    <div class="space-y-6">

        @php
            $categories = $categories ?? config('survey.categories', []);

            // Resolusi gambar header card dengan fallback rapi
            $resolveCardImage = function ($catCode) {
                $candidates = [
                    'A' => ['dukunganLayanan.jpg', 'dukungan-layanan.jpg', 'dukunganLayanan.png'],
                    'B' => ['pelaksanaanProyek.jpg', 'pelaksanaan-proyek.jpg', 'pelaksanaanProyek.png'],
                    'C' => ['masaPemeliharaan.jpg', 'masa-pemeliharaan.jpg', 'masaPemeliharaan.png'],
                ];

                foreach ($candidates[$catCode] ?? [] as $file) {
                    if (file_exists(public_path('images/card/' . $file))) {
                        return asset('images/card/' . $file);
                    }
                }

                // Fallback default jika file belum ada di images/card/
                return asset('images/bg-railway-control.jpg');
            };
        @endphp

        {{-- =========================================================================
             LANGKAH 1: PEMILIHAN KATEGORI (PROGRESSIVE DISCLOSURE)
             Hanya menampilkan 3 kartu pilihan interaktif.
             21 indikator tidak ditampilkan sekaligus.
             ========================================================================= --}}
        <div id="survey-step-1-category" class="space-y-6">

            {{-- Header Langkah 1 --}}
            <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 md:p-7 shadow-[0_2px_12px_rgba(15,23,42,0.04)]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">
                            Pilih Kategori Survei Kepuasan
                        </h3>
                        <p class="text-xs md:text-sm text-slate-500 mt-1.5 leading-relaxed max-w-3xl">
                            Silakan pilih kategori layanan yang ingin Anda evaluasi terlebih dahulu. Indikator pertanyaan penilaian ganda akan ditampilkan secara bertahap.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Grid 3 Kartu Kategori Interaktif (Desain 3 Lapis: Full-Bleed + Soft Blur + Gradient Overlay Elegan) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-7 items-stretch">
                @foreach ($categories as $cat)
                    @php
                        $cardImgUrl = $resolveCardImage($cat['code']);
                    @endphp
                    <div class="category-select-card relative overflow-hidden rounded-3xl min-h-[280px] sm:min-h-[360px] lg:min-h-[420px] flex flex-col justify-between p-6 lg:p-7 shadow-[0_10px_30px_-10px_rgba(0,59,115,0.18)] border border-white/15 ring-1 ring-slate-900/5 group cursor-pointer transition-all duration-300 hover:shadow-2xl hover:-translate-y-1.5"
                         id="cat-card-{{ $cat['code'] }}"
                         onclick="selectCategory('{{ $cat['code'] }}')">

                        {{-- LAPISAN 1 — GAMBAR FULL-BLEED + SOFT BLUR + OBJECT-TOP --}}
                        <img src="{{ $cardImgUrl }}"
                             alt="Kategori {{ $cat['code'] }}: {{ $cat['name'] }}"
                             loading="lazy"
                             class="absolute inset-0 w-full h-full object-cover object-top scale-105 blur-[1px] transition-all duration-700 ease-out group-hover:scale-110 group-hover:blur-0"
                             onerror="this.onerror=null; this.src='{{ asset('images/bg-railway-control.jpg') }}'">

                        {{-- LAPISAN 2 — GRADIENT OVERLAY (KONTRAST TAJAM & MELEMBUT KE TENGAH) --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#001A38]/95 via-[#002E63]/70 via-45% to-transparent transition-opacity duration-500 pointer-events-none"></div>

                        {{-- LAPISAN 3 — KONTEN CARD (relative z-10) --}}
                        {{-- Bagian Atas: Badge Glassmorphism --}}
                        <!-- <div class="relative z-10 flex justify-end gap-2 items-center">
                            {{-- Badge Jumlah Indikator --}}
                            <span class="bg-white/20 backdrop-blur-md text-white border border-white/30 text-xs font-semibold px-3.5 py-1 rounded-full shadow-xs">
                                {{ count($cat['indicators']) }} Indikator
                            </span>

                            {{-- Badge Status Selesai / Terisi (Diperbarui via JS) --}}
                            <span class="cat-completed-badge hidden bg-emerald-500/90 text-white border border-emerald-400/50 text-xs font-semibold px-3 py-1 rounded-full shadow-xs flex items-center gap-1"
                                  id="cat-status-{{ $cat['code'] }}">
                                ✓ Sudah Diisi
                            </span>
                        </div> -->

                        {{-- Bagian Tengah & Bawah: Informasi Kategori & Tombol Aksi --}}
                        <div class="relative z-10 mt-auto pt-6">
                            {{-- Judul Kategori --}}
                            <h4 class="text-xl lg:text-2xl font-bold tracking-tight text-white mt-1 leading-snug">
                                {{ $cat['name'] }}
                            </h4>

                            {{-- Deskripsi Singkat Kategori --}}
                            <p class="text-sm text-slate-200/95 leading-relaxed mt-2 line-clamp-3">
                                {{ $cat['description'] ?? 'Evaluasi performa mutu dan kepuasan pada aspek ' . $cat['name'] . '.' }}
                            </p>

                            {{-- Footer Tombol "Pilih Kategori Ini" + Panah Bulat --}}
                            <div class="border-t border-white/15 pt-4 mt-6 flex items-center justify-between">
                                <span class="text-xs md:text-sm font-semibold text-white">
                                    Pilih Kategori Ini
                                </span>
                                <div class="w-8 h-8 rounded-full bg-white/15 backdrop-blur-sm flex items-center justify-center text-white transition-all duration-300 group-hover:translate-x-1.5 group-hover:bg-white group-hover:text-[#002E63] shadow-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Informasi Keamanan & Kerahasiaan Data --}}
            <div class="bg-blue-50/70 rounded-2xl border border-blue-150 p-4 sm:p-5 text-xs text-slate-600 flex items-center gap-3">
                <p class="leading-relaxed">
                    Data evaluasi Anda dilindungi dan digunakan secara internal oleh PT. Len Railways Systems untuk peningkatan keandalan produk dan layanan berkelanjutan.
                </p>
            </div>

        </div>

        {{-- =========================================================================
             LANGKAH 2: FORMULIR INDIKATOR DINAMIS
             Hanya menampilkan indikator dari kategori yang dipilih oleh pengguna.
             ========================================================================= --}}
        <div id="survey-step-2-form" class="hidden space-y-6">

            {{-- Top Bar Langkah 2: Tombol Ganti Kategori & Info Kategori Terpilih (Sticky Header) --}}
            <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/90 p-4 sm:p-5 md:p-6 shadow-[0_4px_20px_-4px_rgba(0,43,85,0.08)] sticky top-[104px] sm:top-[66px] md:top-[74px] z-20 transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    {{-- Tombol Ganti Kategori --}}
                    <button type="button"
                            id="btn-change-category"
                            onclick="backToCategorySelection()"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-extrabold text-[#003B73] bg-blue-50 hover:bg-blue-100 border border-blue-200 transition-all self-start sm:self-auto shadow-2xs active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Ganti Kategori</span>
                    </button>

                    {{-- Badge Status Kategori Aktif --}}
                    <div class="flex items-center gap-2 self-start sm:self-auto">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Langkah 2:</span>
                        <span id="active-category-badge" class="px-2.5 py-1 rounded-lg text-xs font-black bg-[#003B73] text-white">
                            Layanan & Dukungan
                        </span>
                    </div>
                </div>

                {{-- Judul & Deskripsi Kategori Aktif --}}
                <div class="pt-3 flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div>
                        <h3 id="active-category-title" class="text-base md:text-lg font-black text-slate-800 tracking-tight leading-snug">
                            Layanan & Dukungan
                        </h3>
                        <p id="active-category-desc" class="text-xs text-slate-500 mt-0.5">
                            Mohon berikan penilaian Kepentingan dan Kepuasan pada indikator di bawah ini:
                        </p>
                    </div>

                    {{-- Counter Indikator Kategori Aktif --}}
                    <div class="shrink-0 flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500">Progress:</span>
                        <span id="active-category-counter" class="text-xs font-black text-[#003B73] bg-slate-100 px-2.5 py-1 rounded-lg">
                            0 / 5 Terisi
                        </span>
                    </div>
                </div>

                {{-- Progress Bar Kategori Aktif --}}
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden mt-3 border border-slate-200/50">
                    <div id="active-category-progress-bar"
                         class="h-full bg-gradient-to-r from-[#003B73] via-blue-600 to-sky-400 rounded-full transition-all duration-300"
                         style="width: 0%;"></div>
                </div>
            </div>

            {{-- Formulir Survei (Dikirim via Vanilla JS) --}}
            <form id="form-survei" novalidate class="space-y-6">

                {{-- Grup Kontainer Indikator per Kategori (Hanya 1 grup yang aktif/tampil) --}}
                @foreach ($categories as $category)
                    <div id="category-group-{{ $category['code'] }}" class="category-indicators-group hidden space-y-4">
                        @php $currentNumber = 1; @endphp
                        @foreach ($category['indicators'] as $indicator)
                            <x-survey-card
                                :number="$currentNumber++"
                                :indicator="$indicator"
                                :category="$category"
                            />
                        @endforeach
                    </div>
                @endforeach

                {{-- =====================================================================
                     SARAN / INPUTAN UMUM (OPSIONAL)
                     Textarea untuk saran, apresiasi, atau masukan umum kepada PT LRS
                     ===================================================================== --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)] space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm md:text-base font-extrabold text-slate-800">
                                Saran / Inputan Umum
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Sampaikan apresiasi, saran perbaikan, atau catatan strategis Anda kepada PT. Len Railways Systems (opsional).
                            </p>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-2.5 py-1 rounded-lg">
                            Opsional
                        </span>
                    </div>

                    <textarea
                        id="saran-umum"
                        name="saran_umum"
                        rows="4"
                        class="w-full min-h-[110px] rounded-xl border border-slate-200 bg-slate-50/80 p-3.5 text-xs md:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 shadow-2xs outline-none transition-all resize-y leading-relaxed"
                        placeholder="Tuliskan saran umum atau evaluasi bebas Anda kepada PT. Len Railways Systems di sini..."></textarea>
                </div>

                {{-- =====================================================================
                     UPLOAD FILE (DUMMY) LAMPIRAN PENDUKUNG
                     Hanya mengambil dan menyimpan nama file ke localStorage
                     ===================================================================== --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)] space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm md:text-base font-extrabold text-slate-800">
                                Upload File / Dokumen Pendukung (Dummy)
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Lampirkan dokumen, berita acara, atau foto pendukung evaluasi (opsional).
                            </p>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-2.5 py-1 rounded-lg">
                            Opsional
                        </span>
                    </div>

                    {{-- Hidden Native File Input --}}
                    <input type="file"
                           id="survey-attachment-input"
                           class="hidden"
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                           onchange="handleSurveyFileChange(event)">

                    {{-- Dropzone / Upload Trigger Button --}}
                    <div id="survey-upload-dropzone"
                         onclick="document.getElementById('survey-attachment-input').click()"
                         class="border-2 border-dashed border-slate-200 hover:border-[#003B73] hover:bg-blue-50/40 rounded-xl p-6 text-center cursor-pointer transition-all duration-200 group">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-blue-50 group-hover:bg-[#003B73] text-[#003B73] group-hover:text-white flex items-center justify-center text-xl mb-2 transition-all">
                            📎
                        </div>
                        <div class="text-xs md:text-sm font-bold text-slate-700 group-hover:text-[#003B73] transition-colors">
                            Klik di sini untuk memilih file lampiran
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Mendukung PDF, PNG, JPG, DOCX (Maks. 10 MB · Nama file akan dicatat)
                        </p>
                    </div>

                    {{-- Preview File Terpilih (Default Hidden) --}}
                    <div id="survey-file-preview" class="hidden items-center justify-between p-3.5 rounded-xl bg-blue-50/70 border border-blue-200 transition-all">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-[#003B73] text-white flex items-center justify-center text-sm shrink-0">
                                📄
                            </div>
                            <div class="min-w-0">
                                <div id="attached-filename" class="text-xs md:text-sm font-bold text-slate-800 truncate">
                                    dokumen.pdf
                                </div>
                                <div id="attached-filesize" class="text-[11px] text-slate-500">
                                    Siap dilampirkan
                                </div>
                            </div>
                        </div>
                        <button type="button"
                                onclick="removeSurveyFile(event)"
                                class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors shrink-0">
                            Hapus File
                        </button>
                    </div>
                </div>

                {{-- =====================================================================
                     TOMBOL SUBMIT SURVEI
                     ===================================================================== --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.05)] space-y-4">
                    <button type="submit"
                            id="btn-submit-survey"
                            class="w-full py-4 px-6 rounded-xl bg-[#003B73] hover:bg-[#002D59] text-white font-black text-sm md:text-base shadow-md shadow-[#003B73]/25 hover:shadow-lg hover:shadow-[#003B73]/35 flex items-center justify-center gap-2.5 transition-all duration-200 active:scale-[0.99] focus:outline-none">
                        <span>Kirim Survei</span>
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>

            </form>

        </div>

        {{-- =========================================================================
             LANGKAH 3: TAMPILAN SUKSES ("TERIMA KASIH")
             Muncul setelah submit berhasil disimpan ke localStorage (lrs_survey_data).
             Menyediakan 2 tombol aksi: "Isi Kategori Lain" dan "Kembali ke Beranda".
             ========================================================================= --}}
        <div id="survey-success-container" class="hidden">
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 md:p-10 shadow-[0_4px_24px_rgba(15,23,42,0.06)] text-center max-w-2xl mx-auto space-y-6">

                {{-- Icon Sukses --}}
                <div class="w-20 h-20 mx-auto rounded-3xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-4xl shadow-sm text-emerald-600">
                    🎉
                </div>

                {{-- Pesan Terima Kasih --}}
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-emerald-100/70 text-emerald-800 border border-emerald-200">
                        ✓ Survei Berhasil Terkirim
                    </span>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
                        Terima Kasih!
                    </h3>
                    <p class="text-xs md:text-sm text-slate-500 max-w-md mx-auto leading-relaxed">
                        Penilaian Anda untuk kategori <strong id="success-category-name" class="text-slate-800">Layanan & Dukungan</strong> telah berhasil disimpan dalam sistem kami.
                    </p>
                </div>

                {{-- Ringkasan Payload yang Disimpan --}}
                <div class="bg-slate-50 rounded-2xl border border-slate-200/80 p-5 text-left text-xs space-y-3">
                    <div class="font-extrabold text-[#003B73] uppercase tracking-wider text-[11px] pb-2 border-b border-slate-200 flex items-center justify-between">
                        <span>Ringkasan Data Survei</span>
                        <span class="text-slate-400 font-semibold" id="success-submitted-time">-</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-600">
                        <div>
                            <span class="text-slate-400 font-semibold block text-[10px]">ID RESPONDEN</span>
                            <span id="success-respondent-id" class="font-bold text-slate-800 text-sm">PIC-001</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block text-[10px]">PROYEK</span>
                            <span id="success-project-name" class="font-bold text-slate-800 text-sm truncate block">Proyek Dummy</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block text-[10px]">KATEGORI</span>
                            <span id="success-category-summary" class="font-bold text-slate-800 text-sm">A. Layanan & Dukungan</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block text-[10px]">FILE LAMPIRAN</span>
                            <span id="success-attachment-name" class="font-bold text-slate-800 text-sm truncate block">-</span>
                        </div>
                    </div>
                </div>

                {{-- 2 Tombol Aksi Sesuai Spesifikasi PRD --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                    <button type="button"
                            id="btn-fill-other-category"
                            onclick="actionFillOtherCategory()"
                            class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-[#003B73] hover:bg-[#002D59] text-white font-extrabold text-xs md:text-sm shadow-md shadow-[#003B73]/20 flex items-center justify-center gap-2 transition-all active:scale-95">
                        <span>📋</span>
                        <span>Isi Kategori Lain</span>
                    </button>

                    <button type="button"
                            id="btn-back-to-home"
                            onclick="actionBackToHome()"
                            class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs md:text-sm transition-all flex items-center justify-center gap-2 active:scale-95">
                        <span>🏠</span>
                        <span>Kembali ke Beranda</span>
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>
