<article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2 sm:p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Feeding Schedule</h2>
            <p class="text-sm text-slate-600">All saved feeding schedules with batch, pen, type, date, time, quantity, and status.</p>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200">
        <table class="min-w-[960px] w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-semibold">Feeding ID</th>
                    <th class="px-4 py-3 font-semibold">Batch ID</th>
                    <th class="px-4 py-3 font-semibold">Assigned Pen</th>
                    <th class="px-4 py-3 font-semibold">Feeding Type</th>
                    <th class="px-4 py-3 font-semibold">Date</th>
                    <th class="px-4 py-3 font-semibold">Time</th>
                    <th class="px-4 py-3 font-semibold">Quantity</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse (($feedingCards ?? collect()) as $feedingCard)
                    <tr class="odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $feedingCard['feeding_id'] }}</td>
                        <td class="px-4 py-3 text-slate-700">
                            <p class="font-medium text-slate-900">{{ $feedingCard['batch_id'] }}</p>
                            @if (! empty($feedingCard['batch_name']))
                                <p class="text-xs text-slate-500">{{ $feedingCard['batch_name'] }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $feedingCard['pen_id'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $feedingCard['feeding_type'] }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $feedingCard['feeding_date'] }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $feedingCard['feeding_time'] }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $feedingCard['feeding_quantity_kg'] }}</td>
                        <td class="px-4 py-3">
                            @if ($feedingCard['status'] === 'Completed')
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Completed</span>
                            @elseif ($feedingCard['status'] === 'Pending')
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Pending</span>
                            @else
                                <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">Delayed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-sm text-slate-500">No feeding schedules found. Add a schedule to populate this card.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</article>
