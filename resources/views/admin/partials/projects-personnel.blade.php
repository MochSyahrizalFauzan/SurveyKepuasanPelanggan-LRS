{{-- =========================================================================
     SECTION 5: KELOLA PROYEK & PERSONEL PENDAMPING LRS
     ========================================================================= --}}
<div id="section-projects-personnel" class="admin-section space-y-6 hidden">

    {{-- 1. HEADER DENGAN SUB-TAB SWITCHER --}}
    <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)] flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                    Proyek & Personel Pendamping
                </h1>
            </div>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Kelola master data proyek aktif LRS serta direktori personel pendamping lapangan yang bertugas.
            </p>
        </div>

        {{-- Sub-Tab Switcher --}}
        <div class="inline-flex p-1 rounded-2xl bg-slate-100 border border-slate-200 self-start md:self-auto" role="tablist">
            <button type="button"
                    id="subtab-btn-projects"
                    onclick="switchProyekPersonelSubtab('projects')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-[#003B73] text-white shadow-sm flex items-center gap-1.5 cursor-pointer">
                <span>Daftar Proyek (<span id="count-subtab-projects">0</span>)</span>
            </button>
            <button type="button"
                    id="subtab-btn-personnel"
                    onclick="switchProyekPersonelSubtab('personnel')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 flex items-center gap-1.5 cursor-pointer">
                <span>Personel Pendamping (<span id="count-subtab-personnel">0</span>)</span>
            </button>
        </div>
    </div>

    {{-- 2. KONTEN SUB-TAB 1: DAFTAR PROYEK (lrs_projects_data) --}}
    <div id="subtab-content-projects" class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-800 tracking-tight">
                    Master Proyek Pekerjaan
                </h3>
                <p class="text-xs text-slate-400">
                    Proyek yang terdaftar di sini otomatis muncul di filter Ringkasan dan Generator QR Code.
                </p>
            </div>
            <button type="button"
                    onclick="openProjectModal('add')"
                    class="px-4 py-2 rounded-xl bg-[#003B73] hover:bg-[#002B54] text-white font-bold text-xs shadow-sm shadow-[#003B73]/20 hover:shadow-md transition-all flex items-center gap-1.5 self-start sm:self-auto cursor-pointer">
                <span>➕</span>
                <span>Tambah Proyek Baru</span>
            </button>
        </div>

        {{-- Tabel Master Proyek --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-[0_2px_12px_rgba(15,23,42,0.04)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                            <th class="py-3 px-4">Kode Kontrak</th>
                            <th class="py-3 px-4">Nama Proyek</th>
                            <th class="py-3 px-4">Instansi Klien</th>
                            <th class="py-3 px-4 text-center">Target Responden</th>
                            <th class="py-3 px-4 text-center">Partisipasi Masuk</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-projects-body" class="divide-y divide-slate-100 text-slate-700">
                        {{-- Diisi secara dinamis oleh JS --}}
                    </tbody>
                </table>
            </div>

            <div id="table-projects-empty" class="hidden py-10 text-center text-slate-400 text-xs">
                Belum ada proyek yang ditambahkan. Silakan klik "+ Tambah Proyek Baru".
            </div>
        </div>
    </div>

    {{-- 3. KONTEN SUB-TAB 2: PERSONEL PENDAMPING (lrs_personnel_data) --}}
    <div id="subtab-content-personnel" class="space-y-4 hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-800 tracking-tight">
                    Direktori Personel Pendamping LRS
                </h3>
                <p class="text-xs text-slate-400">
                    Personel yang bertugas mendampingi klien dan menerima notifikasi hasil survei via simulasi WhatsApp.
                </p>
            </div>
            <button type="button"
                    onclick="openPersonnelModal('add')"
                    class="px-4 py-2 rounded-xl bg-[#003B73] hover:bg-[#002B54] text-white font-bold text-xs shadow-sm shadow-[#003B73]/20 hover:shadow-md transition-all flex items-center gap-1.5 self-start sm:self-auto cursor-pointer">
                <span>➕</span>
                <span>Tambah Personel Baru</span>
            </button>
        </div>

        {{-- Tabel Personel --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-[0_2px_12px_rgba(15,23,42,0.04)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Lengkap</th>
                            <th class="py-3 px-4">Divisi / Unit Kerja</th>
                            <th class="py-3 px-4">No. WhatsApp</th>
                            <th class="py-3 px-4">Email LRS</th>
                            <th class="py-3 px-4 text-center">Survei Terkait</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-personnel-body" class="divide-y divide-slate-100 text-slate-700">
                        {{-- Diisi secara dinamis oleh JS --}}
                    </tbody>
                </table>
            </div>

            <div id="table-personnel-empty" class="hidden py-10 text-center text-slate-400 text-xs">
                Belum ada personel yang ditambahkan. Silakan klik "+ Tambah Personel Baru".
            </div>
        </div>
    </div>

</div>
