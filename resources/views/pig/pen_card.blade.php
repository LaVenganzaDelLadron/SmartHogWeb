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
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse (($penCards ?? collect()) as $penCard)
                    <tr
                        data-pen-row="1"
                        data-status="{{ strtolower($penCard['status']) }}"
                        data-search="{{ strtolower($penCard['pen_id'].' '.$penCard['pen_name'].' '.$penCard['notes']) }}"
                        class="align-middle odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80"
                    >
                        <td class="px-4 py-3 font-semibold text-slate-800 text-nowrap">{{ $penCard['pen_id'] }}</td>
                        <td class="px-4 py-3 text-slate-700 text-nowrap">{{ $penCard['pen_name'] }}</td>
                        <td class="px-4 py-3 text-slate-600 text-nowrap">{{ number_format($penCard['capacity']) }}</td>
                        <td class="px-4 py-3">
                            @if ($penCard['status'] === 'Available')
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ $penCard['status'] }}</span>
                            @elseif ($penCard['status'] === 'Occupied')
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">{{ $penCard['status'] }}</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $penCard['status'] }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $penCard['notes'] }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('show.pig', ['modal' => 'edit-pen', 'pen_id' => $penCard['pen_id'], 'pen_name' => $penCard['pen_name'], 'capacity' => $penCard['capacity'], 'status' => strtolower($penCard['status']), 'notes' => $penCard['notes']]) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 transition hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-1"
                                    aria-label="Edit {{ $penCard['pen_name'] }}"
                                    title="Edit pen"
                                >
                                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.6 3.7a1.5 1.5 0 1 1 2.1 2.1L8.2 13.3l-3 .9.9-3z" />
                                    </svg>
                                </a>

                                <a
                                    href="{{ route('show.pig', ['modal' => 'delete-pen', 'pen_id' => $penCard['pen_id'], 'pen_name' => $penCard['pen_name'], 'capacity' => $penCard['capacity']]) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:ring-offset-1"
                                    aria-label="Delete {{ $penCard['pen_name'] }}"
                                    title="Delete pen"
                                >
                                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.8 5.8h12.4M7.2 5.8V4.7a1 1 0 0 1 1-1h3.6a1 1 0 0 1 1 1v1.1m-7.5 0V15a1.2 1.2 0 0 0 1.2 1.2h7a1.2 1.2 0 0 0 1.2-1.2V5.8M8.4 8.4v4.8m3.2-4.8v4.8" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-pen-empty="1">
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">No pen records yet.</td>
                    </tr>
                @endforelse
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
        const table = document.getElementById('pen-records-table');

        if (!searchInput || !statusFilter || !table || searchInput.dataset.bound === '1') {
            return;
        }

        searchInput.dataset.bound = '1';

        const body = table.querySelector('tbody');
        if (!body) {
            return;
        }

        const rows = Array.from(body.querySelectorAll('tr[data-pen-row="1"]'));
        const emptyRow = body.querySelector('tr[data-pen-empty="1"]');
        const noResultsRow = document.getElementById('pen-no-results-row');

        const applyFilters = function () {
            const term = searchInput.value.trim().toLowerCase();
            const selectedStatus = statusFilter.value.trim().toLowerCase();
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

            if (emptyRow) {
                emptyRow.classList.add('hidden');
            }

            if (noResultsRow) {
                noResultsRow.classList.toggle('hidden', rows.length === 0 || visibleCount > 0);
            }
        };

        searchInput.addEventListener('input', applyFilters);
        statusFilter.addEventListener('change', applyFilters);
    });
</script>
