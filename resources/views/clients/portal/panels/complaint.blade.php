<div id="panel-keluhan" class="panel" role="tabpanel" aria-labelledby="tab-keluhan">
    <div class="space-y-6">

        {{-- Section Header --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)]">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-[#003B73] text-xs font-bold uppercase tracking-wider mb-2 border border-blue-100 shadow-2xs">
                <span>🔔</span>
                <span id="txt-complaint-badge">Incident & Complaint Center</span>
            </div>
            <h2 id="txt-complaint-title" class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                Buat Keluhan & Laporan Kendala
            </h2>
            <p id="txt-complaint-subtitle" class="text-xs md:text-sm text-slate-500 mt-1 leading-relaxed">
                Setiap kendala yang Anda sampaikan akan langsung diterbitkan nomor tiket dan ditangani oleh unit terkait PT Len Railway Systems.
            </p>
        </div>

        {{-- Form Keluhan --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)]">
            <form id="form-keluhan" novalidate class="space-y-5">

                {{-- Kategori --}}
                <div class="space-y-1.5">
                    <label id="lbl-complaint-category" class="block text-xs font-bold text-slate-700 uppercase tracking-wide" for="k-kategori">
                        Kategori Kendala<span class="text-red-500 ml-1">*</span>
                    </label>
                    <select class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs md:text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs outline-none transition-all"
                            id="k-kategori"
                            required>
                        <option value="" id="opt-cat-placeholder">— Pilih kategori kendala —</option>
                        <option value="Layanan" id="opt-cat-service">Layanan & Koordinasi</option>
                        <option value="Produk" id="opt-cat-product">Produk & Material Persinyalan</option>
                        <option value="Teknis" id="opt-cat-technical">Pekerjaan Lapangan & Teknis</option>
                        <option value="K3L" id="opt-cat-hse">Insiden / Kepatuhan K3L</option>
                        <option value="Lainnya" id="opt-cat-others">Lainnya</option>
                    </select>
                </div>

                {{-- Judul --}}
                <div class="space-y-1.5">
                    <label id="lbl-complaint-subject" class="block text-xs font-bold text-slate-700 uppercase tracking-wide" for="k-subjek">
                        Judul Kendala / Keluhan<span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs md:text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs outline-none transition-all"
                           id="k-subjek"
                           placeholder="Contoh: Keterlambatan pengiriman modul power supply stasiun X"
                           required>
                </div>

                {{-- Tingkat Urgensi (Radio Grid) --}}
                <div class="space-y-2">
                    <label id="lbl-complaint-urgency" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                        Tingkat Urgensi Penanganan<span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5">
                        <label class="relative flex items-center gap-2 p-3 rounded-xl border border-slate-200/90 bg-slate-50/80 hover:bg-white hover:border-slate-300 shadow-2xs hover:shadow-xs cursor-pointer transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/80 has-[:checked]:text-[#003B73] has-[:checked]:shadow-xs">
                            <input type="radio" name="urgency" value="Rendah" class="sr-only">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                            <span class="text-xs font-bold urgency-label" data-urgency="Rendah">Rendah</span>
                        </label>

                        <label class="relative flex items-center gap-2 p-3 rounded-xl border border-slate-200/90 bg-slate-50/80 hover:bg-white hover:border-slate-300 shadow-2xs hover:shadow-xs cursor-pointer transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/80 has-[:checked]:text-[#003B73] has-[:checked]:shadow-xs">
                            <input type="radio" name="urgency" value="Sedang" class="sr-only">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                            <span class="text-xs font-bold urgency-label" data-urgency="Sedang">Sedang</span>
                        </label>

                        <label class="relative flex items-center gap-2 p-3 rounded-xl border border-slate-200/90 bg-slate-50/80 hover:bg-white hover:border-slate-300 shadow-2xs hover:shadow-xs cursor-pointer transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/80 has-[:checked]:text-[#003B73] has-[:checked]:shadow-xs">
                            <input type="radio" name="urgency" value="Tinggi" class="sr-only">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                            <span class="text-xs font-bold urgency-label" data-urgency="Tinggi">Tinggi</span>
                        </label>

                        <label class="relative flex items-center gap-2 p-3 rounded-xl border border-slate-200/90 bg-slate-50/80 hover:bg-white hover:border-slate-300 shadow-2xs hover:shadow-xs cursor-pointer transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/80 has-[:checked]:text-[#003B73] has-[:checked]:shadow-xs">
                            <input type="radio" name="urgency" value="Kritis" class="sr-only">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-600 shrink-0"></span>
                            <span class="text-xs font-bold urgency-label" data-urgency="Kritis">Kritis</span>
                        </label>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-1.5">
                    <label id="lbl-complaint-desc" class="block text-xs font-bold text-slate-700 uppercase tracking-wide" for="k-deskripsi">
                        Deskripsi Rinci Kendala<span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea class="w-full min-h-[120px] px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs md:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs outline-none transition-all resize-y leading-relaxed"
                              id="k-deskripsi"
                              placeholder="Uraikan lokasi stasiun/lintas, kronologi, serta dampak operasional yang dirasakan..."
                              required></textarea>
                </div>

                {{-- Upload File Dummy --}}
                <div class="space-y-1.5">
                    <label id="lbl-complaint-upload" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                        Lampiran Berkas / Foto (Opsional)
                    </label>
                    <input type="file"
                           id="complaint-attachment-input"
                           class="hidden"
                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                           onchange="handleComplaintFileChange(event)">
                    <div id="complaint-upload-dropzone"
                         class="border-2 border-dashed border-slate-200 rounded-xl p-5 text-center bg-slate-50/70 hover:bg-blue-50/40 hover:border-blue-300 shadow-2xs transition-all cursor-pointer"
                         onclick="document.getElementById('complaint-attachment-input').click()">
                        <div class="text-2xl mb-1">📎</div>
                        <div id="complaint-upload-text" class="text-xs font-bold text-slate-700">Klik untuk melampirkan dokumen / gambar kendala</div>
                        <div id="complaint-upload-hint" class="text-[11px] text-slate-400 mt-0.5">Format JPG, PNG, PDF (Maks. 10 MB — nama file akan dicatat)</div>
                    </div>
                    {{-- Preview file --}}
                    <div id="complaint-file-preview" class="hidden items-center justify-between p-3 rounded-xl bg-purple-50/70 border border-purple-200 text-xs">
                        <div class="flex items-center gap-2 truncate">
                            <span>📄</span>
                            <span id="complaint-attached-filename" class="font-bold text-slate-800 truncate">file.pdf</span>
                        </div>
                        <button type="button" onclick="removeComplaintFile(event)" class="text-rose-600 font-bold hover:underline shrink-0">Hapus</button>
                    </div>
                </div>

                <button type="submit"
                        id="btn-submit-complaint"
                        class="w-full py-4 px-6 rounded-xl bg-gradient-to-r from-purple-700 to-indigo-600 hover:from-purple-800 hover:to-indigo-700 text-white font-extrabold text-sm shadow-md shadow-purple-600/20 hover:shadow-lg hover:shadow-purple-600/30 flex items-center justify-center gap-2 transition-all active:scale-[0.99] focus:outline-none">
                    <span>🎫</span>
                    <span id="txt-btn-submit-complaint">Submit Keluhan & Terbitkan Tiket</span>
                </button>
            </form>

            {{-- Kartu Hasil Tiket yang Diterbitkan --}}
            <div id="tiket-result" class="ticket-result mt-4 rounded-xl p-4 hidden" aria-live="polite"></div>
        </div>

        {{-- Lacak Tiket --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)]">
            <h3 id="txt-track-header" class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-3">
                🔍 Pelacakan Status Tiket Keluhan
            </h3>
            <div class="flex items-center gap-2">
                <input type="text"
                       class="flex-1 px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs md:text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs outline-none transition-all uppercase"
                       id="track-input"
                       placeholder="Contoh: #TKT-1234">
                <button type="button"
                        id="btn-track-ticket"
                        class="px-5 py-3 rounded-xl bg-[#003B73] hover:bg-[#002D59] text-white text-xs md:text-sm font-bold shadow-md shadow-[#003B73]/20 hover:shadow-lg hover:shadow-[#003B73]/30 shrink-0 transition-all active:scale-[0.98]"
                        onclick="trackTicket()">
                    <span id="txt-btn-track">Lacak Tiket</span>
                </button>
            </div>

            {{-- Kartu Hasil Lacak --}}
            <div id="track-result" class="ticket-result mt-4 rounded-xl p-4 hidden" aria-live="polite"></div>
        </div>

    </div>
</div>

