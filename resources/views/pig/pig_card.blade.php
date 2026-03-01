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
            <input type="text" placeholder="Search batch ID, stage, or feeding status" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
        </label>
        <label class="block">
            <span class="sr-only">Filter stage</span>
            <select class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                <option>All Stages</option>
                <option>Weaner</option>
                <option>Grower</option>
                <option>Finisher</option>
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
            </tbody>
        </table>
    </div>
</article>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const batchesBody = document.getElementById('pig-batches-body');
        if (!batchesBody || batchesBody.dataset.bound === '1') {
            return;
        }

        batchesBody.dataset.bound = '1';
        const batchesApiUrl = @js(route('batches.index'));
        const batchesCacheKey = 'smarthog:pig:batches:v1';

        const escapeHtml = function (value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        };

        const renderRows = function (batches) {
            if (!Array.isArray(batches) || batches.length === 0) {
                batchesBody.innerHTML = '<tr><td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">No pig batch records yet.</td></tr>';
                return;
            }

            batchesBody.innerHTML = batches.map(function (batch) {
                const batchCode = batch.batch_code ?? 'N/A';
                const numberOfPigs = Number(batch.no_of_pigs ?? 0);
                const currentAge = Number(batch.current_age ?? 0);
                const growthStage = batch.growth_stage_name ?? batch.growth_stage_id ?? 'N/A';
                const avgWeight = Number(batch.avg_weight ?? 0);

                return `
                    <tr class="align-middle odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
                        <td class="px-4 py-3 font-semibold text-slate-800 text-nowrap">${escapeHtml(batchCode)}</td>
                        <td class="px-4 py-3 text-slate-600 text-nowrap">${escapeHtml(numberOfPigs)}</td>
                        <td class="px-4 py-3 text-slate-600 text-nowrap">${escapeHtml(currentAge + ' days')}</td>
                        <td class="px-4 py-3 text-slate-600 text-nowrap">${escapeHtml(growthStage)}</td>
                        <td class="px-4 py-3 text-slate-600 text-nowrap">${escapeHtml(avgWeight.toFixed(1) + ' kg')}</td>
                        <td class="px-4 py-3"><span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">No data</span></td>
                        <td class="px-4 py-3 text-nowrap"><span class="text-xs font-medium text-slate-500">No data</span></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5">
                                <button type="button" title="View" aria-label="View" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-slate-200 text-slate-700 transition hover:bg-slate-50">
                                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M1.7 10s2.9-5 8.3-5 8.3 5 8.3 5-2.9 5-8.3 5-8.3-5-8.3-5Z" />
                                        <circle cx="10" cy="10" r="2.5" />
                                    </svg>
                                </button>
                                <button type="button" title="Edit" aria-label="Edit" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-amber-200 text-amber-800 transition hover:bg-amber-50">
                                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.8 3.7a1.7 1.7 0 0 1 2.5 2.5L7 15.5l-3.5.8.8-3.5 9.5-9.1Z" />
                                    </svg>
                                </button>
                                <button type="button" title="Archive" aria-label="Archive" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-rose-200 text-rose-700 transition hover:bg-rose-50">
                                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 6h13M5 6v9a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6M8 6V4.8A.8.8 0 0 1 8.8 4h2.4a.8.8 0 0 1 .8.8V6" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        };

        const readCachedBatches = function () {
            try {
                const cached = sessionStorage.getItem(batchesCacheKey);
                if (!cached) {
                    return null;
                }

                const parsed = JSON.parse(cached);
                return Array.isArray(parsed) ? parsed : null;
            } catch (error) {
                return null;
            }
        };

        const writeCachedBatches = function (batches) {
            try {
                sessionStorage.setItem(batchesCacheKey, JSON.stringify(batches));
            } catch (error) {
                // Ignore cache write failures.
            }
        };

        const cachedBatches = readCachedBatches();
        if (Array.isArray(cachedBatches) && cachedBatches.length > 0) {
            renderRows(cachedBatches);
        }

        fetch(batchesApiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (payload) {
                const batches = Array.isArray(payload?.data) ? payload.data : [];
                renderRows(batches);
                if (batches.length > 0) {
                    writeCachedBatches(batches);
                }
            })
            .catch(function () {
                batchesBody.innerHTML = '<tr><td colspan="8" class="px-4 py-8 text-center text-sm text-rose-600">Unable to load pig batches right now.</td></tr>';
            });
    });
</script>
