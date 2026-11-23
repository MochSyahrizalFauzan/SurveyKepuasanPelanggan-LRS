{{-- =========================================================================
     SECTION 6: PENGATURAN & PREFERENSI SISTEM
     ========================================================================= --}}
<div id="section-settings" class="admin-section space-y-6 hidden">

    {{-- 1. HEADER --}}
    <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)]">
        <div class="flex items-center gap-2">
            <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                Pengaturan & Preferensi Sistem
            </h1>
        </div>
        <p class="text-xs md:text-sm text-slate-500 mt-1 max-w-3xl leading-relaxed">
            Atur target mutu kepuasan perusahaan, preferensi audio konfirmasi, simulasi pesan WhatsApp, serta kelola log pengiriman notifikasi antar-pihak.
        </p>
    </div>

    {{-- 2. GRID 2 KOLOM PENGATURAN PARAMETER & NOTIFIKASI --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

        {{-- KARTU 1: Target Skor Kepuasan Perusahaan --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)] space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-extrabold text-slate-800">
                        Target Kepuasan Pelanggan Perusahaan
                    </h3>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">
                    Skor minimal pencapaian indeks kepuasan (CSI) PT. Len Railways Systems.
                </p>
            </div>

            <form onsubmit="saveAdminTargetSetting(event)" class="space-y-4 text-xs">
                <div>
                    <label for="setting-target-score" class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[11px]">
                        Target Mutu (%) <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <div class="relative w-44">
                            <input type="number"
                                   id="setting-target-score"
                                   required
                                   min="50.0"
                                   max="100.0"
                                   step="0.1"
                                   value="80.0"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-black text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400">%</span>
                        </div>
                        <button type="submit"
                                class="px-4 py-2 rounded-xl text-xs font-bold bg-[#003B73] hover:bg-[#002B54] text-white shadow-sm transition-all cursor-pointer">
                            Simpan Target
                        </button>
                    </div>
                    <span class="block text-[11px] text-slate-400 mt-1">
                        Standar acuan perusahaan: 80.0%. Skor di bawah angka ini akan otomatis ditandai status "Di Bawah Target".
                    </span>
                </div>
            </form>
        </div>

        {{-- KARTU 2: Preferensi Audio & Simulasi Notifikasi --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)] space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-extrabold text-slate-800">
                        Preferensi Audio & Notifikasi Real-Time
                    </h3>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">
                    Pengaturan interaktivitas antarmuka admin saat data baru masuk.
                </p>
            </div>

            <div class="space-y-3.5 text-xs">
                {{-- Toggle 1: Suara Konfirmasi Singkat --}}
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                    <div class="pr-3">
                        <span class="font-extrabold text-slate-800 block text-xs">Nada Konfirmasi Masuk</span>
                        <span class="text-[11px] text-slate-400 block mt-0.5">
                            Memutar nada lembut saat ada survei atau keluhan baru dikirim dari Tab Klien.
                        </span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button"
                                onclick="playNotificationChime()"
                                title="Uji Coba Suara"
                                class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-xs text-slate-600 transition-colors cursor-pointer">
                            🔊
                        </button>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="setting-toggle-sound" onchange="saveNotificationToggles()" class="sr-only peer" checked>
                            <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#003B73]"></div>
                        </label>
                    </div>
                </div>

                {{-- Toggle 2: Simulasi Notifikasi WhatsApp ke Personel --}}
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                    <div class="pr-3">
                        <span class="font-extrabold text-slate-800 block text-xs">Simulasi WhatsApp Personel</span>
                        <span class="text-[11px] text-slate-400 block mt-0.5">
                            Kirim log simulasi notifikasi otomatis ke nomor WhatsApp Personel Pendamping yang bertugas.
                        </span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" id="setting-toggle-whatsapp" onchange="saveNotificationToggles()" class="sr-only peer" checked>
                        <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#003B73]"></div>
                    </label>
                </div>
            </div>
        </div>

    </div>

    {{-- 3. RIWAYAT PENGIRIMAN NOTIFIKASI (SIMULASI 3 PIHAK) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-[0_2px_12px_rgba(15,23,42,0.04)] overflow-hidden p-5 md:p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-base">📬</span>
                    <h3 class="text-sm font-extrabold text-slate-800">
                        Riwayat Pengiriman Notifikasi (Simulasi 3 Pihak)
                    </h3>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">
                    Log konfirmasi pesan otomatis ke Pelanggan, Personel Pendamping Lapangan, dan Administrator Layanan.
                </p>
            </div>
            <button type="button"
                    onclick="renderNotificationLogs()"
                    class="text-xs font-bold text-[#003B73] hover:underline self-start sm:self-auto cursor-pointer">
                ↻ Muat Ulang Log
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-extrabold text-slate-600 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-2.5 px-3">Waktu</th>
                        <th class="py-2.5 px-3">Penerima</th>
                        <th class="py-2.5 px-3">Saluran</th>
                        <th class="py-2.5 px-3">Ref. Survei</th>
                        <th class="py-2.5 px-3">Ringkasan Pesan</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="table-notif-logs-body" class="divide-y divide-slate-100 text-slate-700">
                    {{-- Diisi secara dinamis --}}
                </tbody>
            </table>
        </div>

        <div id="table-notif-logs-empty" class="hidden py-8 text-center text-slate-400 text-xs">
            Belum ada riwayat pengiriman notifikasi tercatat.
        </div>
    </div>

    {{-- 4. BLOK UTILITAS DEMO: RESET KE DATA CONTOH AWAL --}}
    <div class="bg-rose-50/60 rounded-2xl border border-rose-200/80 p-5 md:p-6 shadow-2xs space-y-3">
        <div class="flex items-start gap-3">
            <span class="w-9 h-9 rounded-xl bg-rose-100 text-rose-700 font-bold flex items-center justify-center text-lg shrink-0">
                🔄
            </span>
            <div class="flex-1">
                <h3 class="text-sm font-extrabold text-rose-900">
                    Utilitas Demo: Reset Data Contoh (Seed Awal)
                </h3>
                <p class="text-xs text-rose-700/80 mt-0.5 leading-relaxed">
                    Gunakan tombol ini saat ingin mengembalikan data prototipe ke kondisi awal saat sesi presentasi kepada Manajemen PT LRS. Seluruh data survei, keluhan, proyek, personel, dan riwayat akan di-refresh ke konfigurasi dummy standar.
                </p>
                <div class="mt-3">
                    <button type="button"
                            onclick="resetToSeedDemoData()"
                            class="px-4 py-2 rounded-xl text-xs font-black bg-rose-600 hover:bg-rose-700 text-white shadow-sm shadow-rose-600/30 transition-all flex items-center gap-1.5 cursor-pointer">
                        <span>⚠️</span>
                        <span>Reset ke Data Contoh Awal</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
