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
        <table class="min-w-[980px] w-full text-left text-sm">
            <thead class="sticky top-0 bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-semibold text-nowrap">Batch ID</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Date Registered</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Current Age</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Growth Stage</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Avg. Weight</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Feeding Status</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Health Alerts</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse (($pigBatchCards ?? collect()) as $batch)
                    <tr class="align-middle odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
                        <td class="px-4 py-3 font-semibold text-slate-800 text-nowrap">{{ $batch['id'] }}</td>
                        <td class="px-4 py-3 text-slate-600 text-nowrap">{{ $batch['date'] }}</td>
                        <td class="px-4 py-3 text-slate-600 text-nowrap">{{ $batch['age'] }}</td>
                        <td class="px-4 py-3 text-slate-600 text-nowrap">{{ $batch['stage'] }}</td>
                        <td class="px-4 py-3 text-slate-600 text-nowrap">{{ $batch['weight'] }}</td>
                        <td class="px-4 py-3">
                            @if ($batch['feeding'] === 'Completed')
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ $batch['feeding'] }}</span>
                            @elseif ($batch['feeding'] === 'Upcoming')
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">{{ $batch['feeding'] }}</span>
                            @else
                                <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">{{ $batch['feeding'] }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-nowrap">
                            <span class="text-xs font-medium {{ $batch['alerts'] === 'None' ? 'text-emerald-700' : 'text-amber-700' }}">{{ $batch['alerts'] }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1.5 min-w-[280px]">
                                <button type="button" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50">View</button>
                                <button type="button" class="rounded-md border border-emerald-200 px-2 py-1 text-xs font-medium text-emerald-700 hover:bg-emerald-50">Record Weight</button>
                                <button type="button" class="rounded-md border border-amber-200 px-2 py-1 text-xs font-medium text-amber-800 hover:bg-amber-50">Edit</button>
                                <button type="button" class="rounded-md border border-rose-200 px-2 py-1 text-xs font-medium text-rose-700 hover:bg-rose-50">Archive</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">No pig batch records yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</article>
