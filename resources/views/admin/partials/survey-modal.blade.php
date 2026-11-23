{{-- =========================================================================
     MODAL DETAIL RINCIAN SURVEI KEPUASAN
     ========================================================================= --}}
<div id="surveyDetailModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-survey-ref">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-150">

        {{-- Header Modal --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-blue-50 text-[#003B73] font-bold flex items-center justify-center text-lg border border-blue-100 shadow-2xs">
                    📋
                </span>
                <div>
                    <div class="flex items-center gap-2">
                        <span id="modal-survey-ref" class="text-base font-black text-[#003B73]">SRV-XXXX</span>
                        <span id="modal-survey-category-badge" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border bg-blue-50 text-blue-700 border-blue-200">
                            Kategori
                        </span>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5" id="modal-survey-datetime">
                        Tanggal & Jam Submit
                    </div>
                </div>
            </div>

            <button type="button"
                    onclick="closeSurveyDetailModal()"
                    class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none cursor-pointer">
                ✕
            </button>
        </div>

        {{-- Body Modal (Scrollable) --}}
        <div class="p-6 overflow-y-auto space-y-5 text-slate-700 flex-1">

            {{-- Ringkasan Identitas Klien & Proyek --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-xl bg-slate-50/90 border border-slate-200/80 text-xs">
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Nama Klien / Responden</span>
                    <span id="modal-survey-client-name" class="font-extrabold text-slate-800 text-sm">Nama Klien</span>
                    <span id="modal-survey-client-role" class="block text-[11px] text-slate-500 font-medium mt-0.5">Jabatan / Instansi</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Proyek Pekerjaan</span>
                    <span id="modal-survey-project-name" class="font-extrabold text-[#003B73] text-sm">Nama Proyek</span>
                    <span id="modal-survey-personnel-name" class="block text-[11px] text-slate-500 font-medium mt-0.5">Personel Pendamping: -</span>
                </div>
            </div>

            {{-- Status Simulasi Konfirmasi Notifikasi Personel --}}
            <div class="p-3 rounded-xl bg-emerald-50/80 border border-emerald-200 flex items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                        ✓
                    </span>
                    <div>
                        <span class="font-bold text-emerald-800 block">Notifikasi Otomatis Terkirim</span>
                        <span id="modal-survey-notif-status" class="text-[11px] text-emerald-600">
                            Simulasi pesan telah diteruskan ke Personel Pendamping LRS via WhatsApp.
                        </span>
                    </div>
                </div>
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-emerald-200/60 text-emerald-800">
                    Terkonfirmasi
                </span>
            </div>

            {{-- Tabel Rincian Jawaban Indikator --}}
            <div>
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                    Rincian Penilaian Ganda (Harapan vs. Kepuasan)
                </h4>
                <div class="border border-slate-200/80 rounded-xl overflow-hidden shadow-2xs">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100/80 text-[10px] font-extrabold text-slate-600 uppercase tracking-wider border-b border-slate-200/80">
                                <th class="py-2.5 px-3">Kode</th>
                                <th class="py-2.5 px-3">Indikator Penilaian</th>
                                <th class="py-2.5 px-3 text-center">Harapan (1-5)</th>
                                <th class="py-2.5 px-3 text-center">Kepuasan (1-5)</th>
                                <th class="py-2.5 px-3 text-center">Kesesuaian</th>
                            </tr>
                        </thead>
                        <tbody id="modal-survey-answers-body" class="divide-y divide-slate-100 text-slate-700">
                            {{-- Diisi secara dinamis --}}
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Saran Umum / Catatan Responden --}}
            <div>
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Saran & Masukan Responden
                </h4>
                <div id="modal-survey-notes" class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 text-xs text-slate-600 leading-relaxed italic">
                    "Tidak ada catatan khusus yang disampaikan."
                </div>
            </div>

            {{-- Lampiran Dokumen (Jika Ada) --}}
            <div id="modal-survey-attachment-wrap" class="hidden">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Lampiran Dokumen Pendukung
                </h4>
                <div class="flex items-center gap-2 p-2.5 bg-slate-50 rounded-xl border border-slate-200/80 text-xs">
                    <span class="text-base">📎</span>
                    <span id="modal-survey-attachment-name" class="font-bold text-slate-700">dokumen.pdf</span>
                    <span class="text-[10px] text-slate-400 ml-auto font-medium">(Tersimpan di Sesi Lokal)</span>
                </div>
            </div>

        </div>

        {{-- Footer Modal --}}
        <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-end bg-slate-50/50">
            <button type="button"
                    onclick="closeSurveyDetailModal()"
                    class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-200 hover:bg-slate-300 text-slate-700 transition-colors cursor-pointer">
                Tutup
            </button>
        </div>

    </div>

</div>
