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

        if (! tableBody || ! searchInput || ! stageFilter || tableBody.dataset.bound === '1') {
            return;
        }

        tableBody.dataset.bound = '1';
        const endpoint = '{{ route('api.batch.all') }}';
        const deleteEndpointTemplate = '{{ route('api.batch.delete', ['batch_code' => '__BATCH_CODE__']) }}';
        const csrfToken = '{{ csrf_token() }}';
        let batchItems = [];
        let pendingDeleteBatchCode = '';

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
                                <button type="button" data-delete-batch="${escapeHtml(batchCode)}" data-delete-batch-name="${escapeHtml(item.batch_name ?? 'Unknown Batch')}" data-delete-batch-count="${escapeHtml(pigs)}" class="rounded-lg border border-rose-200 px-2.5 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50">Delete</button>
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
            if (! (target instanceof HTMLElement)) {
                return;
            }

            const button = target.closest('[data-delete-batch]');
            if (! (button instanceof HTMLElement)) {
                return;
            }

            const batchCode = button.getAttribute('data-delete-batch') ?? '';
            if (batchCode === '') {
                return;
            }

            const batchName = button.getAttribute('data-delete-batch-name') ?? 'Unknown Batch';
            const pigCount = Number(button.getAttribute('data-delete-batch-count') ?? 0);
            openDeleteBatchModal(batchCode, batchName, pigCount);
        });

        document.querySelectorAll('[data-delete-batch-modal-close="1"]').forEach(function (element) {
            element.addEventListener('click', closeDeleteBatchModal);
        });

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
