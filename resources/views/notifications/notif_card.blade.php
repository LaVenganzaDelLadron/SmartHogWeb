@php
    $eventIcons = [
        'approved' => ['M4 10 8 14 16 6'],
        'rejected' => ['M6 6l8 8', 'M14 6l-8 8'],
        'completed' => ['M4 10 8 14 16 6'],
        'delayed' => ['M10 5v5l3 2'],
        'system alert' => ['M10 3.5 16.5 15h-13z', 'M10 7.6v3.5', 'M10 13.5h.01'],
        'warning' => ['M10 3.5 16.5 15h-13z', 'M10 7.6v3.5', 'M10 13.5h.01'],
    ];
@endphp

@forelse ($notifications as $notification)
    @php
        $id = data_get($notification, 'id');
        $title = (string) data_get($notification, 'title', '');
        $event = strtolower($title);
        $status = strtolower((string) data_get($notification, 'status', 'read'));
        $rawType = (string) data_get($notification, 'type', 'system');
        $type = strtolower(str_replace(['-', '_'], ' ', $rawType));

        $isPositive = in_array($event, ['approved', 'completed'], true);
        $isNegative = in_array($event, ['rejected', 'system alert'], true);

        $iconWrapClass = $isPositive
            ? 'bg-emerald-100 text-emerald-700'
            : ($isNegative ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-800');

        $statusClass = $status === 'new'
            ? 'bg-cyan-100 text-cyan-700'
            : 'bg-slate-200 text-slate-600';

        $cardStateClass = $status === 'new'
            ? 'border-cyan-200 bg-cyan-50/40'
            : 'border-slate-200 bg-slate-50/40 opacity-90';

        $iconPaths = $eventIcons[$event] ?? ['M10 3.5 16.5 15h-13z'];

        $formattedTime = data_get($notification, 'recorded_date', '');
        try {
            $formattedTime = \Illuminate\Support\Carbon::parse((string) data_get($notification, 'recorded_date', ''))
                ->format('M d, Y · h:i A');
        } catch (\Throwable $th) {
            $formattedTime = (string) data_get($notification, 'recorded_date', '');
        }
    @endphp

    <article class="notification-item relative rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-md {{ $cardStateClass }}" data-id="{{ $id }}" data-type="{{ $rawType }}" data-status="{{ $status }}">
        <div class="absolute bottom-0 left-6 top-0 hidden w-px bg-slate-200/70 md:block"></div>
        <div class="relative flex flex-wrap items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $iconWrapClass }}">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        @foreach ($iconPaths as $path)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
                        @endforeach
                    </svg>
                </span>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-sm font-semibold text-slate-900">{{ \Illuminate\Support\Str::title($title) }}</p>
                        <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $statusClass }}">{{ \Illuminate\Support\Str::title($status) }}</span>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600">{{ \Illuminate\Support\Str::title($type) }}</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-700">{{ data_get($notification, 'description', '') }}</p>
                    <p class="mt-2 text-xs text-slate-500">{{ $formattedTime }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-1.5">
                <button type="button" class="rounded-md border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-white">View Details</button>
                <button type="button" class="rounded-md border border-cyan-200 px-2.5 py-1 text-xs font-medium text-cyan-700 hover:bg-cyan-50 {{ $status === 'read' ? 'opacity-60 cursor-not-allowed' : '' }}" {{ $status === 'read' ? 'disabled' : '' }}>Acknowledge</button>
                <button type="button" class="rounded-md border border-emerald-200 px-2.5 py-1 text-xs font-medium text-emerald-700 hover:bg-emerald-50">Resolve</button>
            </div>
        </div>
    </article>
@empty
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
        No notifications yet.
    </div>
@endforelse
