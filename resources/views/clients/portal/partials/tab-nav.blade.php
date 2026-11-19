<div class="tab-nav-wrapper">
    <nav class="tab-nav" role="tablist" aria-label="Navigasi Portal Klien">
        <button class="tab-btn active"
                role="tab"
                aria-selected="true"
                data-tab="survei"
                onclick="switchTab('survei', this)">
            <span class="tab-icon">📋</span>
            <span>Isi Survei</span>
            <span class="tab-badge">{{ $totalQuestions ?? 21 }}</span>
        </button>

        <button class="tab-btn"
                role="tab"
                aria-selected="false"
                data-tab="masukan"
                onclick="switchTab('masukan', this)">
            <span class="tab-icon">💬</span>
            <span>Beri Masukan</span>
        </button>

        <button class="tab-btn"
                role="tab"
                aria-selected="false"
                data-tab="keluhan"
                onclick="switchTab('keluhan', this)">
            <span class="tab-icon">🔔</span>
            <span>Buat Keluhan</span>
        </button>
    </nav>
</div>
