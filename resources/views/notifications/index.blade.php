<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Notifications | {{ config('app.name', 'SMART-HOG') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-[radial-gradient(70rem_45rem_at_0%_-10%,rgba(16,185,129,0.12),transparent),radial-gradient(60rem_40rem_at_100%_0%,rgba(59,130,246,0.09),transparent),#f5f7f8] text-slate-800 antialiased">
        @include('layouts.sidebar', ['deviceOnline' => true, 'newNotificationsCount' => 5])

        <main class="min-h-screen px-4 pb-10 pt-20 lg:ml-80 lg:px-8 lg:pt-8">
            <div class="mx-auto max-w-7xl space-y-6">
                <section class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">SMART-HOG Alerts Center</p>
                            <h1 class="mt-2 text-2xl font-semibold text-slate-900 sm:text-3xl">Notifications</h1>
                            <p class="mt-2 max-w-3xl text-sm text-slate-600">Stay updated on feeding results, system events, health reminders, and admin decisions in one calm, readable view.</p>
                        </div>
                        <button id="mark-all-read" type="button" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">Mark all as read</button>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="grid gap-3 md:grid-cols-4">
                        <select id="filter-type" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="all">Type: All</option>
                            <option value="feeding">Feeding</option>
                            <option value="system">System</option>
                            <option value="pig-health">Pig Health</option>
                            <option value="admin">Admin</option>
                        </select>
                        <select id="filter-status" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="all">Status: All</option>
                            <option value="new">New</option>
                            <option value="read">Read</option>
                        </select>
                        <input id="filter-date" type="date" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                        <select id="sort-order" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="latest">Sort: Latest First</option>
                            <option value="oldest">Sort: Oldest First</option>
                        </select>
                    </div>
                </section>

                @php
                    $notifications = [
                        ['type' => 'admin', 'event' => 'approved', 'message' => 'Batch registration BATCH-019 was approved by Admin.', 'time' => '2026-02-13 08:42', 'status' => 'new'],
                        ['type' => 'feeding', 'event' => 'completed', 'message' => 'Feeding cycle completed for Pen B (42 kg dispensed).', 'time' => '2026-02-13 07:05', 'status' => 'new'],
                        ['type' => 'feeding', 'event' => 'delayed', 'message' => 'Feeding delay detected in Pen D due to low pressure.', 'time' => '2026-02-13 06:55', 'status' => 'new'],
                        ['type' => 'system', 'event' => 'system alert', 'message' => 'Telemetry heartbeat dropped for Feeder C (auto-recovered).', 'time' => '2026-02-12 19:14', 'status' => 'read'],
                        ['type' => 'pig-health', 'event' => 'warning', 'message' => 'Pig Group C vitamin reminder: due tomorrow at 08:00 AM.', 'time' => '2026-02-12 16:20', 'status' => 'new'],
                        ['type' => 'admin', 'event' => 'rejected', 'message' => 'Schedule update request #SCH-771 was rejected for invalid quantity.', 'time' => '2026-02-12 11:08', 'status' => 'read'],
                    ];

                    $eventIcon = [
                        'approved' => 'M4 10 8 14 16 6',
                        'rejected' => 'M6 6l8 8M14 6l-8 8',
                        'completed' => 'M4 10 8 14 16 6',
                        'delayed' => 'M10 5v5l3 2',
                        'system alert' => 'M10 3.5 16.5 15h-13z M10 7.6v3.5 M10 13.5h.01',
                        'warning' => 'M10 3.5 16.5 15h-13z M10 7.6v3.5 M10 13.5h.01',
                    ];
                @endphp

                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="space-y-4" id="notifications-list">
                        @foreach ($notifications as $index => $n)
                            <article
                                data-type="{{ $n['type'] }}"
                                data-status="{{ $n['status'] }}"
                                data-date="{{ \Carbon\Carbon::parse($n['time'])->format('Y-m-d') }}"
                                data-time="{{ \Carbon\Carbon::parse($n['time'])->timestamp }}"
                                class="notification-item relative rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-md {{ $n['status'] === 'new' ? 'border-cyan-200 bg-cyan-50/40' : 'border-slate-200 bg-slate-50/40 opacity-90' }}"
                            >
                                <div class="absolute bottom-0 left-6 top-0 hidden w-px bg-slate-200/70 md:block {{ $index === count($notifications) - 1 ? 'opacity-0' : '' }}"></div>
                                <div class="relative flex flex-wrap items-start justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        @php
                                            $isPositive = in_array($n['event'], ['approved', 'completed']);
                                            $isNegative = in_array($n['event'], ['rejected', 'system alert']);
                                            $iconWrapClass = $isPositive ? 'bg-emerald-100 text-emerald-700' : ($isNegative ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-800');
                                        @endphp
                                        <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $iconWrapClass }}">
                                            <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                                @if (str_contains($eventIcon[$n['event']], ' '))
                                                    @foreach (explode(' M', trim(str_replace('M', ' M', $eventIcon[$n['event']]))) as $segment)
                                                        @if (trim($segment) !== '')
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M{{ trim($segment) }}" />
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <p class="text-sm font-semibold text-slate-900">{{ ucwords($n['event']) }}</p>
                                                <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $n['status'] === 'new' ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-200 text-slate-600' }}">{{ ucfirst($n['status']) }}</span>
                                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600">{{ ucwords(str_replace('-', ' ', $n['type'])) }}</span>
                                            </div>
                                            <p class="mt-1 text-sm text-slate-700">{{ $n['message'] }}</p>
                                            <p class="mt-2 text-xs text-slate-500">{{ \Carbon\Carbon::parse($n['time'])->format('M d, Y · h:i A') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-1.5">
                                        <button type="button" class="rounded-md border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-white">View Details</button>
                                        <button type="button" class="ack-btn rounded-md border border-cyan-200 px-2.5 py-1 text-xs font-medium text-cyan-700 hover:bg-cyan-50">Acknowledge</button>
                                        <button type="button" class="rounded-md border border-emerald-200 px-2.5 py-1 text-xs font-medium text-emerald-700 hover:bg-emerald-50">Resolve</button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>
        </main>

        <script>
            const filterType = document.getElementById('filter-type');
            const filterStatus = document.getElementById('filter-status');
            const filterDate = document.getElementById('filter-date');
            const sortOrder = document.getElementById('sort-order');
            const markAllRead = document.getElementById('mark-all-read');
            const list = document.getElementById('notifications-list');

            function applyNotificationFilters() {
                if (!list) return;
                const items = Array.from(list.querySelectorAll('.notification-item'));
                const typeVal = filterType?.value || 'all';
                const statusVal = filterStatus?.value || 'all';
                const dateVal = filterDate?.value || '';
                const sortVal = sortOrder?.value || 'latest';

                items.forEach((item) => {
                    const passType = typeVal === 'all' || item.dataset.type === typeVal;
                    const passStatus = statusVal === 'all' || item.dataset.status === statusVal;
                    const passDate = !dateVal || item.dataset.date === dateVal;
                    item.style.display = passType && passStatus && passDate ? '' : 'none';
                });

                const visible = items.filter((item) => item.style.display !== 'none');
                visible.sort((a, b) => {
                    const aTime = Number(a.dataset.time || 0);
                    const bTime = Number(b.dataset.time || 0);
                    return sortVal === 'oldest' ? aTime - bTime : bTime - aTime;
                });
                visible.forEach((item) => list.appendChild(item));
            }

            function setRead(item) {
                if (!item || item.dataset.status === 'read') return;
                item.dataset.status = 'read';
                item.classList.remove('border-cyan-200', 'bg-cyan-50/40');
                item.classList.add('border-slate-200', 'bg-slate-50/40', 'opacity-90');
                const statusChip = item.querySelector('span.bg-cyan-100, span.bg-slate-200');
                if (statusChip) {
                    statusChip.textContent = 'Read';
                    statusChip.className = 'rounded-full px-2 py-0.5 text-[11px] font-semibold bg-slate-200 text-slate-600';
                }
                applyNotificationFilters();
            }

            [filterType, filterStatus, filterDate, sortOrder].forEach((el) => {
                el?.addEventListener('change', applyNotificationFilters);
                el?.addEventListener('input', applyNotificationFilters);
            });

            list?.addEventListener('click', (event) => {
                const target = event.target;
                if (!(target instanceof HTMLElement)) return;
                if (target.classList.contains('ack-btn')) {
                    const card = target.closest('.notification-item');
                    setRead(card);
                }
            });

            markAllRead?.addEventListener('click', () => {
                document.querySelectorAll('.notification-item').forEach((item) => setRead(item));
            });
        </script>
    </body>
</html>
