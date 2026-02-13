<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Monitor & Analytics | {{ config('app.name', 'SMART-HOG') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-[radial-gradient(70rem_45rem_at_0%_-10%,rgba(6,95,70,0.14),transparent),radial-gradient(60rem_40rem_at_100%_0%,rgba(59,130,246,0.1),transparent),#f3f6f8] text-slate-800 antialiased">
        @include('layouts.sidebar', ['deviceOnline' => true])

        <main class="min-h-screen px-4 pb-10 pt-20 lg:ml-80 lg:px-8 lg:pt-8">
            <div class="mx-auto max-w-7xl space-y-6">
                <section class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">SMART-HOG Intelligence</p>
                            <h1 class="mt-2 text-2xl font-semibold text-slate-900 sm:text-3xl">Monitor & Analytics</h1>
                            <p class="mt-2 max-w-3xl text-sm text-slate-600">Real-time feeder visibility and performance analytics for fast, informed farm decisions.</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-right">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Last Update</p>
                            <p class="mt-1 text-sm font-medium text-slate-900">{{ now()->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </section>

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                    <article class="rounded-2xl border border-emerald-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Active Feeders</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 14.5h11M6.5 14.5V7.5m7 7V6.5M9.5 14.5V9.5"/></svg>
                            </span>
                        </div>
                        <p class="mt-3 text-2xl font-semibold text-slate-900">5 / 6</p>
                        <p class="mt-1 text-xs text-emerald-700">1 standby unit</p>
                    </article>

                    <article class="rounded-2xl border border-cyan-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Pigs Feeding</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-cyan-100 text-cyan-700">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.5 10h13M3.5 6h13M3.5 14h8"/></svg>
                            </span>
                        </div>
                        <p class="mt-3 text-2xl font-semibold text-slate-900">87</p>
                        <p class="mt-1 text-xs text-cyan-700">Across 3 active pens</p>
                    </article>

                    <article class="rounded-2xl border border-amber-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Feed Level</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-800">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15.5h10v-11H5zM5 10h10"/></svg>
                            </span>
                        </div>
                        <p class="mt-3 text-2xl font-semibold text-slate-900">74%</p>
                        <p class="mt-1 text-xs text-amber-800">Silo refill in ~2 days</p>
                    </article>

                    <article class="rounded-2xl border border-emerald-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">System Status</p>
                            <span class="relative inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                <span class="absolute inline-flex h-3 w-3 animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-600"></span>
                            </span>
                        </div>
                        <p class="mt-3 text-2xl font-semibold text-slate-900">Online</p>
                        <p class="mt-1 text-xs text-emerald-700">All core services healthy</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Latency</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="10" cy="10" r="6.5"/><path stroke-linecap="round" d="M10 7v3.2l2.2 1.4"/></svg>
                            </span>
                        </div>
                        <p class="mt-3 text-2xl font-semibold text-slate-900">132 ms</p>
                        <p class="mt-1 text-xs text-slate-600">Last telemetry roundtrip</p>
                    </article>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Analytics Overview</h2>
                            <p class="text-sm text-slate-600">Feed trends and distribution by date, group, and feed type.</p>
                        </div>
                        <div class="grid gap-2 sm:grid-cols-3">
                            <select class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                <option>Last 7 Days</option>
                                <option>Last 30 Days</option>
                                <option>This Quarter</option>
                            </select>
                            <select class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                <option>All Pig Groups</option>
                                <option>Group A</option>
                                <option>Group B</option>
                                <option>Group C</option>
                            </select>
                            <select class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                <option>All Feed Types</option>
                                <option>Starter</option>
                                <option>Grower</option>
                                <option>Finisher</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-6 xl:grid-cols-3">
                        <article class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 xl:col-span-2">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-slate-900">Daily & Weekly Feed Consumption</h3>
                                <span class="text-xs text-slate-500">kg/day</span>
                            </div>
                            <svg viewBox="0 0 760 260" class="mt-3 h-64 w-full" role="img" aria-label="Consumption line chart">
                                <g stroke="#e2e8f0" stroke-width="1">
                                    <line x1="46" y1="30" x2="730" y2="30"/><line x1="46" y1="76" x2="730" y2="76"/><line x1="46" y1="122" x2="730" y2="122"/><line x1="46" y1="168" x2="730" y2="168"/><line x1="46" y1="214" x2="730" y2="214"/>
                                </g>
                                <polyline points="50,176 136,164 222,155 308,141 394,130 480,121 566,108 652,98 726,90" fill="none" stroke="#065f46" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <polyline points="50,188 136,181 222,172 308,166 394,154 480,144 566,136 652,124 726,116" fill="none" stroke="#3b82f6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.85"/>
                                <g fill="#065f46">
                                    <circle cx="50" cy="176" r="4"/><circle cx="136" cy="164" r="4"/><circle cx="222" cy="155" r="4"/><circle cx="308" cy="141" r="4"/><circle cx="394" cy="130" r="4"/><circle cx="480" cy="121" r="4"/><circle cx="566" cy="108" r="4"/><circle cx="652" cy="98" r="4"/><circle cx="726" cy="90" r="4"/>
                                </g>
                                <g fill="#64748b" font-size="12">
                                    <text x="42" y="244">Mon</text><text x="128" y="244">Tue</text><text x="214" y="244">Wed</text><text x="300" y="244">Thu</text><text x="386" y="244">Fri</text><text x="472" y="244">Sat</text><text x="558" y="244">Sun</text><text x="644" y="244">Wk+1</text>
                                </g>
                            </svg>
                        </article>

                        <article class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                            <h3 class="text-sm font-semibold text-slate-900">Feed Distribution & Wastage</h3>
                            <div class="mt-4 flex items-center justify-center">
                                <svg viewBox="0 0 220 220" class="h-56 w-56" role="img" aria-label="Feed distribution donut chart">
                                    <circle cx="110" cy="110" r="72" fill="none" stroke="#dbeafe" stroke-width="30"></circle>
                                    <circle cx="110" cy="110" r="72" fill="none" stroke="#10b981" stroke-width="30" stroke-dasharray="300 152" transform="rotate(-90 110 110)"></circle>
                                    <circle cx="110" cy="110" r="72" fill="none" stroke="#f59e0b" stroke-width="30" stroke-dasharray="70 382" stroke-dashoffset="-300" transform="rotate(-90 110 110)"></circle>
                                    <text x="110" y="106" text-anchor="middle" class="fill-slate-800" font-size="20" font-weight="700">82%</text>
                                    <text x="110" y="126" text-anchor="middle" class="fill-slate-500" font-size="11">Used</text>
                                </svg>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <p class="rounded-lg bg-emerald-50 px-2 py-1 font-medium text-emerald-700">Feed Used: 82%</p>
                                <p class="rounded-lg bg-amber-50 px-2 py-1 font-medium text-amber-800">Wastage: 18%</p>
                            </div>
                        </article>
                    </div>

                    <article class="mt-6 rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-slate-900">Feed Usage by Pen (Bar Comparison)</h3>
                            <span class="text-xs text-slate-500">kg/week</span>
                        </div>
                        @php
                            $penBars = [
                                ['pen' => 'Pen A', 'value' => 88],
                                ['pen' => 'Pen B', 'value' => 94],
                                ['pen' => 'Pen C', 'value' => 74],
                                ['pen' => 'Pen D', 'value' => 62],
                                ['pen' => 'Pen E', 'value' => 79],
                            ];
                        @endphp
                        <div class="mt-4 flex h-44 items-end gap-3">
                            @foreach ($penBars as $bar)
                                <div class="flex flex-1 flex-col items-center gap-2">
                                    <div class="w-full rounded-t-md bg-gradient-to-t from-cyan-700 to-cyan-500" style="height: {{ $bar['value'] }}%"></div>
                                    <p class="text-xs font-medium text-slate-600">{{ $bar['pen'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </article>
                </section>

                <section class="grid gap-6 xl:grid-cols-3">
                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-1 sm:p-6">
                        <h2 class="text-lg font-semibold text-slate-900">Performance Insights</h2>
                        <p class="mt-1 text-sm text-slate-600">Critical trends and actionable alerts.</p>
                        <ul class="mt-4 space-y-3">
                            <li class="rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                                <p class="text-sm font-semibold text-emerald-900">Normal Growth Pattern</p>
                                <p class="mt-1 text-xs text-emerald-700">Group A feeding intervals are consistent and within expected intake.</p>
                            </li>
                            <li class="rounded-xl border border-amber-200 bg-amber-50 p-3">
                                <p class="text-sm font-semibold text-amber-900">Underfeeding Warning</p>
                                <p class="mt-1 text-xs text-amber-800">Pen D consumed 14% below weekly baseline across 2 cycles.</p>
                            </li>
                            <li class="rounded-xl border border-rose-200 bg-rose-50 p-3">
                                <p class="text-sm font-semibold text-rose-900">System Anomaly</p>
                                <p class="mt-1 text-xs text-rose-700">Feeder C reported delayed response and missed one schedule event.</p>
                            </li>
                        </ul>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2 sm:p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Reports & History</h2>
                                <p class="text-sm text-slate-600">View, sort, search, and export feeding performance records.</p>
                            </div>
                            <button type="button" class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700 transition hover:bg-emerald-100">Export CSV</button>
                        </div>

                        <div class="mt-4 grid gap-2 sm:grid-cols-3">
                            <input id="report-search" type="text" placeholder="Search by report ID or group" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <select id="report-filter" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                <option value="all">All Status</option>
                                <option value="normal">Normal</option>
                                <option value="warning">Warning</option>
                                <option value="critical">Critical</option>
                            </select>
                            <select id="report-sort" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                <option value="latest">Sort: Latest First</option>
                                <option value="oldest">Sort: Oldest First</option>
                            </select>
                        </div>

                        @php
                            $reports = [
                                ['id' => 'RPT-2107', 'date' => '2026-02-12', 'group' => 'Group A', 'consumed' => '312 kg', 'efficiency' => '91%', 'status' => 'normal'],
                                ['id' => 'RPT-2106', 'date' => '2026-02-11', 'group' => 'Group C', 'consumed' => '284 kg', 'efficiency' => '84%', 'status' => 'warning'],
                                ['id' => 'RPT-2105', 'date' => '2026-02-10', 'group' => 'Group B', 'consumed' => '328 kg', 'efficiency' => '79%', 'status' => 'critical'],
                                ['id' => 'RPT-2104', 'date' => '2026-02-09', 'group' => 'Group D', 'consumed' => '295 kg', 'efficiency' => '88%', 'status' => 'normal'],
                            ];
                        @endphp

                        <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="min-w-[760px] w-full text-left text-sm" id="reports-table">
                                <thead class="bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">Report ID</th>
                                        <th class="px-4 py-3 font-semibold">Date</th>
                                        <th class="px-4 py-3 font-semibold">Pig Group</th>
                                        <th class="px-4 py-3 font-semibold">Feed Consumed</th>
                                        <th class="px-4 py-3 font-semibold">Efficiency</th>
                                        <th class="px-4 py-3 font-semibold">Status</th>
                                        <th class="px-4 py-3 font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white" id="reports-body">
                                    @foreach ($reports as $report)
                                        <tr data-status="{{ $report['status'] }}" data-date="{{ $report['date'] }}" data-search="{{ strtolower($report['id'].' '.$report['group']) }}" class="odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
                                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $report['id'] }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $report['date'] }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $report['group'] }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $report['consumed'] }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $report['efficiency'] }}</td>
                                            <td class="px-4 py-3">
                                                @if ($report['status'] === 'normal')
                                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Normal</span>
                                                @elseif ($report['status'] === 'warning')
                                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Warning</span>
                                                @else
                                                    <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">Critical</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3"><button class="rounded-md border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50">View</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                </section>
            </div>
        </main>

        <script>
            const searchInput = document.getElementById('report-search');
            const filterSelect = document.getElementById('report-filter');
            const sortSelect = document.getElementById('report-sort');
            const tbody = document.getElementById('reports-body');

            function updateReportsTable() {
                if (!tbody) return;

                const query = (searchInput?.value || '').trim().toLowerCase();
                const filter = filterSelect?.value || 'all';
                const sort = sortSelect?.value || 'latest';

                const rows = Array.from(tbody.querySelectorAll('tr'));

                rows.forEach((row) => {
                    const rowStatus = row.dataset.status || '';
                    const searchText = row.dataset.search || '';
                    const passFilter = filter === 'all' || rowStatus === filter;
                    const passSearch = !query || searchText.includes(query);
                    row.style.display = passFilter && passSearch ? '' : 'none';
                });

                const visibleRows = rows.filter((row) => row.style.display !== 'none');
                visibleRows.sort((a, b) => {
                    const aDate = new Date(a.dataset.date || '');
                    const bDate = new Date(b.dataset.date || '');
                    return sort === 'oldest' ? aDate - bDate : bDate - aDate;
                });
                visibleRows.forEach((row) => tbody.appendChild(row));
            }

            [searchInput, filterSelect, sortSelect].forEach((el) => {
                el?.addEventListener('input', updateReportsTable);
                el?.addEventListener('change', updateReportsTable);
            });
        </script>
    </body>
</html>
