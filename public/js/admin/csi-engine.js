/**
 * PT Len Railway Systems — Modular CSI Calculation Engine
 * Menghitung Distribusi 1-5, Indeks Kepentingan (I), Indeks Kepuasan (S),
 * GAP (S - I), Impr x Satisf, CSI Per Fasa (%), dan CSI Total (%) dari localStorage ('lrs_survey_data').
 */
(function () {
    'use strict';

    const DEFAULT_CATEGORIES = [
        {
            code: 'A',
            name: 'Layanan & Dukungan',
            indicators: [
                { code: 'A1', text: 'Keramahan personal PT Len Railway Systems' },
                { code: 'A2', text: 'Kemampuan komunikasi personal PT Len Railway Systems' },
                { code: 'A3', text: 'Pemahaman kebutuhan pelanggan' },
                { code: 'A4', text: 'Kualitas dan relevansi solusi/saran yang ditawarkan' },
                { code: 'A5', text: 'Kejelasan penjelasan teknis' }
            ]
        },
        {
            code: 'B',
            name: 'Pelaksanaan Proyek',
            indicators: [
                { code: 'B1', text: 'Ketepatan waktu pelaksanaan proyek' },
                { code: 'B2', text: 'Kerjasama dan koordinasi tim proyek' },
                { code: 'B3', text: 'Kompetensi teknis tim proyek' },
                { code: 'B4', text: 'Kerapihan pekerjaan tim proyek' },
                { code: 'B5', text: 'Tingkat kesalahan/pengulangan pekerjaan' },
                { code: 'B6', text: 'Kecepatan dalam menangani kendala di lapangan' },
                { code: 'B7', text: 'Komunikasi dan pelaporan progres' },
                { code: 'B8', text: 'Budaya K3L PT Len Railway Systems' },
                { code: 'B9', text: 'Kelengkapan dan kepatuhan penggunaan APD' },
                { code: 'B10', text: 'Sosialisasi aturan K3L di lokasi kerja' },
                { code: 'B11', text: 'Kompetensi K3L personil PT Len Railway Systems' }
            ]
        },
        {
            code: 'C',
            name: 'Masa Pemeliharaan',
            indicators: [
                { code: 'C1', text: 'Fungsi produk sesuai harapan' },
                { code: 'C2', text: 'Kemudahan dalam pengoperasian produk' },
                { code: 'C3', text: 'Kemudahan proses pemeliharaan' },
                { code: 'C4', text: 'Respon dan kecepatan layanan after sales' },
                { code: 'C5', text: 'Ketahanan/durabilitas produk' }
            ]
        }
    ];

    const state = {
        activeSubTab: 'recap',
        projectFilter: 'all',
        categoryFilter: 'all',
        searchQuery: '',
        page: 1,
        perPage: 10,
        lastComputed: null
    };

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function isGeneralSurveyItem(item) {
        if (!item) return false;
        return item.survey_source === 'general' || item.project_code === 'GENERAL';
    }

    function getCategoriesConfig() {
        const root = document.getElementById('csi-matrix-root');
        if (root && root.dataset.categories) {
            try {
                const parsed = JSON.parse(root.dataset.categories);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    return parsed;
                }
            } catch (e) {}
        }
        return DEFAULT_CATEGORIES;
    }

    function formatThousands(num) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(Number(num || 0));
    }

    function formatDistPct(pct) {
        const val = Number(pct || 0);
        if (val === 0) return '0%';
        return Number.isInteger(Number(val.toFixed(1)))
            ? `${Math.round(val)}%`
            : `${val.toFixed(1)}%`;
    }

    function getQualityTier(csiPct, hasData) {
        if (!hasData) {
            return {
                label: 'Belum Ada Data',
                badgeClass: 'bg-white/15 text-white border-white/25'
            };
        }
        if (csiPct >= 81.25) {
            return {
                label: 'Sangat Puas',
                badgeClass: 'bg-emerald-400/25 text-emerald-100 border-emerald-300/40'
            };
        }
        if (csiPct >= 62.5) {
            return {
                label: 'Puas',
                badgeClass: 'bg-sky-400/25 text-white border-sky-200/40'
            };
        }
        if (csiPct >= 43.75) {
            return {
                label: 'Cukup',
                badgeClass: 'bg-amber-400/25 text-amber-100 border-amber-300/40'
            };
        }
        return {
            label: 'Kurang',
            badgeClass: 'bg-rose-400/25 text-rose-100 border-rose-300/40'
        };
    }

    /**
     * Sinkronisasi opsi dropdown Proyek CSI dari lrs_projects_data + lrs_survey_data
     */
    function syncProjectFilterOptions() {
        const select = document.getElementById('csi-filter-project');
        if (!select) return;

        const currentVal = select.value || state.projectFilter || 'all';
        let projects = [];
        let surveys = [];
        try {
            projects = JSON.parse(localStorage.getItem('lrs_projects_data') || '[]');
            surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        } catch (e) {}

        const seenNames = new Set();
        const projectNames = [];

        projects.forEach(p => {
            const name = String(p.name || '').trim();
            if (name && !seenNames.has(name.toLowerCase())) {
                seenNames.add(name.toLowerCase());
                projectNames.push(name);
            }
        });

        surveys.forEach(s => {
            if (isGeneralSurveyItem(s)) return;
            const name = String(s.project_name || '').trim();
            if (name && !seenNames.has(name.toLowerCase())) {
                seenNames.add(name.toLowerCase());
                projectNames.push(name);
            }
        });

        let html = '<option value="all">Semua Proyek</option>';
        html += '<option value="GENERAL">Survei Umum (Non-Kontrak)</option>';
        projectNames.forEach(name => {
            html += `<option value="${escapeHtml(name)}">${escapeHtml(name)}</option>`;
        });

        select.innerHTML = html;
        if (Array.from(select.options).some(opt => opt.value === currentVal)) {
            select.value = currentVal;
        } else {
            select.value = 'all';
            state.projectFilter = 'all';
        }
    }

    /**
     * Hitung seluruh Metrik CSI dari data localStorage sesuai Filter Proyek & Fasa aktif
     * (Independen terhadap pagination & search tampilan)
     */
    function computeCsiDataset() {
        const categories = getCategoriesConfig();
        let allSurveys = [];
        try {
            allSurveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        } catch (e) {}

        const filteredSurveys = allSurveys.filter(s => {
            const matchCat = state.categoryFilter === 'all' || String(s.category_id || '').toUpperCase() === state.categoryFilter.toUpperCase();
            if (!matchCat) return false;

            if (state.projectFilter === 'all') return true;
            if (state.projectFilter === 'GENERAL') return isGeneralSurveyItem(s);
            return String(s.project_name || '').trim().toLowerCase() === state.projectFilter.trim().toLowerCase();
        });

        // Agregasi skor 1..5 per question_id
        const answerBuckets = {};
        filteredSurveys.forEach(s => {
            const answers = Array.isArray(s.answers) ? s.answers : [];
            answers.forEach(a => {
                const qId = String(a.question_id || '').trim().toUpperCase();
                if (!qId) return;

                if (!answerBuckets[qId]) {
                    answerBuckets[qId] = {
                        imp: { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0, count: 0 },
                        sat: { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0, count: 0 }
                    };
                }

                const impVal = parseInt(a.kepentingan ?? a.importance ?? a.imp, 10);
                const satVal = parseInt(a.kepuasan ?? a.satisfaction ?? a.sat, 10);

                if (impVal >= 1 && impVal <= 5) {
                    answerBuckets[qId].imp[impVal]++;
                    answerBuckets[qId].imp.count++;
                }
                if (satVal >= 1 && satVal <= 5) {
                    answerBuckets[qId].sat[satVal]++;
                    answerBuckets[qId].sat.count++;
                }
            });
        });

        const selectedCategories = state.categoryFilter === 'all'
            ? categories
            : categories.filter(c => String(c.code || '').toUpperCase() === state.categoryFilter.toUpperCase());

        const rows = [];
        const phaseStats = {};
        let globalNo = 0;

        selectedCategories.forEach(cat => {
            const catCode = String(cat.code || '').toUpperCase();
            const catName = cat.name || `Fasa ${catCode}`;
            let phaseSumI = 0;
            let phaseSumIxS = 0;
            let phaseRatedCount = 0;

            (cat.indicators || []).forEach(ind => {
                globalNo++;
                const qCode = String(ind.code || '').toUpperCase();
                const bucket = answerBuckets[qCode] || {
                    imp: { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0, count: 0 },
                    sat: { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0, count: 0 }
                };

                const impDist = {};
                const satDist = {};
                let weightedImp = 0;
                let weightedSat = 0;

                for (let score = 1; score <= 5; score++) {
                    const p = bucket.imp.count > 0 ? (bucket.imp[score] / bucket.imp.count) * 100 : 0;
                    const s = bucket.sat.count > 0 ? (bucket.sat[score] / bucket.sat.count) * 100 : 0;
                    impDist[score] = p;
                    satDist[score] = s;
                    weightedImp += score * p;
                    weightedSat += score * s;
                }

                const indexI = bucket.imp.count > 0 ? (weightedImp / 5) : 0;
                const indexS = bucket.sat.count > 0 ? (weightedSat / 5) : 0;
                const hasRatings = bucket.imp.count > 0 || bucket.sat.count > 0;
                const gap = hasRatings ? (indexS - indexI) : 0;
                const impSatisf = indexI * indexS;

                if (hasRatings) {
                    phaseSumI += indexI;
                    phaseSumIxS += impSatisf;
                    phaseRatedCount++;
                }

                rows.push({
                    no: globalNo,
                    code: qCode,
                    text: ind.text || qCode,
                    categoryCode: catCode,
                    categoryName: catName,
                    impCount: bucket.imp.count,
                    satCount: bucket.sat.count,
                    hasRatings,
                    impDist,
                    satDist,
                    indexI,
                    indexS,
                    gap,
                    impSatisf
                });
            });

            const csiPhase = phaseSumI > 0 ? (phaseSumIxS / phaseSumI) : 0;
            phaseStats[catCode] = {
                code: catCode,
                name: catName,
                sumI: phaseSumI,
                sumIxS: phaseSumIxS,
                ratedCount: phaseRatedCount,
                csiPhase
            };
        });

        let totalSumI = 0;
        let totalSumS = 0;
        let totalSumIxS = 0;
        let ratedIndicatorCount = 0;
        let worstGapRow = null;

        Object.values(phaseStats).forEach(ps => {
            totalSumI += ps.sumI;
            totalSumIxS += ps.sumIxS;
        });

        rows.forEach(r => {
            if (r.hasRatings) {
                totalSumS += r.indexS;
                ratedIndicatorCount++;
                if (!worstGapRow || r.gap < worstGapRow.gap) {
                    worstGapRow = r;
                }
            }
        });

        const csiTotal = totalSumI > 0 ? (totalSumIxS / totalSumI) : 0;
        const avgI = ratedIndicatorCount > 0 ? (totalSumI / ratedIndicatorCount) : 0;
        const avgS = ratedIndicatorCount > 0 ? (totalSumS / ratedIndicatorCount) : 0;

        return {
            respondentCount: filteredSurveys.length,
            hasData: filteredSurveys.length > 0 && ratedIndicatorCount > 0,
            rows,
            phaseStats,
            totalSumI,
            totalSumIxS,
            avgI,
            avgS,
            csiTotal,
            worstGapRow,
            ratedIndicatorCount
        };
    }

    function renderKpiCards(dataset) {
        const elTotal = document.getElementById('csi-kpi-total-val');
        const elBadge = document.getElementById('csi-kpi-quality-badge');
        const elAvgS = document.getElementById('csi-kpi-avg-s');
        const elAvgI = document.getElementById('csi-kpi-avg-i');
        const elAvgDiff = document.getElementById('csi-kpi-avg-diff');
        const elWorstVal = document.getElementById('csi-kpi-worst-gap-val');
        const elWorstLbl = document.getElementById('csi-kpi-worst-gap-label');
        const elRespCount = document.getElementById('csi-kpi-respondents-count');
        const elRespSub = document.getElementById('csi-kpi-respondents-sub');

        const tier = getQualityTier(dataset.csiTotal, dataset.hasData);

        if (elTotal) elTotal.textContent = `${dataset.csiTotal.toFixed(2)}%`;
        if (elBadge) {
            elBadge.textContent = tier.label;
            elBadge.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold border ${tier.badgeClass}`;
        }

        if (elAvgS) elAvgS.textContent = dataset.avgS.toFixed(2);
        if (elAvgI) elAvgI.textContent = dataset.avgI.toFixed(2);
        if (elAvgDiff) {
            if (dataset.hasData) {
                const diff = dataset.avgS - dataset.avgI;
                elAvgDiff.textContent = `Selisih rata-rata (S - I): ${diff >= 0 ? '+' : ''}${diff.toFixed(2)}`;
            } else {
                elAvgDiff.textContent = 'Skala indeks 0–100 sesuai bobot persepsi klien';
            }
        }

        if (elWorstVal && elWorstLbl) {
            if (dataset.worstGapRow) {
                const gapNum = dataset.worstGapRow.gap;
                elWorstVal.textContent = `${gapNum >= 0 ? '+' : ''}${gapNum.toFixed(2)}`;
                elWorstVal.className = `text-xl sm:text-2xl font-black block ${gapNum < 0 ? 'text-rose-600' : 'text-emerald-600'}`;
                elWorstLbl.textContent = `${dataset.worstGapRow.code} · ${dataset.worstGapRow.text}`;
            } else {
                elWorstVal.textContent = '0.00';
                elWorstVal.className = 'text-xl sm:text-2xl font-black text-slate-400 block';
                elWorstLbl.textContent = 'Belum ada indikator terukur';
            }
        }

        if (elRespCount) elRespCount.textContent = String(dataset.respondentCount);
        if (elRespSub) {
            elRespSub.textContent = dataset.respondentCount > 0
                ? `${dataset.ratedIndicatorCount} dari ${dataset.rows.length} indikator memiliki penilaian`
                : 'Responden sesuai filter proyek & fasa aktif';
        }
    }

    function renderMatrixAndPagination(dataset) {
        const emptyState = document.getElementById('csi-empty-state');
        const emptyTitle = document.getElementById('csi-empty-title');
        const emptyDesc = document.getElementById('csi-empty-desc');
        const tableWrapper = document.getElementById('csi-matrix-table-wrapper');
        const tbody = document.getElementById('csi-matrix-body');
        const tfoot = document.getElementById('csi-matrix-foot');
        const pagBar = document.getElementById('csi-pagination-bar');
        const pagInfo = document.getElementById('csi-pagination-info');
        const pagControls = document.getElementById('csi-pagination-controls');

        if (!tbody || !tableWrapper || !emptyState) return;

        // Jika belum ada survei sama sekali untuk filter proyek & fasa ini
        if (dataset.respondentCount === 0) {
            tbody.innerHTML = '';
            if (tfoot) tfoot.innerHTML = '';
            tableWrapper.classList.add('hidden');
            if (pagBar) pagBar.classList.add('hidden');
            emptyState.classList.remove('hidden');
            if (emptyTitle) emptyTitle.textContent = 'Belum ada data survei pada filter proyek/fasa ini';
            if (emptyDesc) emptyDesc.textContent = 'Matriks perhitungan CSI akan otomatis ditampilkan setelah terdapat responden survei yang sesuai dengan filter aktif.';
            return;
        }

        const q = state.searchQuery.toLowerCase().trim();
        const searchMatchedRows = q
            ? dataset.rows.filter(r =>
                r.code.toLowerCase().includes(q)
                || r.text.toLowerCase().includes(q)
                || r.categoryName.toLowerCase().includes(q))
            : dataset.rows;

        if (searchMatchedRows.length === 0) {
            tbody.innerHTML = '';
            if (tfoot) tfoot.innerHTML = '';
            tableWrapper.classList.add('hidden');
            if (pagBar) pagBar.classList.add('hidden');
            emptyState.classList.remove('hidden');
            if (emptyTitle) emptyTitle.textContent = 'Atribut indikator tidak ditemukan';
            if (emptyDesc) emptyDesc.textContent = `Tidak ada indikator yang cocok dengan kata kunci "${state.searchQuery}".`;
            return;
        }

        emptyState.classList.add('hidden');
        tableWrapper.classList.remove('hidden');
        if (pagBar) pagBar.classList.remove('hidden');

        // Hitung pagination
        const totalRows = searchMatchedRows.length;
        const limit = state.perPage === 'all' ? totalRows : Math.max(1, parseInt(state.perPage, 10) || 10);
        const totalPages = Math.max(1, Math.ceil(totalRows / limit));
        if (state.page > totalPages) state.page = totalPages;
        if (state.page < 1) state.page = 1;

        const startIdx = (state.page - 1) * limit;
        const endIdx = Math.min(totalRows, startIdx + limit);
        const visibleRows = searchMatchedRows.slice(startIdx, endIdx);

        // Hitung span contiguous fasa pada halaman tampil saat ini (nilai tetap dari dataset penuh)
        const phaseSpanAtIndex = {};
        let cursor = 0;
        while (cursor < visibleRows.length) {
            const catCode = visibleRows[cursor].categoryCode;
            let spanLen = 1;
            while (cursor + spanLen < visibleRows.length && visibleRows[cursor + spanLen].categoryCode === catCode) {
                spanLen++;
            }
            phaseSpanAtIndex[cursor] = spanLen;
            cursor += spanLen;
        }

        // Render tbody
        let html = '';
        visibleRows.forEach((row, idx) => {
            const prevRow = idx > 0 ? visibleRows[idx - 1] : null;
            const isPhaseBoundary = prevRow && prevRow.categoryCode !== row.categoryCode;
            const rowBorderCls = isPhaseBoundary ? 'border-t-2 border-slate-300/90' : '';
            const mutedRowCls = row.hasRatings ? '' : 'opacity-65';

            const distImpCells = [1, 2, 3, 4, 5].map(score => {
                const pct = row.impDist[score] || 0;
                const textCls = pct > 0 ? 'text-slate-700 font-semibold' : 'text-slate-300';
                return `<td class="py-3 px-2 text-center tabular-nums border-r border-slate-100 ${textCls}">${escapeHtml(formatDistPct(pct))}</td>`;
            }).join('');

            const distSatCells = [1, 2, 3, 4, 5].map(score => {
                const pct = row.satDist[score] || 0;
                const textCls = pct > 0 ? 'text-slate-700 font-semibold' : 'text-slate-300';
                return `<td class="py-3 px-2 text-center tabular-nums border-r border-slate-100 ${textCls}">${escapeHtml(formatDistPct(pct))}</td>`;
            }).join('');

            const gapValStr = row.hasRatings
                ? `${row.gap >= 0 ? '+' : ''}${row.gap.toFixed(2)}`
                : '0.00';
            const gapCls = !row.hasRatings
                ? 'text-slate-400'
                : (row.gap < 0 ? 'text-rose-600 font-extrabold' : 'text-emerald-600 font-extrabold');

            // CSI Per Fasa cell (hanya pada awal tiap blok fasa pada halaman ini; nilai dari full dataset)
            let csiPhaseCell = '';
            if (phaseSpanAtIndex[idx]) {
                const ps = dataset.phaseStats[row.categoryCode];
                const phaseCsiVal = ps ? ps.csiPhase.toFixed(2) + '%' : '0.00%';
                const phaseHasData = ps && ps.ratedCount > 0;
                csiPhaseCell = `
                    <td rowspan="${phaseSpanAtIndex[idx]}"
                        class="py-3 px-3 text-center align-middle border-l border-r border-slate-200 bg-slate-50/70 whitespace-nowrap">
                        <span class="inline-flex flex-col items-center gap-0.5 px-2.5 py-1.5 rounded-xl border ${phaseHasData ? 'bg-blue-50/80 border-blue-200 text-[#003B73]' : 'bg-slate-100 border-slate-200 text-slate-400'}">
                            <span class="text-[10px] font-bold uppercase tracking-wider">Fasa ${escapeHtml(row.categoryCode)}</span>
                            <span class="text-xs font-black tabular-nums">${escapeHtml(phaseCsiVal)}</span>
                        </span>
                    </td>
                `;
            }

            // CSI Total cell (rowspan seluruh baris di halaman ini; nilai selalu dari keseluruhan dataset terfilter)
            let csiTotalCell = '';
            if (idx === 0) {
                const tier = getQualityTier(dataset.csiTotal, dataset.hasData);
                csiTotalCell = `
                    <td rowspan="${visibleRows.length}"
                        class="py-3 px-3.5 text-center align-middle bg-blue-50/40 whitespace-nowrap">
                        <div class="inline-flex flex-col items-center gap-1 px-3 py-2 rounded-xl bg-[#003B73] text-white shadow-2xs">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-100">CSI Total</span>
                            <span class="text-sm font-black tabular-nums">${dataset.csiTotal.toFixed(2)}%</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/15">${escapeHtml(tier.label)}</span>
                        </div>
                    </td>
                `;
            }

            html += `
                <tr class="group hover:bg-blue-50/40 transition-colors ${rowBorderCls} ${mutedRowCls}">
                    <td class="sticky left-0 z-10 bg-white group-hover:bg-blue-50 py-3 px-3 text-center font-bold text-slate-500 border-r border-slate-100 tabular-nums transition-colors">
                        ${row.no}
                    </td>
                    <td class="sticky left-12 z-10 bg-white group-hover:bg-blue-50 py-3 px-3.5 border-r border-slate-200 shadow-[2px_0_6px_-2px_rgba(15,23,42,0.08)] transition-colors">
                        <div class="flex items-start gap-2 min-w-0">
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-slate-100 text-[#003B73] border border-slate-200 shrink-0 mt-0.5">
                                ${escapeHtml(row.code)}
                            </span>
                            <div class="min-w-0">
                                <div class="font-bold text-slate-800 leading-snug break-words">${escapeHtml(row.text)}</div>
                                <div class="text-[10px] text-slate-400 font-semibold mt-0.5">Fasa ${escapeHtml(row.categoryCode)} · ${escapeHtml(row.categoryName)}</div>
                            </div>
                        </div>
                    </td>
                    ${distImpCells}
                    <td class="py-3 px-3 text-right font-bold text-[#003B73] tabular-nums border-r border-slate-200 bg-slate-50/40">
                        ${row.indexI.toFixed(2)}
                    </td>
                    ${distSatCells}
                    <td class="py-3 px-3 text-right font-bold text-slate-800 tabular-nums border-r border-slate-100 bg-slate-50/40">
                        ${row.indexS.toFixed(2)}
                    </td>
                    <td class="py-3 px-3 text-right tabular-nums border-r border-slate-100 ${gapCls}">
                        ${escapeHtml(gapValStr)}
                    </td>
                    <td class="py-3 px-3 text-right font-bold text-slate-800 tabular-nums border-r border-slate-200">
                        ${escapeHtml(formatThousands(row.impSatisf))}
                    </td>
                    ${csiPhaseCell}
                    ${csiTotalCell}
                </tr>
            `;
        });
        tbody.innerHTML = html;

        // Render tfoot (total agregat seluruh dataset sesuai filter Proyek & Fasa)
        if (tfoot) {
            const globalGap = dataset.avgS - dataset.avgI;
            const globalGapCls = globalGap < 0 ? 'text-rose-600' : 'text-emerald-600';
            tfoot.innerHTML = `
                <tr>
                    <td class="sticky left-0 z-10 bg-slate-100 py-3 px-3 border-r border-slate-200 text-center">&Sigma;</td>
                    <td class="sticky left-12 z-10 bg-slate-100 py-3 px-3.5 border-r border-slate-200 shadow-[2px_0_6px_-2px_rgba(15,23,42,0.08)]">
                        <span class="text-slate-800">Total / Rata-rata Filter Aktif</span>
                        <span class="block text-[10px] font-semibold text-slate-500">Dihitung dari seluruh ${dataset.rows.length} indikator &amp; ${dataset.respondentCount} responden</span>
                    </td>
                    <td colspan="5" class="py-3 px-2 text-center text-[11px] font-semibold text-slate-400 border-r border-slate-200">Distribusi Kepentingan (%)</td>
                    <td class="py-3 px-3 text-right tabular-nums text-[#003B73] border-r border-slate-200">${escapeHtml(formatThousands(dataset.totalSumI))}</td>
                    <td colspan="5" class="py-3 px-2 text-center text-[11px] font-semibold text-slate-400 border-r border-slate-200">Distribusi Kepuasan (%)</td>
                    <td class="py-3 px-3 text-right tabular-nums text-slate-700 border-r border-slate-100">${dataset.avgS.toFixed(2)} (avg)</td>
                    <td class="py-3 px-3 text-right tabular-nums border-r border-slate-100 ${globalGapCls}">${globalGap >= 0 ? '+' : ''}${globalGap.toFixed(2)}</td>
                    <td class="py-3 px-3 text-right tabular-nums text-slate-800 border-r border-slate-200">${escapeHtml(formatThousands(dataset.totalSumIxS))}</td>
                    <td colspan="2" class="py-3 px-3 text-center text-[#003B73] bg-blue-50/70">CSI Total: ${dataset.csiTotal.toFixed(2)}%</td>
                </tr>
            `;
        }

        // Render info & tombol pagination
        if (pagInfo) {
            pagInfo.textContent = `Menampilkan ${startIdx + 1}–${endIdx} dari ${totalRows} indikator`;
        }

        if (pagControls) {
            const btnBase = 'px-2.5 py-1.5 rounded-lg text-xs font-bold border transition-all cursor-pointer';
            const btnDisabled = 'opacity-40 cursor-not-allowed pointer-events-none';
            let pHtml = `
                <button type="button"
                        onclick="goToCsiPage(${state.page - 1})"
                        ${state.page <= 1 ? 'disabled' : ''}
                        class="${btnBase} bg-white border-slate-200 text-slate-600 hover:bg-slate-100 ${state.page <= 1 ? btnDisabled : ''}">
                    Prev
                </button>
            `;

            for (let p = 1; p <= totalPages; p++) {
                const isActive = p === state.page;
                pHtml += `
                    <button type="button"
                            onclick="goToCsiPage(${p})"
                            class="${btnBase} ${isActive ? 'bg-[#003B73] text-white border-[#003B73] shadow-2xs' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-100'}">
                        ${p}
                    </button>
                `;
            }

            pHtml += `
                <button type="button"
                        onclick="goToCsiPage(${state.page + 1})"
                        ${state.page >= totalPages ? 'disabled' : ''}
                        class="${btnBase} bg-white border-slate-200 text-slate-600 hover:bg-slate-100 ${state.page >= totalPages ? btnDisabled : ''}">
                    Next
                </button>
            `;
            pagControls.innerHTML = pHtml;
        }
    }

    function refresh() {
        syncProjectFilterOptions();
        const dataset = computeCsiDataset();
        state.lastComputed = dataset;
        renderKpiCards(dataset);
        renderMatrixAndPagination(dataset);
    }

    window.switchSurveysSubTab = function (tabKey) {
        state.activeSubTab = tabKey === 'csi' ? 'csi' : 'recap';

        const btnRecap = document.getElementById('tab-btn-surveys-recap');
        const btnCsi = document.getElementById('tab-btn-surveys-csi');
        const panelRecap = document.getElementById('panel-surveys-recap');
        const panelCsi = document.getElementById('panel-surveys-csi');

        const activeCls = 'px-4 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 flex items-center gap-2 bg-[#003B73] text-white shadow-xs cursor-pointer';
        const inactiveCls = 'px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-white/80 cursor-pointer';

        if (state.activeSubTab === 'csi') {
            if (btnCsi) {
                btnCsi.className = activeCls;
                btnCsi.setAttribute('aria-selected', 'true');
            }
            if (btnRecap) {
                btnRecap.className = inactiveCls;
                btnRecap.setAttribute('aria-selected', 'false');
            }
            if (panelRecap) {
                panelRecap.classList.add('hidden', 'opacity-0');
            }
            if (panelCsi) {
                panelCsi.classList.remove('hidden');
                requestAnimationFrame(() => panelCsi.classList.remove('opacity-0'));
            }
            refresh();
        } else {
            if (btnRecap) {
                btnRecap.className = activeCls;
                btnRecap.setAttribute('aria-selected', 'true');
            }
            if (btnCsi) {
                btnCsi.className = inactiveCls;
                btnCsi.setAttribute('aria-selected', 'false');
            }
            if (panelCsi) {
                panelCsi.classList.add('hidden', 'opacity-0');
            }
            if (panelRecap) {
                panelRecap.classList.remove('hidden');
                requestAnimationFrame(() => panelRecap.classList.remove('opacity-0'));
            }
        }
    };

    window.onCsiFilterChange = function () {
        const elProj = document.getElementById('csi-filter-project');
        const elCat = document.getElementById('csi-filter-category');
        const elSearch = document.getElementById('csi-filter-search');

        state.projectFilter = elProj ? elProj.value : 'all';
        state.categoryFilter = elCat ? elCat.value : 'all';
        state.searchQuery = elSearch ? elSearch.value : '';
        state.page = 1;
        refresh();
    };

    window.onCsiPerPageChange = function () {
        const elPer = document.getElementById('csi-per-page');
        state.perPage = elPer ? elPer.value : '10';
        state.page = 1;
        refresh();
    };

    window.goToCsiPage = function (targetPage) {
        state.page = Math.max(1, parseInt(targetPage, 10) || 1);
        if (state.lastComputed) {
            renderMatrixAndPagination(state.lastComputed);
        } else {
            refresh();
        }
    };

    window.exportCsiMatrixCsv = function () {
        const dataset = state.lastComputed || computeCsiDataset();
        if (!dataset || dataset.respondentCount === 0) {
            if (typeof window.showAdminToast === 'function') {
                window.showAdminToast('Belum ada data survei pada filter aktif untuk diekspor.');
            }
            return;
        }

        const q = state.searchQuery.toLowerCase().trim();
        const exportRows = q
            ? dataset.rows.filter(r =>
                r.code.toLowerCase().includes(q)
                || r.text.toLowerCase().includes(q)
                || r.categoryName.toLowerCase().includes(q))
            : dataset.rows;

        let csv = '\uFEFF';
        csv += 'PT Len Railway Systems - Matriks Perhitungan Customer Satisfaction Index (CSI)\n';
        csv += `Filter Proyek,"${state.projectFilter}",Responden Terhitung,${dataset.respondentCount},CSI Total,"${dataset.csiTotal.toFixed(2)}%"\n`;
        csv += 'No,Kode,Fasa,Atribut / Indikator,Kepentingan 1 (%),Kepentingan 2 (%),Kepentingan 3 (%),Kepentingan 4 (%),Kepentingan 5 (%),Indeks Kepentingan (I),Kepuasan 1 (%),Kepuasan 2 (%),Kepuasan 3 (%),Kepuasan 4 (%),Kepuasan 5 (%),Indeks Kepuasan (S),GAP (S - I),Impr x Satisf (I x S),CSI Per Fasa (%),CSI Total (%)\n';

        exportRows.forEach(r => {
            const ps = dataset.phaseStats[r.categoryCode];
            const csiPhaseStr = ps ? ps.csiPhase.toFixed(2) + '%' : '0.00%';
            const cleanText = String(r.text || '').replace(/"/g, '""');
            const cleanCat = String(r.categoryName || '').replace(/"/g, '""');

            csv += [
                r.no,
                `"${r.code}"`,
                `"Fasa ${r.categoryCode} - ${cleanCat}"`,
                `"${cleanText}"`,
                r.impDist[1].toFixed(1),
                r.impDist[2].toFixed(1),
                r.impDist[3].toFixed(1),
                r.impDist[4].toFixed(1),
                r.impDist[5].toFixed(1),
                r.indexI.toFixed(2),
                r.satDist[1].toFixed(1),
                r.satDist[2].toFixed(1),
                r.satDist[3].toFixed(1),
                r.satDist[4].toFixed(1),
                r.satDist[5].toFixed(1),
                r.indexS.toFixed(2),
                r.gap.toFixed(2),
                r.impSatisf.toFixed(2),
                `"${csiPhaseStr}"`,
                `"${dataset.csiTotal.toFixed(2)}%"`
            ].join(',') + '\n';
        });

        csv += `\nTOTAL FILTER AKTIF,"","",${dataset.rows.length} Indikator,,,,,,${dataset.totalSumI.toFixed(2)},,,,,,${dataset.avgS.toFixed(2)} (avg),${(dataset.avgS - dataset.avgI).toFixed(2)},${dataset.totalSumIxS.toFixed(2)},,"${dataset.csiTotal.toFixed(2)}%"\n`;

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'matriks-perhitungan-csi-pt-len-railway-systems.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        if (typeof window.showAdminToast === 'function') {
            window.showAdminToast('Matriks CSI PT Len Railway Systems berhasil diunduh.');
        }
    };

    window.LrsCsiEngine = {
        init: refresh,
        refresh: refresh,
        setSubTab: window.switchSurveysSubTab,
        compute: computeCsiDataset
    };

    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('csi-matrix-root')) {
            refresh();
        }
    });
})();
