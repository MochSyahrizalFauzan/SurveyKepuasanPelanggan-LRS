{{-- =========================================================================
     MODAL TAMBAH & EDIT PERSONEL PENDAMPING LRS
     ========================================================================= --}}
<div id="personnelModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-personnel-title">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xl max-w-lg w-full flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-150">

        {{-- Header Modal --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-2.5">
                <h3 id="modal-personnel-title" class="text-sm font-black text-slate-800">
                    Tambah Personel Baru
                </h3>
            </div>
            <button type="button"
                    onclick="closePersonnelModal()"
                    class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none cursor-pointer">
                ✕
            </button>
        </div>

        {{-- Form Body --}}
        <form id="form-personnel-modal" onsubmit="savePersonnelModal(event)" class="p-6 space-y-4 text-xs">
            <input type="hidden" id="personnel-form-id" value="">

            {{-- Nama Lengkap --}}
            <div>
                <label for="personnel-form-name" class="block font-bold text-slate-700 mb-1">
                    Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       id="personnel-form-name"
                       required
                       placeholder="Contoh: Ir. Dwi Suryanto, S.T."
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
            </div>

            {{-- Divisi / Unit Kerja --}}
            <div>
                <label for="personnel-form-division" class="block font-bold text-slate-700 mb-1">
                    Divisi / Unit Kerja <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       id="personnel-form-division"
                       required
                       placeholder="Contoh: Divisi Rekayasa & Sistem Persinyalan"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
            </div>

            {{-- Nomor WhatsApp & Email --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="personnel-form-whatsapp" class="block font-bold text-slate-700 mb-1">
                        No. WhatsApp (Notifikasi) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="personnel-form-whatsapp"
                           required
                           placeholder="081234567890"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                </div>

                <div>
                    <label for="personnel-form-email" class="block font-bold text-slate-700 mb-1">
                        Email LRS <span class="text-rose-500">*</span>
                    </label>
                    <input type="email"
                           id="personnel-form-email"
                           required
                           placeholder="nama@lrs.co.id"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                </div>
            </div>

            {{-- Footer Aksi --}}
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button"
                        onclick="closePersonnelModal()"
                        class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl text-xs font-bold bg-[#003B73] hover:bg-[#002B54] text-white shadow-sm transition-colors cursor-pointer">
                    Simpan Personel
                </button>
            </div>
        </form>

    </div>

</div>
