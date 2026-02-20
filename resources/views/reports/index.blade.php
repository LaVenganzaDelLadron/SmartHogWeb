<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Reports | {{ config('app.name', 'SMART-HOG') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-[radial-gradient(65rem_40rem_at_0%_-10%,rgba(16,185,129,0.12),transparent),radial-gradient(55rem_35rem_at_100%_0%,rgba(2,132,199,0.1),transparent),#f3f7f2] text-slate-800 antialiased">
        @include('layouts.sidebar', ['deviceOnline' => true])

        <main class="min-h-screen px-4 pb-10 pt-20 lg:ml-80 lg:px-8 lg:pt-8">
            <div class="mx-auto max-w-7xl space-y-6">
                <section class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">SMART-HOG Insights</p>
                            <h1 class="mt-2 text-2xl font-semibold text-slate-900 sm:text-3xl">Reports</h1>
                            <p class="mt-2 max-w-3xl text-sm text-slate-600">Review feeding history, growth performance, and farm efficiency records in one place.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 3v9m0 0-3-3m3 3 3-3M4 13.5v2h12v-2" />
                                </svg>
                                Export CSV
                            </button>
                            <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-2.5 text-sm font-semibold text-cyan-700 transition hover:bg-cyan-100">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 3.5h8M6 16.5h8M5 6.5h10v7H5z" />
                                </svg>
                                Generate PDF
                            </button>
                        </div>
                    </div>
                </section>

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Reports This Month</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">36</p>
                        <p class="mt-2 text-sm text-slate-600">+8 compared to last month</p>
                    </article>
                    <article class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Average Feed Efficiency</p>
                        <p class="mt-3 text-3xl font-semibold text-emerald-700">89%</p>
                        <p class="mt-2 text-sm text-emerald-700">Within healthy target range</p>
                    </article>
                    <article class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Pending Review</p>
                        <p class="mt-3 text-3xl font-semibold text-amber-800">5</p>
                        <p class="mt-2 text-sm text-amber-800">Require operator validation</p>
                    </article>
                    <article class="rounded-2xl border border-rose-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Critical Flags</p>
                        <p class="mt-3 text-3xl font-semibold text-rose-700">2</p>
                        <p class="mt-2 text-sm text-rose-700">Overfeeding and delayed cycles</p>
                    </article>
                </section>

                <section class="grid gap-6 xl:grid-cols-3">
                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2 sm:p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Feed Consumption Trend</h2>
                                <p class="text-sm text-slate-600">Weekly total feed usage for report snapshots.</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Last 8 Weeks</span>
                        </div>
                        <svg viewBox="0 0 760 250" class="mt-4 h-60 w-full rounded-2xl border border-slate-100 bg-slate-50/70 p-3" role="img" aria-label="Feed consumption trend chart">
                            <g stroke="#e2e8f0" stroke-width="1">
                                <line x1="44" y1="28" x2="730" y2="28" />
                                <line x1="44" y1="70" x2="730" y2="70" />
                                <line x1="44" y1="112" x2="730" y2="112" />
                                <line x1="44" y1="154" x2="730" y2="154" />
                                <line x1="44" y1="196" x2="730" y2="196" />
                            </g>
                            <polyline points="50,170 140,160 230,150 320,137 410,128 500,118 590,124 680,110 726,104" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            <g fill="#047857">
                                <circle cx="50" cy="170" r="3.5" />
                                <circle cx="140" cy="160" r="3.5" />
                                <circle cx="230" cy="150" r="3.5" />
                                <circle cx="320" cy="137" r="3.5" />
                                <circle cx="410" cy="128" r="3.5" />
                                <circle cx="500" cy="118" r="3.5" />
                                <circle cx="590" cy="124" r="3.5" />
                                <circle cx="680" cy="110" r="3.5" />
                            </g>
                            <g fill="#64748b" font-size="12">
                                <text x="42" y="226">W1</text>
                                <text x="132" y="226">W2</text>
                                <text x="222" y="226">W3</text>
                                <text x="312" y="226">W4</text>
                                <text x="402" y="226">W5</text>
                                <text x="492" y="226">W6</text>
                                <text x="582" y="226">W7</text>
                                <text x="672" y="226">W8</text>
                            </g>
                        </svg>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <h2 class="text-lg font-semibold text-slate-900">Report Distribution</h2>
                        <p class="mt-1 text-sm text-slate-600">By category this month.</p>
                        <div class="mt-5 space-y-3">
                            <div>
                                <div class="mb-1 flex items-center justify-between text-xs font-medium text-slate-600">
                                    <span>Feeding</span>
                                    <span>46%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100"><div class="h-2 w-[46%] rounded-full bg-emerald-500"></div></div>
                            </div>
                            <div>
                                <div class="mb-1 flex items-center justify-between text-xs font-medium text-slate-600">
                                    <span>Growth</span>
                                    <span>31%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100"><div class="h-2 w-[31%] rounded-full bg-cyan-500"></div></div>
                            </div>
                            <div>
                                <div class="mb-1 flex items-center justify-between text-xs font-medium text-slate-600">
                                    <span>Health</span>
                                    <span>17%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100"><div class="h-2 w-[17%] rounded-full bg-amber-500"></div></div>
                            </div>
                            <div>
                                <div class="mb-1 flex items-center justify-between text-xs font-medium text-slate-600">
                                    <span>System</span>
                                    <span>6%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100"><div class="h-2 w-[6%] rounded-full bg-rose-500"></div></div>
                            </div>
                        </div>
                    </article>
                </section>

                @php
                    $reports = [
                        ['id' => 'REP-2401', 'type' => 'Feeding', 'group' => 'Batch A', 'date' => '2026-02-19', 'feed' => '298 kg', 'status' => 'normal'],
                        ['id' => 'REP-2402', 'type' => 'Growth', 'group' => 'Batch C', 'date' => '2026-02-18', 'feed' => '281 kg', 'status' => 'review'],
                        ['id' => 'REP-2403', 'type' => 'Health', 'group' => 'Batch B', 'date' => '2026-02-17', 'feed' => '306 kg', 'status' => 'critical'],
                        ['id' => 'REP-2404', 'type' => 'Feeding', 'group' => 'Batch D', 'date' => '2026-02-16', 'feed' => '272 kg', 'status' => 'normal'],
                        ['id' => 'REP-2405', 'type' => 'System', 'group' => 'All Pens', 'date' => '2026-02-15', 'feed' => '--', 'status' => 'review'],
                    ];
                @endphp

                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Reports History</h2>
                            <p class="text-sm text-slate-600">Search, filter, and open farm reports quickly.</p>
                        </div>
                        <p class="text-xs font-medium uppercase tracking-[0.14em] text-slate-500">{{ now()->format('F d, Y') }}</p>
                    </div>

                    <div class="mt-4 grid gap-2 sm:grid-cols-4">
                        <input id="reports-search" type="text" placeholder="Search report ID, batch, type" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 sm:col-span-2">
                        <select id="reports-type" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="all">All Types</option>
                            <option value="feeding">Feeding</option>
                            <option value="growth">Growth</option>
                            <option value="health">Health</option>
                            <option value="system">System</option>
                        </select>
                        <select id="reports-sort" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="latest">Sort: Latest First</option>
                            <option value="oldest">Sort: Oldest First</option>
                        </select>
                    </div>

                    <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="min-w-[780px] w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">Report ID</th>
                                    <th class="px-4 py-3 font-semibold">Type</th>
                                    <th class="px-4 py-3 font-semibold">Batch / Pen</th>
                                    <th class="px-4 py-3 font-semibold">Date</th>
                                    <th class="px-4 py-3 font-semibold">Feed Total</th>
                                    <th class="px-4 py-3 font-semibold">Status</th>
                                    <th class="px-4 py-3 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody id="reports-body" class="divide-y divide-slate-100 bg-white">
                                @foreach ($reports as $report)
                                    <tr data-type="{{ strtolower($report['type']) }}" data-date="{{ $report['date'] }}" data-search="{{ strtolower($report['id'] . ' ' . $report['type'] . ' ' . $report['group']) }}" class="odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
                                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $report['id'] }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $report['type'] }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $report['group'] }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $report['date'] }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $report['feed'] }}</td>
                                        <td class="px-4 py-3">
                                            @if ($report['status'] === 'normal')
                                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Normal</span>
                                            @elseif ($report['status'] === 'review')
                                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Needs Review</span>
                                            @else
                                                <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">Critical</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <button type="button" class="rounded-md border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 transition hover:bg-slate-50">View Details</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>

        <script>
            const reportsSearch = document.getElementById('reports-search');
            const reportsType = document.getElementById('reports-type');
            const reportsSort = document.getElementById('reports-sort');
            const reportsBody = document.getElementById('reports-body');

            function updateReportsRows() {
                if (!reportsBody) {
                    return;
                }

                const query = (reportsSearch?.value || '').trim().toLowerCase();
                const type = reportsType?.value || 'all';
                const sort = reportsSort?.value || 'latest';
                const rows = Array.from(reportsBody.querySelectorAll('tr'));

                rows.forEach((row) => {
                    const rowType = row.dataset.type || '';
                    const rowSearch = row.dataset.search || '';
                    const passType = type === 'all' || rowType === type;
                    const passSearch = !query || rowSearch.includes(query);
                    row.style.display = passType && passSearch ? '' : 'none';
                });

                const visibleRows = rows.filter((row) => row.style.display !== 'none');
                visibleRows.sort((a, b) => {
                    const aDate = new Date(a.dataset.date || '');
                    const bDate = new Date(b.dataset.date || '');
                    return sort === 'oldest' ? aDate - bDate : bDate - aDate;
                });

                visibleRows.forEach((row) => {
                    reportsBody.appendChild(row);
                });
            }

            [reportsSearch, reportsType, reportsSort].forEach((element) => {
                element?.addEventListener('input', updateReportsRows);
                element?.addEventListener('change', updateReportsRows);
            });
        </script>
    </body>
</html>
