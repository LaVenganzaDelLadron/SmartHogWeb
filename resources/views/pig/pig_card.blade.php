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
