{{-- =========================================================================
     KOMPONEN ADMIN: MODAL EDIT METADATA RESPONDEN SURVEI
     Responsive: Centered Modal (Desktop/Tablet) <-> Bottom-Sheet (Mobile)
     ========================================================================= --}}
<div id="editSurveyMetadataModal"
     class="fixed inset-0 z-50 hidden items-end sm:items-center justify-center bg-slate-900/40 backdrop-blur-xs sm:p-4 overflow-y-auto opacity-0 transition-opacity duration-200"
     role="dialog"
     aria-modal="true"
     aria-labelledby="edit-metadata-modal-title">

    <div id="edit-metadata-modal-panel"
         class="relative w-full max-w-lg bg-white rounded-t-2xl sm:rounded-2xl border border-slate-200/90 shadow-2xl overflow-hidden transform translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-200 max-h-[92vh] flex flex-col">

        {{-- Garis Aksen Korporat LRS --}}
        <div class="h-1 w-full bg-gradient-to-r from-[#003B73] via-sky-500 to-[#D32F2F] shrink-0" aria-hidden="true"></div>

        {{-- Header Modal --}}
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between gap-3 shrink-0">
            <div class="flex flex-wrap items-center gap-2 min-w-0">
                <h2 id="edit-metadata-modal-title" class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight">
                    Edit Metadata Responden
                </h2>
                <span id="edit-meta-ref-badge"
                      class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-blue-50 text-[#003B73] border border-blue-200 select-all">
                    SRV-XXXX
                </span>
            </div>

            <button type="button"
                    id="btn-close-edit-metadata"
                    onclick="closeEditSurveyMetadataModal()"
                    title="Tutup Modal"
                    aria-label="Tutup Modal Edit Metadata Responden"
                    class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors focus:outline-none cursor-pointer shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body Form (Scrollable) --}}
        <form id="form-edit-survey-metadata" class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-4 text-xs">
            {{-- 1. Proyek Pekerjaan --}}
            <div class="space-y-1.5">
                <label for="edit-meta-project" class="block font-bold text-slate-700 uppercase tracking-wide text-[11px]">
                    Proyek Pekerjaan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select id="edit-meta-project"
                            required
                            class="w-full appearance-none bg-slate-50/90 hover:bg-white border border-slate-200/90 rounded-xl px-3.5 py-2.5 pr-8 text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all cursor-pointer">
                        <option value="GENERAL">Survei Umum (Non-Kontrak)</option>
                    </select>
                    <svg class="w-4 h-4 text-slate-400 pointer-events-none absolute right-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Nama Klien / Responden & Jabatan/Instansi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="space-y-1.5">
                    <label for="edit-meta-client" class="block font-bold text-slate-700 uppercase tracking-wide text-[11px]">
                        Nama Klien / Responden <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="edit-meta-client"
                           required
                           placeholder="Nama PIC / Klien"
                           class="w-full bg-slate-50/90 border border-slate-200/90 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                    <p id="err-edit-meta-client" class="hidden text-[11px] font-bold text-rose-600">
                        Nama klien atau responden wajib diisi.
                    </p>
                </div>

                <div class="space-y-1.5">
                    <label for="edit-meta-client-role" class="block font-bold text-slate-700 uppercase tracking-wide text-[11px]">
                        Instansi / Jabatan <span class="text-slate-400 font-semibold normal-case">(Opsional)</span>
                    </label>
                    <input type="text"
                           id="edit-meta-client-role"
                           placeholder="Contoh: Project Director / PT KAI"
                           class="w-full bg-slate-50/90 border border-slate-200/90 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                </div>
            </div>

            {{-- 2. Kategori Survei --}}
            <div class="space-y-2">
                <label for="edit-meta-category" class="block font-bold text-slate-700 uppercase tracking-wide text-[11px]">
                    Kategori Survei <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select id="edit-meta-category"
                            required
                            class="w-full appearance-none bg-slate-50/90 hover:bg-white border border-slate-200/90 rounded-xl px-3.5 py-2.5 pr-8 text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all cursor-pointer">
                        @foreach (config('survey.categories', []) as $category)
                            <option value="{{ $category['code'] }}">
                                Kategori {{ $category['code'] }} · {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 text-slate-400 pointer-events-none absolute right-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Peringatan Halus Perubahan Kategori --}}
                <div id="edit-meta-category-warning"
                     role="note"
                     class="hidden rounded-xl bg-amber-50/90 border border-amber-200 p-3 flex items-start gap-2.5 text-amber-800">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-[11px] font-semibold leading-relaxed">
                        Mengubah kategori akan memengaruhi pengelompokan data pada counter dan perhitungan CSI.
                    </p>
                </div>
            </div>

            {{-- 3. Personel Pendamping --}}
            <div class="space-y-1.5">
                <label for="edit-meta-personnel" class="block font-bold text-slate-700 uppercase tracking-wide text-[11px]">
                    Personel Pendamping LRS <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       id="edit-meta-personnel"
                       list="edit-meta-personnel-list"
                       required
                       placeholder="Pilih atau ketik nama personel pendamping..."
                       class="w-full bg-slate-50/90 border border-slate-200/90 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                <datalist id="edit-meta-personnel-list"></datalist>
                <p id="err-edit-meta-personnel" class="hidden text-[11px] font-bold text-rose-600">
                    Personel pendamping wajib diisi.
                </p>
            </div>
        </form>

        {{-- Footer Tombol Aksi --}}
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/80 flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-2.5 shrink-0">
            <button type="button"
                    id="btn-cancel-edit-metadata"
                    onclick="closeEditSurveyMetadataModal()"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer">
                Batal
            </button>
            <button type="submit"
                    form="form-edit-survey-metadata"
                    id="btn-save-edit-metadata"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#003B73] hover:bg-[#002B54] shadow-sm shadow-[#003B73]/25 transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>
</div>
