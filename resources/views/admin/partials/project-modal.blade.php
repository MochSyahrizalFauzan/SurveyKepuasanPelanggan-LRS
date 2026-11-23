{{-- =========================================================================
     MODAL TAMBAH & EDIT PROYEK
     ========================================================================= --}}
<div id="projectModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-project-title">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xl max-w-lg w-full flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-150">

        {{-- Header Modal --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-[#003B73] font-bold flex items-center justify-center text-sm border border-blue-100">
                    🏗️
                </span>
                <h3 id="modal-project-title" class="text-sm font-black text-slate-800">
                    Tambah Proyek Baru
                </h3>
            </div>
            <button type="button"
                    onclick="closeProjectModal()"
                    class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none cursor-pointer">
                ✕
            </button>
        </div>

        {{-- Form Body --}}
        <form id="form-project-modal" onsubmit="saveProjectModal(event)" class="p-6 space-y-4 text-xs">
            <input type="hidden" id="project-form-id" value="">

            {{-- Kode Kontrak --}}
            <div>
                <label for="project-form-contract" class="block font-bold text-slate-700 mb-1">
                    Kode Kontrak / Proyek <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       id="project-form-contract"
                       required
                       placeholder="Contoh: CTR-LRS-2026-001"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
            </div>

            {{-- Nama Proyek --}}
            <div>
                <label for="project-form-name" class="block font-bold text-slate-700 mb-1">
                    Nama Proyek Pekerjaan <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       id="project-form-name"
                       required
                       placeholder="Contoh: LRT Jakarta Fase 1B"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
            </div>

            {{-- Instansi Klien --}}
            <div>
                <label for="project-form-institution" class="block font-bold text-slate-700 mb-1">
                    Instansi / Perusahaan Klien <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       id="project-form-institution"
                       required
                       placeholder="Contoh: PT. LRT Jakarta"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
            </div>

            {{-- Target Responden & Status --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="project-form-target" class="block font-bold text-slate-700 mb-1">
                        Target Responden PIC <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           id="project-form-target"
                           required
                           min="1"
                           max="100"
                           value="10"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                </div>

                <div>
                    <label for="project-form-status" class="block font-bold text-slate-700 mb-1">
                        Status Proyek
                    </label>
                    <div class="relative">
                        <select id="project-form-status"
                                class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 pr-7 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                            <option value="Aktif">Aktif</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                        <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">▼</div>
                    </div>
                </div>
            </div>

            {{-- Footer Aksi --}}
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button"
                        onclick="closeProjectModal()"
                        class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl text-xs font-bold bg-[#003B73] hover:bg-[#002B54] text-white shadow-sm transition-colors cursor-pointer">
                    Simpan Proyek
                </button>
            </div>
        </form>

    </div>

</div>
