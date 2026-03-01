<article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Pen Records</h2>
            <p class="text-sm text-slate-600">List of all pens with capacity, status, notes, and quick actions.</p>
        </div>
    </div>

    <div class="mt-4 grid gap-3 sm:grid-cols-2">
        <div>
            <label for="pen-search-input" class="mb-1 block text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Search Pen</label>
            <input
                id="pen-search-input"
                type="search"
                placeholder="Search by pen id, name, or notes..."
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
            >
        </div>
        <div>
            <label for="pen-status-filter" class="mb-1 block text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Filter Status</label>
            <select
                id="pen-status-filter"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
            >
                <option value="">All Statuses</option>
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200">
        <table id="pen-records-table" class="min-w-[880px] w-full text-left text-sm">
            <thead class="sticky top-0 bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-semibold text-nowrap">Pen ID</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Pen Name</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Capacity</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Status</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Notes</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody id="pen-records-body" class="divide-y divide-slate-100 bg-white">
                @for ($index = 0; $index < 5; $index++)
                    <tr data-pen-skeleton="1" class="animate-pulse">
                        <td class="px-4 py-3"><div class="h-4 w-16 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-24 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-12 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-6 w-20 rounded-full bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-40 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-8 w-20 rounded bg-slate-200"></div></td>
                    </tr>
                @endfor
                <tr id="pen-no-results-row" class="hidden">
                    <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">No matching pen records found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</article>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('pen-search-input');
        const statusFilter = document.getElementById('pen-status-filter');
        const tableBody = document.getElementById('pen-records-body');
        if (!searchInput || !statusFilter || !tableBody || searchInput.dataset.bound === '1') {
            return;
        }

        searchInput.dataset.bound = '1';

        const pensApiUrl = @js(route('pens.index'));
        const pigPageUrl = @js(route('show.pig'));
        const pensCacheKey = 'smarthog:pig:pens:v1';

        const escapeHtml = function (value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        };

        const toTitleCase = function (value) {
            const lowered = String(value ?? '').toLowerCase();
            if (lowered === '') {
                return 'Unknown';
            }

            return lowered.charAt(0).toUpperCase() + lowered.slice(1);
        };

        const renderStatusBadge = function (status) {
            if (status === 'available') {
                return '<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Available</span>';
            }

            if (status === 'occupied') {
                return '<span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Occupied</span>';
            }

            return '<span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">' + escapeHtml(toTitleCase(status)) + '</span>';
        };

        const buildActionUrl = function (mode, pen) {
            const params = new URLSearchParams();
            params.set('modal', mode);
            params.set('pen_id', String(pen.pen_code ?? ''));
            params.set('pen_name', String(pen.pen_name ?? ''));
            params.set('capacity', String(pen.capacity ?? 0));

            if (mode === 'edit-pen') {
                params.set('status', String(pen.status ?? '').toLowerCase());
                params.set('notes', String(pen.notes ?? ''));
            }

            return pigPageUrl + '?' + params.toString();
        };

        const buildRowMarkup = function (pen) {
            const normalizedStatus = String(pen.status ?? '').toLowerCase();
            const notes = pen.notes ? String(pen.notes) : 'No notes';
            const searchIndex = (String(pen.pen_code ?? '') + ' ' + String(pen.pen_name ?? '') + ' ' + notes).toLowerCase();

            return `
                <tr
                    data-pen-row="1"
                    data-status="${escapeHtml(normalizedStatus)}"
                    data-search="${escapeHtml(searchIndex)}"
                    class="align-middle odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80"
                >
                    <td class="px-4 py-3 font-semibold text-slate-800 text-nowrap">${escapeHtml(pen.pen_code ?? '')}</td>
                    <td class="px-4 py-3 text-slate-700 text-nowrap">${escapeHtml(pen.pen_name ?? '')}</td>
                    <td class="px-4 py-3 text-slate-600 text-nowrap">${escapeHtml(Number(pen.capacity ?? 0).toLocaleString())}</td>
                    <td class="px-4 py-3">${renderStatusBadge(normalizedStatus)}</td>
                    <td class="px-4 py-3 text-slate-600">${escapeHtml(notes)}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a
                                href="${escapeHtml(buildActionUrl('edit-pen', pen))}"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 transition hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-1"
                                aria-label="Edit ${escapeHtml(pen.pen_name ?? '')}"
                                title="Edit pen"
                            >
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.6 3.7a1.5 1.5 0 1 1 2.1 2.1L8.2 13.3l-3 .9.9-3z" />
                                </svg>
                            </a>
                            <a
                                href="${escapeHtml(buildActionUrl('delete-pen', pen))}"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:ring-offset-1"
                                aria-label="Delete ${escapeHtml(pen.pen_name ?? '')}"
                                title="Delete pen"
                            >
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.8 5.8h12.4M7.2 5.8V4.7a1 1 0 0 1 1-1h3.6a1 1 0 0 1 1 1v1.1m-7.5 0V15a1.2 1.2 0 0 0 1.2 1.2h7a1.2 1.2 0 0 0 1.2-1.2V5.8M8.4 8.4v4.8m3.2-4.8v4.8" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        };

        const renderMessageRow = function (message) {
            tableBody.innerHTML = `
                <tr data-pen-empty="1">
                    <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">${escapeHtml(message)}</td>
                </tr>
            `;
            const activeNoResultsRow = document.getElementById('pen-no-results-row');
            if (activeNoResultsRow) {
                activeNoResultsRow.classList.add('hidden');
            }
        };

        const renderPens = function (pens) {
            if (!Array.isArray(pens) || pens.length === 0) {
                renderMessageRow('No pen records yet.');
                return;
            }

            tableBody.innerHTML = pens.map(buildRowMarkup).join('') + '<tr id="pen-no-results-row" class="hidden"><td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">No matching pen records found.</td></tr>';

            const refreshedNoResultsRow = document.getElementById('pen-no-results-row');
            if (refreshedNoResultsRow) {
                refreshedNoResultsRow.classList.add('hidden');
            }

            applyFilters();
        };

        const readCachedPens = function () {
            try {
                const cached = sessionStorage.getItem(pensCacheKey);
                if (!cached) {
                    return null;
                }

                const parsed = JSON.parse(cached);
                return Array.isArray(parsed) ? parsed : null;
            } catch (error) {
                return null;
            }
        };

        const writeCachedPens = function (pens) {
            try {
                sessionStorage.setItem(pensCacheKey, JSON.stringify(pens));
            } catch (error) {
                // Ignore cache write failures.
            }
        };

        const applyFilters = function () {
            const term = searchInput.value.trim().toLowerCase();
            const selectedStatus = statusFilter.value.trim().toLowerCase();
            const rows = Array.from(tableBody.querySelectorAll('tr[data-pen-row="1"]'));
            let visibleCount = 0;

            rows.forEach(function (row) {
                const haystack = row.getAttribute('data-search') || '';
                const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
                const matchesSearch = term === '' || haystack.includes(term);
                const matchesStatus = selectedStatus === '' || rowStatus === selectedStatus;
                const isVisible = matchesSearch && matchesStatus;

                row.classList.toggle('hidden', !isVisible);
                if (isVisible) {
                    visibleCount += 1;
                }
            });

            const activeNoResultsRow = document.getElementById('pen-no-results-row');
            if (activeNoResultsRow) {
                activeNoResultsRow.classList.toggle('hidden', rows.length === 0 || visibleCount > 0);
            }
        };

        searchInput.addEventListener('input', applyFilters);
        statusFilter.addEventListener('change', applyFilters);

        const cachedPens = readCachedPens();
        if (Array.isArray(cachedPens) && cachedPens.length > 0) {
            renderPens(cachedPens);
        }

        fetch(pensApiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    return {
                        ok: response.ok,
                        payload: payload,
                    };
                });
            })
            .then(function (result) {
                const pens = Array.isArray(result.payload?.data) ? result.payload.data : [];
                renderPens(pens);
                if (pens.length > 0) {
                    writeCachedPens(pens);
                }
            })
            .catch(function () {
                renderMessageRow('Unable to load pen records right now. Please refresh this page.');
            });
    });
</script>
