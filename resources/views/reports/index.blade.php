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
    <body class="min-h-screen bg-[radial-gradient(68rem_44rem_at_0%_-10%,rgba(16,185,129,0.14),transparent),radial-gradient(60rem_42rem_at_100%_0%,rgba(8,145,178,0.12),transparent),#f2f7f3] text-slate-800 antialiased">
        @include('layouts.sidebar', ['deviceOnline' => true])

        @php
            $reportSummary = [
                ['label' => 'Reports Generated', 'value' => '128', 'meta' => '+12 this week', 'tone' => 'emerald'],
                ['label' => 'Scheduled Exports', 'value' => '7', 'meta' => '3 pending today', 'tone' => 'cyan'],
                ['label' => 'Critical Findings', 'value' => '4', 'meta' => 'Needs review', 'tone' => 'rose'],
                ['label' => 'Average Efficiency', 'value' => '89%', 'meta' => 'Across all groups', 'tone' => 'amber'],
            ];

            $categories = [
                ['name' => 'Feeding Performance', 'description' => 'Daily and weekly intake trends per pen and group.', 'count' => 48, 'tone' => 'emerald'],
                ['name' => 'Growth & Weight', 'description' => 'Weight gain progression and deviation summaries.', 'count' => 36, 'tone' => 'cyan'],
                ['name' => 'Health & Alerts', 'description' => 'Incident logs, anomalies, and alert outcomes.', 'count' => 21, 'tone' => 'rose'],
                ['name' => 'Inventory & Feed Cost', 'description' => 'Stock usage, wastage, and cost distribution.', 'count' => 23, 'tone' => 'amber'],
            ];

            $reports = [
                ['id' => 'RPT-2312', 'date' => '2026-02-20', 'type' => 'Feeding Performance', 'range' => 'Feb 13 - Feb 20', 'status' => 'published', 'owner' => 'System'],
                ['id' => 'RPT-2311', 'date' => '2026-02-19', 'type' => 'Health & Alerts', 'range' => 'Feb 12 - Feb 19', 'status' => 'review', 'owner' => 'Admin'],
                ['id' => 'RPT-2310', 'date' => '2026-02-18', 'type' => 'Growth & Weight', 'range' => 'Feb 11 - Feb 18', 'status' => 'published', 'owner' => 'System'],
                ['id' => 'RPT-2309', 'date' => '2026-02-17', 'type' => 'Inventory & Feed Cost', 'range' => 'Feb 10 - Feb 17', 'status' => 'draft', 'owner' => 'Manager'],
                ['id' => 'RPT-2308', 'date' => '2026-02-16', 'type' => 'Feeding Performance', 'range' => 'Feb 09 - Feb 16', 'status' => 'published', 'owner' => 'System'],
            ];
        @endphp

        <main class="min-h-screen px-4 pb-10 pt-20 lg:ml-80 lg:px-8 lg:pt-8">
            <div class="mx-auto max-w-7xl space-y-6">
                <section class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Smart-Hog Intelligence</p>
                            <h1 class="mt-2 text-2xl font-semibold text-slate-900 sm:text-3xl">Reports Center</h1>
                            <p class="mt-2 max-w-3xl text-sm text-slate-600">Generate, track, and export farm performance reports from a single workspace.</p>
                            <p class="mt-3 text-xs font-medium text-slate-500">{{ now()->format('l, F d, Y') }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">New Report</button>
                            <button type="button" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">Export All</button>
                        </div>
                    </div>
                </section>

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($reportSummary as $item)
                        <article @class([
                            'rounded-2xl border bg-white p-5 shadow-sm',
                            'border-emerald-200' => $item['tone'] === 'emerald',
                            'border-cyan-200' => $item['tone'] === 'cyan',
                            'border-rose-200' => $item['tone'] === 'rose',
                            'border-amber-200' => $item['tone'] === 'amber',
                        ])>
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">{{ $item['label'] }}</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $item['value'] }}</p>
                            <p @class([
                                'mt-2 text-sm font-medium',
                                'text-emerald-700' => $item['tone'] === 'emerald',
                                'text-cyan-700' => $item['tone'] === 'cyan',
                                'text-rose-700' => $item['tone'] === 'rose',
                                'text-amber-800' => $item['tone'] === 'amber',
                            ])>{{ $item['meta'] }}</p>
                        </article>
                    @endforeach
                </section>

                <section class="grid gap-6 xl:grid-cols-3">
                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-2">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Report Volume Trend</h2>
                                <p class="text-sm text-slate-600">Generated reports across the past eight weeks.</p>
                            </div>
                            <select class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                <option>Last 8 Weeks</option>
                                <option>Last 3 Months</option>
                                <option>This Year</option>
                            </select>
                        </div>

                        <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                            <svg viewBox="0 0 720 250" class="h-60 w-full" role="img" aria-label="Reports volume chart">
                                <g stroke="#e2e8f0" stroke-width="1">
                                    <line x1="40" y1="30" x2="690" y2="30" />
                                    <line x1="40" y1="75" x2="690" y2="75" />
                                    <line x1="40" y1="120" x2="690" y2="120" />
                                    <line x1="40" y1="165" x2="690" y2="165" />
                                    <line x1="40" y1="210" x2="690" y2="210" />
                                </g>
                                <polyline points="44,182 126,170 208,160 290,148 372,126 454,118 536,102 618,94 684,82" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                <g fill="#047857">
                                    <circle cx="44" cy="182" r="4" /><circle cx="126" cy="170" r="4" /><circle cx="208" cy="160" r="4" /><circle cx="290" cy="148" r="4" /><circle cx="372" cy="126" r="4" /><circle cx="454" cy="118" r="4" /><circle cx="536" cy="102" r="4" /><circle cx="618" cy="94" r="4" /><circle cx="684" cy="82" r="4" />
                                </g>
                                <g fill="#64748b" font-size="12">
                                    <text x="36" y="232">W1</text><text x="118" y="232">W2</text><text x="200" y="232">W3</text><text x="282" y="232">W4</text><text x="364" y="232">W5</text><text x="446" y="232">W6</text><text x="528" y="232">W7</text><text x="610" y="232">W8</text>
                                </g>
                            </svg>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <h2 class="text-lg font-semibold text-slate-900">Report Categories</h2>
                        <p class="mt-1 text-sm text-slate-600">Distribution of generated report types.</p>
                        <div class="mt-4 space-y-3">
                            @foreach ($categories as $category)
                                <div @class([
                                    'rounded-xl border p-3',
                                    'border-emerald-200 bg-emerald-50/60' => $category['tone'] === 'emerald',
                                    'border-cyan-200 bg-cyan-50/60' => $category['tone'] === 'cyan',
                                    'border-rose-200 bg-rose-50/60' => $category['tone'] === 'rose',
                                    'border-amber-200 bg-amber-50/60' => $category['tone'] === 'amber',
                                ])>
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-sm font-semibold text-slate-900">{{ $category['name'] }}</p>
                                        <span class="rounded-full bg-white px-2 py-0.5 text-xs font-semibold text-slate-700">{{ $category['count'] }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-600">{{ $category['description'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </article>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Recent Reports</h2>
                            <p class="text-sm text-slate-600">Search, filter, and sort generated reports.</p>
                        </div>
                        <button type="button" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">Download CSV</button>
                    </div>

                    <div class="mt-4 grid gap-2 sm:grid-cols-3">
                        <input id="report-search" type="text" placeholder="Search by report ID or type" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                        <select id="report-status-filter" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="all">All Status</option>
                            <option value="published">Published</option>
                            <option value="review">Under Review</option>
                            <option value="draft">Draft</option>
                        </select>
                        <select id="report-sort" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="latest">Sort: Latest First</option>
                            <option value="oldest">Sort: Oldest First</option>
                        </select>
                    </div>

                    <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="min-w-[860px] w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">Report ID</th>
                                    <th class="px-4 py-3 font-semibold">Date</th>
                                    <th class="px-4 py-3 font-semibold">Type</th>
                                    <th class="px-4 py-3 font-semibold">Coverage</th>
                                    <th class="px-4 py-3 font-semibold">Owner</th>
                                    <th class="px-4 py-3 font-semibold">Status</th>
                                    <th class="px-4 py-3 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody id="reports-body" class="divide-y divide-slate-100 bg-white">
                                @foreach ($reports as $report)
                                    <tr data-status="{{ $report['status'] }}" data-date="{{ $report['date'] }}" data-search="{{ strtolower($report['id'].' '.$report['type']) }}" class="odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
                                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $report['id'] }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $report['date'] }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $report['type'] }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $report['range'] }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $report['owner'] }}</td>
                                        <td class="px-4 py-3">
                                            @if ($report['status'] === 'published')
                                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Published</span>
                                            @elseif ($report['status'] === 'review')
                                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Under Review</span>
                                            @else
                                                <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">Draft</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <button type="button" class="rounded-md border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 transition hover:bg-slate-50">View</button>
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
            const reportSearch = document.getElementById('report-search');
            const reportStatusFilter = document.getElementById('report-status-filter');
            const reportSort = document.getElementById('report-sort');
            const reportsBody = document.getElementById('reports-body');

            function updateReports() {
                if (!reportsBody) {
                    return;
                }

                const query = (reportSearch?.value || '').trim().toLowerCase();
                const status = reportStatusFilter?.value || 'all';
                const sort = reportSort?.value || 'latest';
                const rows = Array.from(reportsBody.querySelectorAll('tr'));

                rows.forEach((row) => {
                    const rowStatus = row.dataset.status || '';
                    const rowSearch = row.dataset.search || '';
                    const matchesStatus = status === 'all' || rowStatus === status;
                    const matchesSearch = query === '' || rowSearch.includes(query);
                    row.style.display = matchesStatus && matchesSearch ? '' : 'none';
                });

                const visibleRows = rows.filter((row) => row.style.display !== 'none');
                visibleRows.sort((first, second) => {
                    const firstDate = new Date(first.dataset.date || '');
                    const secondDate = new Date(second.dataset.date || '');
                    return sort === 'oldest' ? firstDate - secondDate : secondDate - firstDate;
                });

                visibleRows.forEach((row) => reportsBody.appendChild(row));
            }

            [reportSearch, reportStatusFilter, reportSort].forEach((element) => {
                element?.addEventListener('input', updateReports);
                element?.addEventListener('change', updateReports);
            });
        </script>
    </body>
</html>
