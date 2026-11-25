/**
 * PT Len Railway Systems — Modular Survey Respondent Metadata Editor
 * Mengelola buka, pre-fill, validasi, simpan ke localStorage ('lrs_survey_data'),
 * serta sinkronisasi real-time tabel Rekap Responden, Counter, dan CSI Engine.
 */
(function () {
    'use strict';

    const CATEGORY_NAMES = {
        A: 'Layanan & Dukungan',
        B: 'Pelaksanaan Proyek',
        C: 'Masa Pemeliharaan'
    };

    const modalState = {
        targetIndex: -1,
        targetReferenceNo: null,
        originalCategoryId: 'A',
        isOpen: false,
        hideTimer: null
    };

    function getElements() {
        return {
            modal: document.getElementById('editSurveyMetadataModal'),
            panel: document.getElementById('edit-metadata-modal-panel'),
            form: document.getElementById('form-edit-survey-metadata'),
            refBadge: document.getElementById('edit-meta-ref-badge'),
            projectSelect: document.getElementById('edit-meta-project'),
            clientInput: document.getElementById('edit-meta-client'),
            clientErr: document.getElementById('err-edit-meta-client'),
            clientRoleInput: document.getElementById('edit-meta-client-role'),
            categorySelect: document.getElementById('edit-meta-category'),
            categoryWarning: document.getElementById('edit-meta-category-warning'),
            personnelInput: document.getElementById('edit-meta-personnel'),
            personnelList: document.getElementById('edit-meta-personnel-list'),
            personnelErr: document.getElementById('err-edit-meta-personnel')
        };
    }

    function readStorageArray(key) {
        try {
            const parsed = JSON.parse(localStorage.getItem(key) || '[]');
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    }

    function isGeneralSurveyItem(item) {
        if (!item) return false;
        return item.survey_source === 'general' || item.project_code === 'GENERAL';
    }

    function populateProjectOptions(projectSelect, currentSurvey) {
        if (!projectSelect) return;

        const projects = readStorageArray('lrs_projects_data');
        const surveys = readStorageArray('lrs_survey_data');
        const seen = new Set();

        while (projectSelect.firstChild) {
            projectSelect.removeChild(projectSelect.firstChild);
        }

        const generalOpt = new Option('Survei Umum (Non-Kontrak)', 'GENERAL');
        projectSelect.add(generalOpt);

        const addUniqueProject = (name) => {
            const clean = String(name || '').trim();
            if (!clean || clean === 'Survei Umum (Non-Kontrak)') return;
            const key = clean.toLowerCase();
            if (seen.has(key)) return;
            seen.add(key);
            projectSelect.add(new Option(clean, clean));
        };

        projects.forEach(p => addUniqueProject(p.name));
        surveys.forEach(s => {
            if (!isGeneralSurveyItem(s)) {
                addUniqueProject(s.project_name);
            }
        });

        if (isGeneralSurveyItem(currentSurvey)) {
            projectSelect.value = 'GENERAL';
        } else {
            const curName = String(currentSurvey.project_name || '').trim();
            if (curName) addUniqueProject(curName);
            projectSelect.value = curName || 'GENERAL';
        }
    }

    function populatePersonnelDatalist(datalistEl, currentPersonnel) {
        if (!datalistEl) return;

        while (datalistEl.firstChild) {
            datalistEl.removeChild(datalistEl.firstChild);
        }

        const personnelList = readStorageArray('lrs_personnel_data');
        const surveys = readStorageArray('lrs_survey_data');
        const seen = new Set();

        const appendOpt = (name) => {
            const clean = String(name || '').trim();
            if (!clean) return;
            const key = clean.toLowerCase();
            if (seen.has(key)) return;
            seen.add(key);
            const opt = document.createElement('option');
            opt.value = clean;
            datalistEl.appendChild(opt);
        };

        personnelList.forEach(p => appendOpt(p.name));
        surveys.forEach(s => appendOpt(s.accompanying_personnel));
        appendOpt(currentPersonnel);
    }

    function toggleFieldError(errEl, inputEl, hasError) {
        if (errEl) errEl.classList.toggle('hidden', !hasError);
        if (inputEl) {
            inputEl.classList.toggle('border-rose-400', hasError);
            inputEl.classList.toggle('bg-rose-50/40', hasError);
        }
    }

    function updateCategoryWarningVisibility() {
        const { categorySelect, categoryWarning } = getElements();
        if (!categorySelect || !categoryWarning) return;
        const changed = String(categorySelect.value || '').toUpperCase() !== String(modalState.originalCategoryId || '').toUpperCase();
        categoryWarning.classList.toggle('hidden', !changed);
    }

    function openModal(identifier) {
        const surveys = readStorageArray('lrs_survey_data');
        let idx = -1;
        let survey = null;

        if (typeof identifier === 'number' && surveys[identifier]) {
            idx = identifier;
            survey = surveys[identifier];
        } else {
            idx = surveys.findIndex(s => s.reference_no === String(identifier));
            if (idx >= 0) survey = surveys[idx];
        }

        if (!survey) return;

        const els = getElements();
        if (!els.modal || !els.panel) return;

        if (modalState.hideTimer) {
            clearTimeout(modalState.hideTimer);
            modalState.hideTimer = null;
        }

        modalState.targetIndex = idx;
        modalState.targetReferenceNo = survey.reference_no || null;
        modalState.originalCategoryId = String(survey.category_id || 'B').toUpperCase();
        modalState.isOpen = true;

        if (els.refBadge) {
            els.refBadge.textContent = survey.reference_no || `SRV-${String(idx + 1).padStart(4, '0')}`;
        }

        populateProjectOptions(els.projectSelect, survey);
        populatePersonnelDatalist(els.personnelList, survey.accompanying_personnel);

        if (els.clientInput) els.clientInput.value = survey.client_name || survey.respondent_id || '';
        if (els.clientRoleInput) els.clientRoleInput.value = survey.client_role || '';
        if (els.categorySelect) els.categorySelect.value = modalState.originalCategoryId;
        if (els.personnelInput) {
            els.personnelInput.value = survey.accompanying_personnel
                || (isGeneralSurveyItem(survey) ? 'Layanan Umum LRS' : 'Ir. Dwi Suryanto, S.T.');
        }

        toggleFieldError(els.clientErr, els.clientInput, false);
        toggleFieldError(els.personnelErr, els.personnelInput, false);
        updateCategoryWarningVisibility();

        els.modal.classList.remove('hidden');
        els.modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            els.modal.classList.remove('opacity-0');
            els.panel.classList.remove('translate-y-4', 'sm:scale-95');
            els.panel.classList.add('translate-y-0', 'sm:scale-100');
            if (els.projectSelect) els.projectSelect.focus();
        });
    }

    function closeModal() {
        const els = getElements();
        if (!els.modal || !els.panel || !modalState.isOpen) return;

        modalState.isOpen = false;
        els.modal.classList.add('opacity-0');
        els.panel.classList.remove('translate-y-0', 'sm:scale-100');
        els.panel.classList.add('translate-y-4', 'sm:scale-95');
        document.body.style.overflow = '';

        modalState.hideTimer = setTimeout(() => {
            if (!modalState.isOpen && els.modal) {
                els.modal.classList.add('hidden');
                els.modal.classList.remove('flex');
            }
        }, 200);
    }

    function saveMetadataChanges(event) {
        if (event) event.preventDefault();

        const els = getElements();
        const clientVal = String(els.clientInput?.value || '').trim();
        const clientRoleVal = String(els.clientRoleInput?.value || '').trim();
        const projectVal = String(els.projectSelect?.value || 'GENERAL').trim();
        const categoryVal = String(els.categorySelect?.value || 'B').trim().toUpperCase();
        const personnelVal = String(els.personnelInput?.value || '').trim();

        const hasClientErr = !clientVal;
        const hasPersonnelErr = !personnelVal;

        toggleFieldError(els.clientErr, els.clientInput, hasClientErr);
        toggleFieldError(els.personnelErr, els.personnelInput, hasPersonnelErr);

        if (hasClientErr || hasPersonnelErr) {
            if (hasClientErr && els.clientInput) els.clientInput.focus();
            else if (hasPersonnelErr && els.personnelInput) els.personnelInput.focus();
            return;
        }

        const surveys = readStorageArray('lrs_survey_data');
        let idx = modalState.targetIndex;
        if (modalState.targetReferenceNo) {
            const byRefIdx = surveys.findIndex(s => s.reference_no === modalState.targetReferenceNo);
            if (byRefIdx >= 0) idx = byRefIdx;
        }

        if (idx < 0 || !surveys[idx]) {
            closeModal();
            return;
        }

        const target = surveys[idx];
        const projects = readStorageArray('lrs_projects_data');
        const categoryName = CATEGORY_NAMES[categoryVal] || target.category_name || 'Pelaksanaan Proyek';

        if (projectVal === 'GENERAL') {
            target.survey_source = 'general';
            target.project_code = 'GENERAL';
            target.project_name = clientRoleVal && clientRoleVal !== 'Responden Umum'
                ? `Survei Umum - ${clientRoleVal}`
                : 'Survei Umum (Non-Kontrak)';
        } else {
            const matchedProject = projects.find(p => String(p.name || '').trim().toLowerCase() === projectVal.toLowerCase());
            target.survey_source = 'contract';
            target.project_name = matchedProject ? matchedProject.name : projectVal;
            target.project_code = matchedProject ? (matchedProject.code || '') : (target.project_code === 'GENERAL' ? '' : (target.project_code || ''));
        }

        target.client_name = clientVal;
        if (clientRoleVal) {
            target.client_role = clientRoleVal;
        } else if (projectVal === 'GENERAL') {
            target.client_role = 'Responden Umum';
        }

        target.category_id = categoryVal;
        target.category_name = categoryName;
        if (Array.isArray(target.answers)) {
            target.answers.forEach(ans => {
                if (ans && typeof ans === 'object') {
                    ans.category = categoryName;
                }
            });
        }

        target.accompanying_personnel = personnelVal;
        target.metadataUpdatedAt = new Date().toISOString();

        try {
            localStorage.setItem('lrs_survey_data', JSON.stringify(surveys));
        } catch (err) {
            console.error('Gagal menyimpan pembaruan metadata responden:', err);
            return;
        }

        closeModal();

        if (typeof window.showAdminToast === 'function') {
            window.showAdminToast('Metadata responden berhasil diperbarui');
        }

        if (typeof window.refreshAdminSurveyViews === 'function') {
            window.refreshAdminSurveyViews();
        } else {
            if (typeof window.onSurveyFilterChange === 'function') window.onSurveyFilterChange();
            if (window.LrsCsiEngine && typeof window.LrsCsiEngine.refresh === 'function') window.LrsCsiEngine.refresh();
        }

        window.dispatchEvent(new CustomEvent('lrs:survey-metadata-updated', {
            detail: { reference_no: target.reference_no, category_id: target.category_id }
        }));
    }

    function bindModalEvents() {
        const els = getElements();
        if (!els.modal || !els.form) return;

        els.form.addEventListener('submit', saveMetadataChanges);

        if (els.categorySelect) {
            els.categorySelect.addEventListener('change', updateCategoryWarningVisibility);
        }

        if (els.clientInput) {
            els.clientInput.addEventListener('input', () => {
                toggleFieldError(els.clientErr, els.clientInput, !String(els.clientInput.value || '').trim());
            });
        }

        if (els.personnelInput) {
            els.personnelInput.addEventListener('input', () => {
                toggleFieldError(els.personnelErr, els.personnelInput, !String(els.personnelInput.value || '').trim());
            });
        }

        els.modal.addEventListener('click', (e) => {
            if (e.target === els.modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modalState.isOpen) {
                closeModal();
            }
        });
    }

    window.openEditSurveyMetadataModal = openModal;
    window.closeEditSurveyMetadataModal = closeModal;
    window.LrsSurveyMetadataModal = {
        open: openModal,
        close: closeModal
    };

    document.addEventListener('DOMContentLoaded', bindModalEvents);
})();
