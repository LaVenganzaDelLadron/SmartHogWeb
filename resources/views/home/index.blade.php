<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dashboard | {{ config('app.name', 'SMART-HOG') }}</title>

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
            <div id="home-page-content" class="mx-auto max-w-7xl space-y-6 transition duration-300 {{ request('modal') === 'add-batch' ? 'blur-[2px] pointer-events-none select-none' : '' }}">
                <section class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Farm Overview</p>
                            <h1 class="mt-2 text-2xl font-semibold text-slate-900 sm:text-3xl">Good day, Farm Operator</h1>
                            <p class="mt-2 text-sm text-slate-600">Track feeding status, growth trends, and alerts in one place.</p>
                            <p class="mt-3 text-xs font-medium text-slate-500">{{ now()->format('l, F d, Y') }}</p>
                        </div>

                        <a href="{{ route('show.dashboard', ['modal' => 'add-batch']) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                            <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" d="M10 4v12M4 10h12" />
                            </svg>
                            Add Batch
                        </a>
                    </div>
                </section>

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Total Pigs</p>
                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">Normal</span>
                        </div>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totalPigs ?? 0) }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ number_format($activeBatches ?? 0) }} active batches</p>
                    </article>

                    <article class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Next Feeding</p>
                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-800">Feeding</span>
                        </div>
                        <p class="mt-3 text-2xl font-semibold text-slate-900">3:30 PM</p>
                        <p class="mt-2 text-sm text-slate-600">Batch B · 42 kg feed</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Feed Dispensed Today</p>
                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">95%</span>
                        </div>
                        <p class="mt-3 text-2xl font-semibold text-slate-900">286 kg</p>
                        <p class="mt-2 text-sm text-slate-600">95% of 300 kg target</p>
                        <div class="mt-3 h-2 rounded-full bg-slate-100">
                            <div class="h-full w-[95%] rounded-full bg-emerald-500"></div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-rose-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Active Alerts</p>
                            <span class="rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-semibold text-rose-700">Attention</span>
                        </div>
                        <p class="mt-3 text-3xl font-semibold text-rose-700">3</p>
                        <p class="mt-2 text-sm text-slate-600">2 vaccination · 1 growth concern</p>
                    </article>
                </section>

                <section class="grid gap-6 xl:grid-cols-2">
                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Growth Trends</h2>
                                <p class="text-sm text-slate-600">Weekly pig weight progression by batch</p>
                            </div>
                            <label class="text-sm text-slate-600">
                                <span class="sr-only">Select batch</span>
                                <select class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                    <option>Batch A</option>
                                    <option selected>Batch B</option>
                                    <option>Batch C</option>
                                    <option>Batch D</option>
                                </select>
                            </label>
                        </div>

                        <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                            <svg viewBox="0 0 560 230" class="h-56 w-full" role="img" aria-label="Growth trend chart">
                                <g stroke="#e2e8f0" stroke-width="1">
                                    <line x1="38" y1="30" x2="538" y2="30" />
                                    <line x1="38" y1="75" x2="538" y2="75" />
                                    <line x1="38" y1="120" x2="538" y2="120" />
                                    <line x1="38" y1="165" x2="538" y2="165" />
                                    <line x1="38" y1="210" x2="538" y2="210" />
                                </g>
                                <polyline points="40,176 120,158 200,142 280,126 360,110 440,95 520,82" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                <polyline points="40,188 120,170 200,158 280,148 360,132 440,118 520,102" fill="none" stroke="#0f766e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.8" />
                                <g fill="#047857">
                                    <circle cx="40" cy="176" r="4" /><circle cx="120" cy="158" r="4" /><circle cx="200" cy="142" r="4" /><circle cx="280" cy="126" r="4" /><circle cx="360" cy="110" r="4" /><circle cx="440" cy="95" r="4" /><circle cx="520" cy="82" r="4" />
                                </g>
                                <g fill="#64748b" font-size="12">
                                    <text x="34" y="225">Mon</text><text x="114" y="225">Tue</text><text x="194" y="225">Wed</text><text x="274" y="225">Thu</text><text x="354" y="225">Fri</text><text x="434" y="225">Sat</text><text x="514" y="225">Sun</text>
                                </g>
                            </svg>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Feed Consumption</h2>
                                <p class="text-sm text-slate-600">Daily feed usage patterns</p>
                            </div>
                            <div class="inline-flex rounded-lg border border-slate-200 bg-slate-50 p-1 text-sm">
                                <button type="button" class="rounded-md bg-emerald-700 px-3 py-1.5 font-medium text-white">Weekly</button>
                                <button type="button" class="rounded-md px-3 py-1.5 font-medium text-slate-600 hover:bg-white">Monthly</button>
                            </div>
                        </div>

                        <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                            @php
                                $consumption = [
                                    ['day' => 'Mon', 'value' => 78],
                                    ['day' => 'Tue', 'value' => 82],
                                    ['day' => 'Wed', 'value' => 74],
                                    ['day' => 'Thu', 'value' => 88],
                                    ['day' => 'Fri', 'value' => 80],
                                    ['day' => 'Sat', 'value' => 70],
                                    ['day' => 'Sun', 'value' => 76],
                                ];
                            @endphp
                            <div class="flex h-56 items-end justify-between gap-3">
                                @foreach ($consumption as $bar)
                                    <div class="flex flex-1 flex-col items-center gap-2">
                                        <div class="w-full rounded-t-md bg-gradient-to-t from-amber-700 to-amber-500" style="height: {{ $bar['value'] }}%"></div>
                                        <p class="text-xs font-medium text-slate-600">{{ $bar['day'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </article>
                </section>

                <section>
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900">Quick Actions</h2>
                        <p class="text-xs font-medium uppercase tracking-[0.14em] text-slate-500">Common tasks</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <a href="{{ route('show.dashboard', ['modal' => 'add-batch']) }}" class="group rounded-2xl border-2 border-dashed border-emerald-300 bg-emerald-50/50 p-5 transition hover:border-emerald-500 hover:bg-emerald-50">
                            <p class="text-sm font-semibold text-emerald-900">New Pig Batch</p>
                            <p class="mt-1 text-sm text-emerald-700">Register and configure a new group of pigs.</p>
                        </a>
                        <a href="#" class="group rounded-2xl border-2 border-dashed border-amber-300 bg-amber-50/50 p-5 transition hover:border-amber-500 hover:bg-amber-50">
                            <p class="text-sm font-semibold text-amber-900">Create Schedule</p>
                            <p class="mt-1 text-sm text-amber-700">Set feeding times and batch portions quickly.</p>
                        </a>
                        <a href="#" class="group rounded-2xl border-2 border-dashed border-cyan-300 bg-cyan-50/60 p-5 transition hover:border-cyan-500 hover:bg-cyan-50">
                            <p class="text-sm font-semibold text-cyan-900">Record Weight</p>
                            <p class="mt-1 text-sm text-cyan-700">Log pig weight updates for growth tracking.</p>
                        </a>
                    </div>
                </section>
            </div>
        </main>

        @include('home.add_batch')
    </body>
</html>
