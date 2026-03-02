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
