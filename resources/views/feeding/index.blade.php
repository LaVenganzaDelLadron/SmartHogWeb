<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Feeding Management | {{ config('app.name', 'SMART-HOG') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-[radial-gradient(70rem_44rem_at_0%_-10%,rgba(22,163,74,0.12),transparent),radial-gradient(60rem_40rem_at_100%_0%,rgba(120,53,15,0.08),transparent),#f6f5ef] text-slate-800 antialiased">
        @include('layouts.sidebar', ['deviceOnline' => true])

        <main class="min-h-screen px-4 pb-10 pt-20 lg:ml-80 lg:px-8 lg:pt-8">
            <div id="feeding-page-content" class="mx-auto max-w-7xl space-y-6 transition duration-300 {{ request('modal') === 'add-feeding' ? 'blur-[2px] pointer-events-none select-none' : '' }}">
                <section class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-start gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">SMART-HOG Module</p>
                            <h1 class="mt-2 text-2xl font-semibold text-slate-900 sm:text-3xl">Feeding Management</h1>
                            <p class="mt-2 max-w-3xl text-sm text-slate-600">Manage feeding schedules, monitor dispenser activity, and quickly act on delays to keep operations reliable and automated.</p>
                        </div>
                            <a href="{{ route('show.feeding', ['modal' => 'add-growth-stage']) }}" class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-white px-4 py-2.5 text-sm font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 14.5h11M4.5 10h11M4.5 5.5h11" />
                                </svg>
                                Add Feeds
                            </a>
                            <a href="{{ route('show.feeding', ['modal' => 'add-feeding']) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" d="M10 4v12M4 10h12" />
                                </svg>
                                Add Schedule
                            </a>
                    </div>
                </section>

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Today's Schedule</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5h11v11h-11zM4.5 8.5h11" />
                                </svg>
                            </span>
                        </div>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">6</p>
                        <p class="mt-2 text-sm text-slate-600">4 completed, 2 remaining</p>
                    </article>

                    <article class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Feed Consumed</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-800">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 14.5h11M6.5 14.5V7.5m7 7V6.5M9.5 14.5V9.5" />
                                </svg>
                            </span>
                        </div>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">286 kg</p>
                        <p class="mt-2 text-sm text-slate-600">95% of daily target (300 kg)</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Next Feeding</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <circle cx="10" cy="10" r="6.5" />
                                    <path stroke-linecap="round" d="M10 6.8v3.8l2.7 1.5" />
                                </svg>
                            </span>
                        </div>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">3:30 PM</p>
                        <p class="mt-2 text-sm text-slate-600">Pen B · 42 kg scheduled</p>
                    </article>

                    <article class="rounded-2xl border border-rose-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Alerts</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-rose-100 text-rose-700">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 3.5 16.5 15h-13z" />
                                    <path stroke-linecap="round" d="M10 7.7v3.8m0 2.2h.01" />
                                </svg>
                            </span>
                        </div>
                        <p class="mt-3 text-3xl font-semibold text-rose-700">2</p>
                        <p class="mt-2 text-sm text-slate-600">1 delayed, 1 missed feeding</p>
                    </article>
                </section>

                <section class="grid gap-6 xl:grid-cols-3">
                    @include('feeding.feeding_card')

                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-slate-900">Feed Control</h2>
                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Auto Mode</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-600">Manual override requires confirmation to prevent accidental feeder actions.</p>

                        @php
                            $feeders = [
                                ['name' => 'Feeder A', 'state' => 'Open', 'online' => true],
                                ['name' => 'Feeder B', 'state' => 'Closed', 'online' => true],
                                ['name' => 'Feeder C', 'state' => 'Closed', 'online' => false],
                            ];
                        @endphp

                        <div class="mt-4 space-y-3">
                            @foreach ($feeders as $index => $feeder)
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $feeder['name'] }}</p>
                                            <p class="text-xs {{ $feeder['online'] ? 'text-emerald-700' : 'text-rose-700' }}">
                                                {{ $feeder['online'] ? 'Online' : 'Offline' }} ·
                                                <span class="font-medium">{{ $feeder['state'] }}</span>
                                            </p>
                                        </div>

                                        <label class="relative inline-flex cursor-pointer items-center" title="Manual override">
                                            <input
                                                type="checkbox"
                                                class="peer sr-only feeder-toggle"
                                                data-feeder="{{ $feeder['name'] }}"
                                                data-current="{{ $feeder['state'] }}"
                                                {{ $feeder['state'] === 'Open' ? 'checked' : '' }}
                                            >
                                            <span class="h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-emerald-600"></span>
                                            <span class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800">
                            Manual override is logged and applied for 10 minutes before automatic mode resumes.
                        </div>
                    </article>
                </section>
            </div>
        </main>

        @include('feeding.add_feed_sched')

        <script>
            document.querySelectorAll('.feeder-toggle').forEach((toggle) => {
                toggle.addEventListener('change', (event) => {
                    const input = event.currentTarget;
                    const feeder = input.dataset.feeder || 'Feeder';
                    const action = input.checked ? 'OPEN' : 'CLOSE';
                    const ok = window.confirm(`Confirm manual override: ${action} ${feeder}?`);

                    if (!ok) {
                        input.checked = !input.checked;
                        return;
                    }

                    const statusText = input.closest('div.rounded-xl')?.querySelector('p.text-xs span');
                    if (statusText) {
                        statusText.textContent = input.checked ? 'Open' : 'Closed';
                    }
                });
            });
        </script>
    </body>
</html>
