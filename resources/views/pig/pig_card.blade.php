<article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2 sm:p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Pig Batches</h2>
            <p class="text-sm text-slate-600">Search and filter batches, then manage records quickly.</p>
        </div>
    </div>

    <div class="mt-4 grid gap-3 md:grid-cols-3">
        <label class="block md:col-span-2">
            <span class="sr-only">Search</span>
            <input id="pig-batch-search" type="text" placeholder="Search batch ID, stage, or feeding status" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
        </label>
        <label class="block">
            <span class="sr-only">Filter stage</span>
            <select id="pig-batch-stage-filter" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                <option value="">All Stages</option>
            </select>
        </label>
    </div>

    <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200">
        <table class="min-w-[920px] w-full text-left text-sm">
            <thead class="sticky top-0 bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-semibold text-nowrap">Batch ID</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">No. of Pigs</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Current Age</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Growth Stage</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Avg. Weight</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Feeding Status</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Health Alerts</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody id="pig-batches-body" class="divide-y divide-slate-100 bg-white">
                @for ($index = 0; $index < 5; $index++)
                    <tr class="animate-pulse">
                        <td class="px-4 py-3"><div class="h-4 w-20 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-24 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-16 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-20 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-16 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-6 w-20 rounded-full bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-20 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-8 w-28 rounded bg-slate-200"></div></td>
                    </tr>
                @endfor
                <tr id="pig-batches-no-results" class="hidden">
                    <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">No matching pig batches found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</article>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('pig-batches-body');
        const searchInput = document.getElementById('pig-batch-search');
        const stageFilter = document.getElementById('pig-batch-stage-filter');
        const deleteBatchModal = document.getElementById('pig-delete-batch-modal');
        const deleteBatchCodeLabel = document.getElementById('delete-batch-code');
        const deleteBatchNameLabel = document.getElementById('delete-batch-name');
        const deleteBatchCountLabel = document.getElementById('delete-batch-count');
        const confirmDeleteBatchButton = document.getElementById('confirm-delete-batch-button');
        const updateBatchModal = document.getElementById('pig-update-batch-modal');
        const updateBatchCodePreview = document.getElementById('update-batch-code-preview');
        const updateBatchStagePreview = document.getElementById('update-batch-stage-preview');
        const updateBatchPenPreview = document.getElementById('update-batch-pen-preview');
        const updateBatchCountPreview = document.getElementById('update-batch-count-preview');
        const updateBatchCodeInput = document.getElementById('update-batch-code');
        const updateBatchGrowthStageCodeInput = document.getElementById('update-batch-growth-stage-code');
        const updateBatchPenCodeInput = document.getElementById('update-batch-pen-code');
        const updateBatchNameInput = document.getElementById('update-batch-name');
        const updateBatchPigCountInput = document.getElementById('update-batch-pig-count');
        const updateBatchCurrentAgeInput = document.getElementById('update-batch-current-age');
        const updateBatchStageInput = document.getElementById('update-batch-stage');
        const updateBatchWeightInput = document.getElementById('update-batch-weight');
        const updateBatchPenInput = document.getElementById('update-batch-pen');
        const updateBatchNotesInput = document.getElementById('update-batch-notes');

        if (! tableBody || ! searchInput || ! stageFilter || tableBody.dataset.bound === '1') {
            return;
        }

        tableBody.dataset.bound = '1';
        const endpoint = '{{ route('api.batch.all') }}';
        const deleteEndpointTemplate = '{{ route('api.batch.delete', ['batch_code' => '__BATCH_CODE__']) }}';
        const growthApiUrl = '{{ route('api.growth.all') }}';
        const pensApiUrl = '{{ route('api.pens.all') }}';
        const csrfToken = '{{ csrf_token() }}';
        const growthCacheKey = 'smarthog:growth:stages:v1';
        const pensCacheKey = 'smarthog:pens:all:v1';
        let batchItems = [];
        let pendingDeleteBatchCode = '';
        let growthStagesPromise = null;
        let pensPromise = null;

        const escapeHtml = function (value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        };

        const renderStageOptions = function (items) {
            const stageNames = Array.from(new Set(items
                .map(function (item) {
                    const growthName = String(item.growth_name ?? '').trim();
                    if (growthName !== '') {
                        return growthName;
                    }

                    return String(item.growth_stage_id ?? '').trim();
                })
                .filter(function (stage) {
                    return stage !== '';
                })));

            stageFilter.innerHTML = '<option value="">All Stages</option>' + stageNames
                .map(function (stage) {
                    return `<option value="${escapeHtml(stage)}">${escapeHtml(stage)}</option>`;
                })
                .join('');
        };

        const renderRows = function (items) {
            const rows = items.map(function (item) {
                const batchCode = String(item.batch_code ?? '');
                const pigs = Number(item.no_of_pigs ?? 0);
                const currentAge = Number(item.current_age ?? 0);
                const stage = String(item.growth_name ?? item.growth_stage_id ?? 'N/A');
                const weight = Number(item.avg_weight ?? 0);
                const isFeedingGood = pigs > 0;
                const hasAlert = currentAge <= 0;

                return `
                    <tr class="odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
                        <td class="px-4 py-3 font-semibold text-slate-900">${escapeHtml(batchCode)}</td>
                        <td class="px-4 py-3 text-slate-700">${escapeHtml(pigs)}</td>
                        <td class="px-4 py-3 text-slate-700">${escapeHtml(currentAge)} days</td>
                        <td class="px-4 py-3 text-slate-700">${escapeHtml(stage)}</td>
                        <td class="px-4 py-3 text-slate-700">${escapeHtml(weight.toFixed(1))} kg</td>
                        <td class="px-4 py-3">
                            ${isFeedingGood
                                ? '<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Active</span>'
                                : '<span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Idle</span>'}
                        </td>
                        <td class="px-4 py-3 text-slate-700">${hasAlert ? 'Check age data' : 'None'}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button 
                                    type="button"
                                    data-update-batch="${escapeHtml(batchCode)}"
                                    data-update-batch-name="${escapeHtml(item.batch_name ?? 'Unknown Batch')}"
                                    data-update-batch-count="${escapeHtml(pigs)}"
                                    data-update-batch-age="${escapeHtml(currentAge)}"
                                    data-update-batch-stage="${escapeHtml(stage)}"
                                    data-update-batch-stage-code="${escapeHtml(item.growth_stage_id ?? '')}"
                                    data-update-batch-weight="${escapeHtml(weight.toFixed(1))}"
                                    data-update-batch-pen-code="${escapeHtml(item.pen_code_id ?? item.pen_code ?? '')}"
                                    data-update-batch-notes="${escapeHtml(item.notes ?? '')}"
                                    class="flex items-center gap-1 rounded-lg border border-emerald-200 px-2.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">

                                    <!-- Pencil Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        class="h-4 w-4" 
                                        fill="none" 
                                        viewBox="0 0 24 24" 
                                        stroke="currentColor" 
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M11 5h2m-6 14h12a2 2 0 002-2V9l-6-6H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg> 
                                </button>

                                <button 
                                    type="button"
                                    data-delete-batch="${escapeHtml(batchCode)}"
                                    data-delete-batch-name="${escapeHtml(item.batch_name ?? 'Unknown Batch')}"
                                    data-delete-batch-count="${escapeHtml(pigs)}"
                                    class="flex items-center gap-1 rounded-lg border border-rose-200 px-2.5 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50">

                                    <!-- Trash Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        class="h-4 w-4" 
                                        fill="none" 
                                        viewBox="0 0 24 24" 
                                        stroke="currentColor" 
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M19 7l-1 14H6L5 7m5-3h4m-6 3h8m-1 0V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

        tableBody.innerHTML = rows + '<tr id="pig-batches-no-results" class="hidden"><td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">No matching pig batches found.</td></tr>';
        };

        const applyFilters = function () {
            const keyword = String(searchInput.value ?? '').trim().toLowerCase();
            const stageValue = String(stageFilter.value ?? '').trim().toLowerCase();

            const filtered = batchItems.filter(function (item) {
                const batchCode = String(item.batch_code ?? '').toLowerCase();
                const stage = String(item.growth_name ?? item.growth_stage_id ?? '').toLowerCase();
                const notes = String(item.notes ?? '').toLowerCase();

                const matchesKeyword = keyword === '' || batchCode.includes(keyword) || stage.includes(keyword) || notes.includes(keyword);
                const matchesStage = stageValue === '' || stage === stageValue;

                return matchesKeyword && matchesStage;
            });

            renderRows(filtered);
            const noResultsRow = document.getElementById('pig-batches-no-results');
            if (noResultsRow) {
                noResultsRow.classList.toggle('hidden', filtered.length !== 0);
            }
        };

        const closeDeleteBatchModal = function () {
            if (! (deleteBatchModal instanceof HTMLElement)) {
                return;
            }

            deleteBatchModal.classList.add('hidden');
            deleteBatchModal.setAttribute('aria-hidden', 'true');
            pendingDeleteBatchCode = '';

            if (confirmDeleteBatchButton instanceof HTMLButtonElement) {
                confirmDeleteBatchButton.disabled = false;
                confirmDeleteBatchButton.classList.remove('cursor-not-allowed', 'opacity-70');
            }
        };

        const openDeleteBatchModal = function (batchCode, batchName, pigs) {
            if (! (deleteBatchModal instanceof HTMLElement)) {
                return;
            }

            pendingDeleteBatchCode = batchCode;

            if (deleteBatchCodeLabel instanceof HTMLElement) {
                deleteBatchCodeLabel.textContent = batchCode || 'N/A';
            }

            if (deleteBatchNameLabel instanceof HTMLElement) {
                deleteBatchNameLabel.textContent = batchName || 'Unknown Batch';
            }

            if (deleteBatchCountLabel instanceof HTMLElement) {
                deleteBatchCountLabel.textContent = String(pigs || 0) + ' pigs';
            }

            deleteBatchModal.classList.remove('hidden');
            deleteBatchModal.setAttribute('aria-hidden', 'false');
        };

        const closeUpdateBatchModal = function () {
            if (! (updateBatchModal instanceof HTMLElement)) {
                return;
            }

            updateBatchModal.classList.add('hidden');
            updateBatchModal.setAttribute('aria-hidden', 'true');
        };

        const syncGrowthStageCode = function () {
            if (! (updateBatchStageInput instanceof HTMLSelectElement) || ! (updateBatchGrowthStageCodeInput instanceof HTMLInputElement)) {
                return;
            }

            const selectedOption = updateBatchStageInput.options[updateBatchStageInput.selectedIndex];
            const growthCode = selectedOption ? String(selectedOption.getAttribute('data-growth-code') ?? '').trim() : '';
            updateBatchGrowthStageCodeInput.value = growthCode;
        };

        const syncPenCode = function () {
            if (! (updateBatchPenInput instanceof HTMLSelectElement) || ! (updateBatchPenCodeInput instanceof HTMLInputElement)) {
                return;
            }

            updateBatchPenCodeInput.value = String(updateBatchPenInput.value ?? '').trim();
        };

        const renderUpdateStageOptions = function (stages) {
            if (! (updateBatchStageInput instanceof HTMLSelectElement) || ! Array.isArray(stages)) {
                return;
            }

            const options = stages
                .map(function (stage) {
                    const growthName = String(stage.growth_name ?? '').trim();
                    const growthCode = String(stage.growth_code ?? '').trim();
                    if (growthName === '') {
                        return '';
                    }

                    return `<option value="${escapeHtml(growthName)}" data-growth-code="${escapeHtml(growthCode)}">${escapeHtml(growthName)}</option>`;
                })
                .join('');

            updateBatchStageInput.innerHTML = '<option value="">Select stage</option>' + options;
            syncGrowthStageCode();
        };

        const renderUpdatePenOptions = function (pens) {
            if (! (updateBatchPenInput instanceof HTMLSelectElement) || ! Array.isArray(pens)) {
                return;
            }

            const options = pens
                .map(function (pen) {
                    const penCode = String(pen.pen_code ?? '').trim();
                    const penName = String(pen.pen_name ?? '').trim();
                    if (penCode === '' || penName === '') {
                        return '';
                    }

                    return `<option value="${escapeHtml(penCode)}">${escapeHtml(penName)} (${escapeHtml(penCode)})</option>`;
                })
                .join('');

            updateBatchPenInput.innerHTML = '<option value="">Select pen</option>' + options;
            syncPenCode();
        };

        const readCachedItems = function (cacheKey) {
            try {
                const cached = sessionStorage.getItem(cacheKey);
                if (! cached) {
                    return null;
                }

                const parsed = JSON.parse(cached);
                return Array.isArray(parsed) ? parsed : null;
            } catch (error) {
                return null;
            }
        };

        const writeCachedItems = function (cacheKey, items) {
            try {
                sessionStorage.setItem(cacheKey, JSON.stringify(items));
            } catch (error) {
                return;
            }
        };

        const loadGrowthStages = function () {
            if (growthStagesPromise) {
                return growthStagesPromise;
            }

            const cached = readCachedItems(growthCacheKey);
            if (Array.isArray(cached) && cached.length > 0) {
                renderUpdateStageOptions(cached);
            }

            growthStagesPromise = fetch(growthApiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (payload) {
                    const stages = Array.isArray(payload?.data) ? payload.data : [];
                    if (stages.length > 0) {
                        renderUpdateStageOptions(stages);
                        writeCachedItems(growthCacheKey, stages);
                    }
                })
                .catch(function () {
                    return;
                });

            return growthStagesPromise;
        };

        const loadPens = function () {
            if (pensPromise) {
                return pensPromise;
            }

            const cached = readCachedItems(pensCacheKey);
            if (Array.isArray(cached) && cached.length > 0) {
                renderUpdatePenOptions(cached);
            }

            pensPromise = fetch(pensApiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (payload) {
                    const source = payload?.data;
                    const pens = Array.isArray(source?.data) ? source.data : (Array.isArray(source) ? source : []);
                    if (pens.length > 0) {
                        renderUpdatePenOptions(pens);
                        writeCachedItems(pensCacheKey, pens);
                    }
                })
                .catch(function () {
                    return;
                });

            return pensPromise;
        };

        const openUpdateBatchModal = function (batch) {
            if (! (updateBatchModal instanceof HTMLElement)) {
                return;
            }

            const batchCode = String(batch.batch_code ?? '').trim();
            const batchName = String(batch.batch_name ?? '').trim();
            const pigs = Number(batch.no_of_pigs ?? 0);
            const currentAge = Number(batch.current_age ?? 0);
            const stageName = String(batch.growth_name ?? batch.growth_stage_id ?? '').trim();
            const stageCode = String(batch.growth_stage_id ?? '').trim();
            const avgWeight = Number(batch.avg_weight ?? 0);
            const penCode = String(batch.pen_code_id ?? batch.pen_code ?? '').trim();
            const notes = String(batch.notes ?? '').trim();

            if (updateBatchCodePreview instanceof HTMLElement) {
                updateBatchCodePreview.textContent = batchCode || 'N/A';
            }
            if (updateBatchCountPreview instanceof HTMLElement) {
                updateBatchCountPreview.textContent = String(pigs || 0) + ' pigs';
            }
            if (updateBatchStagePreview instanceof HTMLElement) {
                updateBatchStagePreview.textContent = stageName || 'N/A';
            }
            if (updateBatchPenPreview instanceof HTMLElement) {
                updateBatchPenPreview.textContent = penCode || 'N/A';
            }
            if (updateBatchCodeInput instanceof HTMLInputElement) {
                updateBatchCodeInput.value = batchCode || '';
            }
            if (updateBatchNameInput instanceof HTMLInputElement) {
                updateBatchNameInput.value = batchName;
            }
            if (updateBatchPigCountInput instanceof HTMLInputElement) {
                updateBatchPigCountInput.value = String(pigs);
            }
            if (updateBatchCurrentAgeInput instanceof HTMLInputElement) {
                updateBatchCurrentAgeInput.value = String(currentAge);
            }
            if (updateBatchWeightInput instanceof HTMLInputElement) {
                updateBatchWeightInput.value = String(avgWeight);
            }
            if (updateBatchNotesInput instanceof HTMLTextAreaElement) {
                updateBatchNotesInput.value = notes;
            }
            if (updateBatchStageInput instanceof HTMLSelectElement) {
                updateBatchStageInput.value = stageName;
            }
            if (updateBatchGrowthStageCodeInput instanceof HTMLInputElement) {
                updateBatchGrowthStageCodeInput.value = stageCode;
            }
            if (updateBatchPenInput instanceof HTMLSelectElement) {
                updateBatchPenInput.value = penCode;
            }
            if (updateBatchPenCodeInput instanceof HTMLInputElement) {
                updateBatchPenCodeInput.value = penCode;
            }
            syncGrowthStageCode();
            syncPenCode();
            if (updateBatchGrowthStageCodeInput instanceof HTMLInputElement && updateBatchGrowthStageCodeInput.value.trim() === '') {
                updateBatchGrowthStageCodeInput.value = stageCode;
            }
            if (updateBatchPenCodeInput instanceof HTMLInputElement && updateBatchPenCodeInput.value.trim() === '') {
                updateBatchPenCodeInput.value = penCode;
            }

            updateBatchModal.classList.remove('hidden');
            updateBatchModal.setAttribute('aria-hidden', 'false');
        };

        const deleteBatchRecord = function (batchCode) {
            if (! batchCode) {
                return;
            }

            if (confirmDeleteBatchButton instanceof HTMLButtonElement) {
                confirmDeleteBatchButton.disabled = true;
                confirmDeleteBatchButton.classList.add('cursor-not-allowed', 'opacity-70');
            }

            const endpointUrl = deleteEndpointTemplate.replace('__BATCH_CODE__', encodeURIComponent(batchCode));

            fetch(endpointUrl, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
            })
                .then(function (response) {
                    return response.json().then(function (payload) {
                        return { ok: response.ok, payload: payload };
                    });
                })
                .then(function (result) {
                    if (! result.ok || ! result.payload?.ok) {
                        const message = typeof result.payload?.message === 'string' && result.payload.message.trim() !== ''
                            ? result.payload.message
                            : 'Failed to remove batch. Please try again.';

                        if (typeof window.showWarningAlert === 'function') {
                            window.showWarningAlert({
                                title: 'Delete Failed',
                                message: message,
                                durationMs: 3200,
                            });
                        }
                        return;
                    }

                    batchItems = batchItems.filter(function (item) {
                        return String(item.batch_code ?? '') !== batchCode;
                    });
                    applyFilters();
                    closeDeleteBatchModal();

                    if (typeof window.showSuccessAlert === 'function') {
                        window.showSuccessAlert({
                            title: 'Batch Deleted',
                            message: result.payload?.message || 'Batch removed successfully.',
                            durationMs: 2400,
                        });
                    }
                })
                .catch(function () {
                    if (typeof window.showWarningAlert === 'function') {
                        window.showWarningAlert({
                            title: 'Delete Failed',
                            message: 'Unable to remove batch right now. Please try again.',
                            durationMs: 3200,
                        });
                    }
                })
                .finally(function () {
                    if (confirmDeleteBatchButton instanceof HTMLButtonElement) {
                        confirmDeleteBatchButton.disabled = false;
                        confirmDeleteBatchButton.classList.remove('cursor-not-allowed', 'opacity-70');
                    }
                });
        };

        fetch(endpoint, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    return { ok: response.ok, payload: payload };
                });
            })
            .then(function (result) {
                if (! result.ok || ! result.payload?.ok) {
                    throw new Error('Failed to load pig batches.');
                }

                const items = Array.isArray(result.payload?.data) ? result.payload.data : [];
                batchItems = items;
                renderStageOptions(items);
                applyFilters();
            })
            .catch(function () {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-sm text-rose-700">Unable to load pig batches right now. Please refresh the page.</td>
                    </tr>
                `;
            });

        searchInput.addEventListener('input', applyFilters);
        stageFilter.addEventListener('change', applyFilters);
        tableBody.addEventListener('click', function (event) {
            const target = event.target;
            if (! (target instanceof Element)) {
                return;
            }

            const updateButton = target.closest('[data-update-batch]');
            if (updateButton instanceof HTMLElement) {
                const batch = {
                    batch_code: String(updateButton.getAttribute('data-update-batch') ?? '').trim(),
                    batch_name: String(updateButton.getAttribute('data-update-batch-name') ?? '').trim(),
                    no_of_pigs: Number(updateButton.getAttribute('data-update-batch-count') ?? 0),
                    current_age: Number(updateButton.getAttribute('data-update-batch-age') ?? 0),
                    growth_name: String(updateButton.getAttribute('data-update-batch-stage') ?? '').trim(),
                    growth_stage_id: String(updateButton.getAttribute('data-update-batch-stage-code') ?? '').trim(),
                    avg_weight: Number(updateButton.getAttribute('data-update-batch-weight') ?? 0),
                    pen_code_id: String(updateButton.getAttribute('data-update-batch-pen-code') ?? '').trim(),
                    notes: String(updateButton.getAttribute('data-update-batch-notes') ?? '').trim(),
                };

                Promise.all([loadGrowthStages(), loadPens()]).finally(function () {
                    openUpdateBatchModal(batch);
                });
                return;
            }

            const deleteButton = target.closest('[data-delete-batch]');
            if (! (deleteButton instanceof HTMLElement)) {
                return;
            }

            const batchCode = deleteButton.getAttribute('data-delete-batch') ?? '';
            if (batchCode === '') {
                return;
            }

            const batchName = deleteButton.getAttribute('data-delete-batch-name') ?? 'Unknown Batch';
            const pigCount = Number(deleteButton.getAttribute('data-delete-batch-count') ?? 0);
            openDeleteBatchModal(batchCode, batchName, pigCount);
        });

        document.querySelectorAll('[data-delete-batch-modal-close="1"]').forEach(function (element) {
            element.addEventListener('click', closeDeleteBatchModal);
        });
        document.querySelectorAll('[data-update-batch-modal-close="1"]').forEach(function (element) {
            element.addEventListener('click', closeUpdateBatchModal);
        });
        if (updateBatchStageInput instanceof HTMLSelectElement) {
            updateBatchStageInput.addEventListener('change', syncGrowthStageCode);
        }
        if (updateBatchPenInput instanceof HTMLSelectElement) {
            updateBatchPenInput.addEventListener('change', syncPenCode);
        }

        if (confirmDeleteBatchButton instanceof HTMLButtonElement) {
            confirmDeleteBatchButton.addEventListener('click', function () {
                if (pendingDeleteBatchCode === '') {
                    return;
                }

                deleteBatchRecord(pendingDeleteBatchCode);
            });
        }
    });
</script>
