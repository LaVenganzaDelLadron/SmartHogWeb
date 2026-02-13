<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pig Management | {{ config('app.name', 'SMART-HOG') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-[radial-gradient(70rem_44rem_at_0%_-10%,rgba(16,185,129,0.12),transparent),radial-gradient(60rem_38rem_at_100%_0%,rgba(20,83,45,0.1),transparent),#f3f7f2] text-slate-800 antialiased">
        @include('layouts.sidebar', ['deviceOnline' => true])

        <main class="min-h-screen px-4 pb-10 pt-20 lg:ml-80 lg:px-8 lg:pt-8">
            <div class="mx-auto max-w-7xl space-y-6">
                <section class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">SMART-HOG Module</p>
                            <h1 class="mt-2 text-2xl font-semibold text-slate-900 sm:text-3xl">Pig Management</h1>
                            <p class="mt-2 max-w-3xl text-sm text-slate-600">Register, organize, and monitor pig batches with quick access to growth, feeding, and health records.</p>
                        </div>

                        <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                            <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" d="M10 4v12M4 10h12" />
                            </svg>
                            Add New Pig Batch
                        </button>
                    </div>
                </section>

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Total Pigs</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">124</p>
                        <p class="mt-2 text-sm text-slate-600">Across all registered batches</p>
                    </article>

                    <article class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Active Batches</p>
                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">Normal</span>
                        </div>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">4</p>
                        <p class="mt-2 text-sm text-slate-600">1 batch nearing market age</p>
                    </article>

                    <article class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Growth Stage Distribution</p>
                        <div class="mt-3 flex gap-2 text-xs font-medium">
                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-emerald-700">Weaner 34%</span>
                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-amber-800">Grower 41%</span>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-slate-700">Finisher 25%</span>
                        </div>
                        <p class="mt-3 text-sm text-slate-600">Balanced growth profile</p>
                    </article>

                    <article class="rounded-2xl border border-rose-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Nearing Market Age</p>
                            <span class="rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-semibold text-rose-700">Attention</span>
                        </div>
                        <p class="mt-3 text-3xl font-semibold text-rose-700">18</p>
                        <p class="mt-2 text-sm text-slate-600">Prioritize selling plans this week</p>
                    </article>
                </section>

                <section class="grid gap-6 xl:grid-cols-3">
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

                        @php
                            $batches = collect([
                                ['id' => 'BATCH-001', 'date' => '2026-01-09', 'age' => '12 weeks', 'stage' => 'Grower', 'weight' => '48.4 kg', 'feeding' => 'On Schedule', 'status' => 'normal', 'alerts' => 'None'],
                                ['id' => 'BATCH-002', 'date' => '2025-12-15', 'age' => '15 weeks', 'stage' => 'Finisher', 'weight' => '63.7 kg', 'feeding' => 'Due in 45 min', 'status' => 'attention', 'alerts' => '1 vaccination'],
                                ['id' => 'BATCH-003', 'date' => '2026-01-21', 'age' => '9 weeks', 'stage' => 'Weaner', 'weight' => '31.2 kg', 'feeding' => 'On Schedule', 'status' => 'normal', 'alerts' => 'None'],
                                ['id' => 'BATCH-004', 'date' => '2025-11-28', 'age' => '18 weeks', 'stage' => 'Finisher', 'weight' => '71.1 kg', 'feeding' => 'Check dispenser', 'status' => 'alert', 'alerts' => 'Growth drop'],
                            ])->sortByDesc('date')->values();
                        @endphp

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
                                    @foreach ($batches as $batch)
                                        <tr class="align-middle odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
                                            <td class="px-4 py-3 font-semibold text-slate-800 text-nowrap">{{ $batch['id'] }}</td>
                                            <td class="px-4 py-3 text-slate-600 text-nowrap">{{ $batch['date'] }}</td>
                                            <td class="px-4 py-3 text-slate-600 text-nowrap">{{ $batch['age'] }}</td>
                                            <td class="px-4 py-3 text-slate-600 text-nowrap">{{ $batch['stage'] }}</td>
                                            <td class="px-4 py-3 text-slate-600 text-nowrap">{{ $batch['weight'] }}</td>
                                            <td class="px-4 py-3">
                                                @if ($batch['status'] === 'normal')
                                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ $batch['feeding'] }}</span>
                                                @elseif ($batch['status'] === 'attention')
                                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">{{ $batch['feeding'] }}</span>
                                                @else
                                                    <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">{{ $batch['feeding'] }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-nowrap">
                                                <span class="text-xs font-medium {{ $batch['alerts'] === 'None' ? 'text-emerald-700' : ($batch['alerts'] === 'Growth drop' ? 'text-rose-700' : 'text-amber-700') }}">{{ $batch['alerts'] }}</span>
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Batch Details</h2>
                                <p class="text-sm text-slate-600">Selected: <span class="font-semibold text-slate-800">BATCH-002</span></p>
                            </div>
                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Needs Attention</span>
                        </div>

                        <div class="mt-4 space-y-4">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Feeding History</p>
                                <ul class="mt-2 space-y-2 text-sm text-slate-700">
                                    <li class="flex items-center justify-between"><span>07:00 AM</span><span>Completed · 22 kg</span></li>
                                    <li class="flex items-center justify-between"><span>11:30 AM</span><span>Completed · 20 kg</span></li>
                                    <li class="flex items-center justify-between"><span>03:30 PM</span><span class="text-amber-700">Upcoming · 21 kg</span></li>
                                </ul>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Weight Progression</p>
                                <div class="mt-3 h-2 rounded-full bg-slate-200">
                                    <div class="h-full w-[78%] rounded-full bg-gradient-to-r from-emerald-500 to-cyan-500"></div>
                                </div>
                                <p class="mt-2 text-sm text-slate-700">+3.8 kg in the last 2 weeks</p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Growth Trends</p>
                                <svg viewBox="0 0 300 120" class="mt-2 h-28 w-full" role="img" aria-label="Batch growth trend">
                                    <polyline points="8,92 50,82 92,70 134,63 176,50 218,44 260,34 292,28" fill="none" stroke="#059669" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>
                                    <g fill="#059669">
                                        <circle cx="8" cy="92" r="3"></circle><circle cx="50" cy="82" r="3"></circle><circle cx="92" cy="70" r="3"></circle><circle cx="134" cy="63" r="3"></circle><circle cx="176" cy="50" r="3"></circle><circle cx="218" cy="44" r="3"></circle><circle cx="260" cy="34" r="3"></circle><circle cx="292" cy="28" r="3"></circle>
                                    </g>
                                </svg>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Scheduled Vaccinations</p>
                                <ul class="mt-2 space-y-2 text-sm">
                                    <li class="flex items-center justify-between"><span class="text-slate-700">Booster Dose A</span><span class="text-amber-700">Tomorrow</span></li>
                                    <li class="flex items-center justify-between"><span class="text-slate-700">Vitamin Injection</span><span class="text-slate-500">Feb 18</span></li>
                                </ul>
                            </div>
                        </div>
                    </article>
                </section>

                <section class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4">
                    <p class="text-sm text-emerald-900"><span class="font-semibold">Short version:</span> The Pig Management interface enables users to register pig batches, monitor growth stages, record weight data, and track feeding and health information through a clean, card-based and user-friendly layout.</p>
                </section>
            </div>
        </main>
    </body>
</html>
