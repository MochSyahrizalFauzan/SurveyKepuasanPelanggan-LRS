{{-- =========================================================================
     MODAL DETAIL PENANGANAN KELUHAN
     ========================================================================= --}}
<div id="ticketModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-ticket-id">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-150">

        {{-- Header Modal --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-blue-50 text-[#003B73] font-bold flex items-center justify-center text-lg">
                    🎫
                </span>
                <div>
                    <div class="flex items-center gap-2">
                        <span id="modal-ticket-id" class="text-base font-black text-[#003B73]">#TKT-XXXX</span>
                        <span id="modal-status-badge" class="px-2.5 py-0.5 rounded-full text-xs font-extrabold border bg-rose-50 text-rose-700 border-rose-200">
                            Masuk
                        </span>
                        <span id="modal-priority-badge" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                            Prioritas
                        </span>
                    </div>
                    <div id="modal-project-name" class="text-xs font-semibold text-slate-500 mt-0.5">Nama Proyek</div>
                </div>
            </div>

            {{-- Tombol Tutup --}}
            <button type="button"
                    onclick="closeTicketModal()"
                    class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none">
                ✕
            </button>
        </div>

        {{-- Body Modal (Scrollable) --}}
        <div class="p-6 overflow-y-auto space-y-5 text-slate-700 flex-1">

            {{-- Detail Keluhan --}}
            <div class="space-y-1.5">
                <h4 id="modal-subject" class="text-sm md:text-base font-extrabold text-slate-800 leading-snug">
                    Judul Keluhan
                </h4>
                <div class="p-3.5 bg-slate-50/90 rounded-xl border border-slate-200/80 shadow-2xs text-xs md:text-sm text-slate-600 leading-relaxed whitespace-pre-line" id="modal-description">
                    Deskripsi keluhan...
                </div>
                <div class="flex flex-wrap items-center gap-4 text-[11px] text-slate-400 font-medium pt-1">
                    <span>📅 Dilaporkan: <strong id="modal-created-at" class="text-slate-600">-</strong></span>
                    <span>🏷️ Kategori: <strong id="modal-category" class="text-slate-600">-</strong></span>
                    <span>⏱️ Batas Waktu: <strong id="modal-sla-text" class="text-slate-600">-</strong></span>
                </div>
            </div>

            {{-- Riwayat Penanganan --}}
            <div class="pt-2 border-t border-slate-100">
                <h5 class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                    <span>🕒</span>
                    <span>Riwayat Penanganan</span>
                </h5>
                <div id="modal-audit-trail" class="space-y-2 text-xs">
                    {{-- Dinamis via JS --}}
                </div>
            </div>

            {{-- Form Pemeriksaan & Langkah Perbaikan --}}
            <div class="pt-3 border-t border-slate-100 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#003B73]">
                        Pemeriksaan & Tindak Lanjut Masalah
                    </span>
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100 shadow-2xs">
                        Wajib Diisi Sebelum Selesai
                    </span>
                </div>

                {{-- Penyebab Masalah --}}
                <div class="space-y-1">
                    <label for="modal-rca-input" class="block text-xs font-bold text-slate-700">
                        Penyebab Masalah
                    </label>
                    <textarea id="modal-rca-input"
                              rows="3"
                              placeholder="Uraikan penyebab masalah yang ditemukan berdasarkan pemeriksaan tim teknis..."
                              class="w-full bg-slate-50/80 border border-slate-200 rounded-xl p-3 text-xs md:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 shadow-2xs transition-all"></textarea>
                </div>

                {{-- Langkah Perbaikan yang Dilakukan --}}
                <div class="space-y-1">
                    <label for="modal-capa-input" class="block text-xs font-bold text-slate-700">
                        Langkah Perbaikan yang Dilakukan
                    </label>
                    <textarea id="modal-capa-input"
                              rows="3"
                              placeholder="Uraikan tindakan perbaikan langsung dan pencegahan agar masalah serupa tidak terulang..."
                              class="w-full bg-slate-50/80 border border-slate-200 rounded-xl p-3 text-xs md:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 shadow-2xs transition-all"></textarea>
                </div>

                {{-- PIC & Status --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label for="modal-pic-input" class="block text-xs font-bold text-slate-700">
                            Penanggung Jawab (PIC)
                        </label>
                        <input type="text"
                               id="modal-pic-input"
                               placeholder="Contoh: Ir. Hendra (Divisi Persinyalan)"
                               class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3 py-2 text-xs md:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 shadow-2xs transition-all">
                    </div>

                    <div class="space-y-1">
                        <label for="modal-status-select" class="block text-xs font-bold text-slate-700">
                            Perbarui Status Tiket
                        </label>
                        <select id="modal-status-select"
                                class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3 py-2 text-xs md:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 shadow-2xs transition-all">
                            <option value="Open">Masuk</option>
                            <option value="In Progress">Dalam Proses</option>
                            <option value="Resolved">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer Modal --}}
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/70 flex flex-wrap items-center justify-between gap-3">
            <button type="button"
                    onclick="closeTicketModal()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200/90 text-xs font-bold text-slate-600 hover:bg-slate-100 shadow-2xs transition-all">
                Batal
            </button>

            <div class="flex items-center gap-2">
                <button type="button"
                        onclick="saveTicketAnalysis()"
                        class="px-5 py-2.5 rounded-xl bg-[#003B73] hover:bg-[#002B54] text-white text-xs font-bold shadow-md shadow-[#003B73]/20 hover:shadow-lg hover:shadow-[#003B73]/30 transition-all flex items-center gap-1.5 active:scale-95">
                    <span>💾</span>
                    <span>Simpan & Perbarui</span>
                </button>
            </div>
        </div>

    </div>

</div>
