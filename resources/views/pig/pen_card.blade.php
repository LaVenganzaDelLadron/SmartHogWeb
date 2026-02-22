<article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Pen Records</h2>
            <p class="text-sm text-slate-600">List of all pens with capacity, status, notes, and record date.</p>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200">
        <table class="min-w-[900px] w-full text-left text-sm">
            <thead class="sticky top-0 bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-semibold text-nowrap">Pen ID</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Pen Name</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Capacity</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Status</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Notes</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Record Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse (($penCards ?? collect()) as $penCard)
                    <tr class="align-middle odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
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
                        <td class="px-4 py-3 text-slate-600 text-nowrap">{{ $penCard['record_date'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">No pen records yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</article>
