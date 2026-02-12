<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'SMART-HOG') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900">
        <div class="relative overflow-x-hidden">
            <div class="pointer-events-none absolute -left-28 -top-28 h-72 w-72 rounded-full bg-emerald-300/30 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-24 top-10 h-72 w-72 rounded-full bg-cyan-300/30 blur-3xl"></div>

            @include('layouts.header')

            <main class="relative mx-auto w-full max-w-6xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
                <section id="top" class="overflow-hidden rounded-3xl border border-emerald-100/80 bg-white/90 shadow-xl shadow-emerald-900/5 backdrop-blur">
                    <div class="grid gap-8 bg-gradient-to-br from-emerald-50/80 via-white to-cyan-50/70 p-6 sm:p-8 lg:grid-cols-2 lg:p-10">
                        <div>
                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-emerald-700">Smart Farming Automation</span>
                            <h1 class="mt-4 max-w-[18ch] text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl lg:text-5xl">Automated pig feeding that keeps your farm on schedule.</h1>
                            <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">
                                SMART-HOG delivers dependable feeding automation, real-time monitoring, and mobile control to keep farm operations simple, consistent, and efficient.
                            </p>
                            <div class="mt-6 flex flex-wrap gap-3">
                                <a href="#final-cta" class="rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-900/20 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2">Get Started</a>
                                <a href="#how-it-works" class="rounded-xl border border-emerald-200 bg-white px-5 py-3 text-sm font-semibold text-emerald-700 transition duration-300 hover:-translate-y-0.5 hover:border-emerald-300 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2">Learn More</a>
                            </div>
                        </div>

                        <div class="space-y-3 rounded-2xl border border-emerald-100 bg-white/80 p-4 shadow-md shadow-emerald-900/5">
                            <div class="flex items-center justify-between rounded-xl border border-emerald-100 bg-white p-3 text-sm text-slate-700">
                                <span>Device Uptime</span>
                                <strong class="font-semibold text-slate-900">99.8%</strong>
                            </div>
                            <div class="flex items-center justify-between rounded-xl border border-emerald-100 bg-white p-3 text-sm text-slate-700">
                                <span>Feed Accuracy</span>
                                <strong class="font-semibold text-slate-900">+/- 2%</strong>
                            </div>
                            <div class="flex items-center justify-between rounded-xl border border-emerald-100 bg-white p-3 text-sm text-slate-700">
                                <span>Remote Access</span>
                                <span class="rounded-full bg-gradient-to-r from-emerald-600 to-blue-600 px-2.5 py-1 text-xs font-semibold text-white">Online</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl border border-emerald-100 bg-white p-3 text-sm text-slate-700">
                                <span>System Health</span>
                                <strong class="font-semibold text-emerald-600">Stable</strong>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="features" class="rounded-3xl border border-emerald-100/80 bg-white/90 p-6 shadow-xl shadow-emerald-900/5 sm:p-8">
                    <h2 class="text-2xl font-semibold text-slate-900 sm:text-3xl">Core Features</h2>
                    <p class="mt-2 max-w-3xl text-slate-600">A practical IoT platform built for everyday farm operations.</p>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <article class="rounded-2xl border border-emerald-100 bg-gradient-to-b from-white to-emerald-50/60 p-4 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-r from-emerald-600 to-blue-600 text-sm text-white">⏱</div>
                            <h3 class="mt-3 text-base font-semibold text-slate-900">Time-based Feeding</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Set daily feeding schedules to maintain consistency and reduce missed or late feedings.</p>
                        </article>
                        <article class="rounded-2xl border border-emerald-100 bg-gradient-to-b from-white to-emerald-50/60 p-4 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-r from-emerald-600 to-blue-600 text-sm text-white">📱</div>
                            <h3 class="mt-3 text-base font-semibold text-slate-900">Mobile App Control</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Adjust feeding plans and trigger actions quickly from your phone while on-site or off-site.</p>
                        </article>
                        <article class="rounded-2xl border border-emerald-100 bg-gradient-to-b from-white to-emerald-50/60 p-4 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-r from-emerald-600 to-blue-600 text-sm text-white">📡</div>
                            <h3 class="mt-3 text-base font-semibold text-slate-900">IoT Monitoring</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Track dispenser status, feeding logs, and device conditions with real-time insights.</p>
                        </article>
                        <article class="rounded-2xl border border-emerald-100 bg-gradient-to-b from-white to-emerald-50/60 p-4 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-r from-emerald-600 to-blue-600 text-sm text-white">🛡</div>
                            <h3 class="mt-3 text-base font-semibold text-slate-900">Reliability</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Designed for steady daily use with predictable operation and dependable feeding output.</p>
                        </article>
                    </div>
                </section>

                <section id="how-it-works" class="rounded-3xl border border-emerald-100/80 bg-white/90 p-6 shadow-xl shadow-emerald-900/5 sm:p-8">
                    <h2 class="text-2xl font-semibold text-slate-900 sm:text-3xl">How It Works</h2>
                    <p class="mt-2 max-w-3xl text-slate-600">SMART-HOG runs on a clear, guided process from setup to daily monitoring.</p>
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <article class="rounded-2xl border border-emerald-100 bg-white p-4 shadow-sm">
                            <div class="mb-3 h-3 w-3 rounded-full bg-gradient-to-r from-emerald-600 to-blue-600"></div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Step 1</p>
                            <h3 class="mt-1 text-base font-semibold text-slate-900">Schedule</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Set feed times and serving sizes for each group in a few taps.</p>
                        </article>
                        <article class="rounded-2xl border border-emerald-100 bg-white p-4 shadow-sm">
                            <div class="mb-3 h-3 w-3 rounded-full bg-gradient-to-r from-emerald-600 to-blue-600"></div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Step 2</p>
                            <h3 class="mt-1 text-base font-semibold text-slate-900">Dispense</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">The system dispenses feed automatically based on your saved routine.</p>
                        </article>
                        <article class="rounded-2xl border border-emerald-100 bg-white p-4 shadow-sm">
                            <div class="mb-3 h-3 w-3 rounded-full bg-gradient-to-r from-emerald-600 to-blue-600"></div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Step 3</p>
                            <h3 class="mt-1 text-base font-semibold text-slate-900">Monitor</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Track events and performance in real time through a mobile dashboard.</p>
                        </article>
                    </div>
                </section>

                <section id="final-cta" class="rounded-3xl bg-gradient-to-r from-emerald-600 via-cyan-600 to-blue-600 p-6 shadow-xl shadow-cyan-900/20 sm:p-8">
                    <h2 class="text-2xl font-semibold text-white sm:text-3xl">Ready to modernize your piggery workflow?</h2>
                    <p class="mt-2 max-w-3xl text-cyan-50">Start with SMART-HOG today and make feeding smarter, safer, and easier to manage.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('show.login') }}" class="rounded-xl bg-white px-5 py-3 text-sm font-semibold text-cyan-700 transition duration-300 hover:-translate-y-0.5 hover:bg-cyan-50 focus:outline-none focus:ring-2 focus:ring-white/80 focus:ring-offset-2 focus:ring-offset-cyan-700">Sign In</a>
                        <a href="#" class="rounded-xl border border-cyan-100/70 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-cyan-100/80 focus:ring-offset-2 focus:ring-offset-cyan-700">Contact Team</a>
                    </div>
                </section>
            </main>

            @include('layouts.footer')
        </div>
    </body>
</html>
