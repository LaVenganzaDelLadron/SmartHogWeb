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
        @php
            $penRequestFailed = $errors->has('pen') || $errors->has('pen_name') || $errors->has('capacity') || $errors->has('notes') || $errors->has('date');
            $penFailureMessage = $errors->first('pen')
                ?: $errors->first('pen_name')
                ?: $errors->first('capacity')
                ?: $errors->first('notes')
                ?: $errors->first('date');
        @endphp

        @include('layouts.sidebar', ['deviceOnline' => true])

        <main class="min-h-screen px-4 pb-10 pt-20 lg:ml-80 lg:px-8 lg:pt-8">
            <div id="pig-page-content" class="mx-auto max-w-7xl space-y-6 transition duration-300 {{ in_array(request('modal'), ['add-pen', 'add-pig', 'edit-pen', 'delete-pen'], true) ? 'blur-[2px] pointer-events-none select-none' : '' }}">
                <section class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">SMART-HOG Module</p>
                            <h1 class="mt-2 text-2xl font-semibold text-slate-900 sm:text-3xl">Pig Management</h1>
                            <p class="mt-2 max-w-3xl text-sm text-slate-600">Register, organize, and monitor pig batches with quick access to growth, feeding, and health records.</p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('show.pig', ['modal' => 'add-pen']) }}" class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-white px-4 py-2.5 text-sm font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" d="M3.5 6.5h13M3.5 10h13M3.5 13.5h13" />
                                </svg>
                                Add Pen
                            </a>
                            <a href="{{ route('show.pig', ['modal' => 'add-pig']) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" d="M10 4v12M4 10h12" />
                                </svg>
                                Add New Pig Batch
                            </a>
                        </div>
                    </div>
                </section>

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Total Pigs</p>
                        <div id="pig-total-pigs-skeleton" class="mt-3 h-9 w-24 animate-pulse rounded-lg bg-slate-200"></div>
                        <p id="pig-total-pigs-value" class="mt-3 hidden text-3xl font-semibold text-slate-900">{{ number_format($totalPigs ?? 0) }}</p>
                        <p id="pig-total-pigs-note" class="mt-2 text-sm text-slate-600">Across all registered batches</p>
                    </article>

                    <article class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Active Batches</p>
                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">Normal</span>
                        </div>
                        <div id="pig-active-batches-skeleton" class="mt-3 h-9 w-20 animate-pulse rounded-lg bg-slate-200"></div>
                        <p id="pig-active-batches-value" class="mt-3 hidden text-3xl font-semibold text-slate-900">{{ number_format($activeBatches ?? 0) }}</p>
                        <p id="pig-active-batches-note" class="mt-2 text-sm text-slate-600">Batches with pigs greater than 0</p>
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
                    @include('pig.pig_card')

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

                <section>
                    @include('pig.pen_card')
                </section>

                <section class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4">
                    <p class="text-sm text-emerald-900"><span class="font-semibold">Short version:</span> The Pig Management interface enables users to register pig batches, monitor growth stages, record weight data, and track feeding and health information through a clean, card-based and user-friendly layout.</p>
                </section>
            </div>
        </main>

        @include('pig.add_pen')
        @include('pig.add_pig')
        @include('pig.update_pen')
        @include('pig.delete_pen')

        @if (session('success') || $penRequestFailed)
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const successMessage = @js(session('success'));
                    const failureMessage = @js($penFailureMessage);

                    if (successMessage && typeof window.showSuccessAlert === 'function') {
                        window.showSuccessAlert({
                            title: 'Request Successful',
                            message: successMessage,
                            durationMs: 3200,
                        });
                    }

                    if (failureMessage && typeof window.showWarningAlert === 'function') {
                        window.showWarningAlert({
                            title: 'Request Failed',
                            message: failureMessage,
                            durationMs: 3600,
                        });
                    }
                });
            </script>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const totalPigsValue = document.getElementById('pig-total-pigs-value');
                const totalPigsSkeleton = document.getElementById('pig-total-pigs-skeleton');
                const activeBatchesValue = document.getElementById('pig-active-batches-value');
                const activeBatchesSkeleton = document.getElementById('pig-active-batches-skeleton');
                const activeBatchesNote = document.getElementById('pig-active-batches-note');

                if (!totalPigsValue || !activeBatchesValue || !totalPigsSkeleton || !activeBatchesSkeleton || totalPigsValue.dataset.bound === '1') {
                    return;
                }

                totalPigsValue.dataset.bound = '1';

                const totalPigsApiUrl = @js(route('batches.total_pigs'));
                const activeBatchesApiUrl = @js(route('batches.active'));

                const formatNumber = function (value) {
                    return Number(value ?? 0).toLocaleString();
                };

                const setLoadingState = function () {
                    totalPigsValue.classList.add('hidden');
                    activeBatchesValue.classList.add('hidden');
                    totalPigsSkeleton.classList.remove('hidden');
                    activeBatchesSkeleton.classList.remove('hidden');
                };

                const clearLoadingState = function () {
                    totalPigsSkeleton.classList.add('hidden');
                    activeBatchesSkeleton.classList.add('hidden');
                    totalPigsValue.classList.remove('hidden');
                    activeBatchesValue.classList.remove('hidden');
                };

                setLoadingState();

                Promise.all([
                    fetch(totalPigsApiUrl, { headers: { 'Accept': 'application/json' } }).then(function (response) {
                        return response.json();
                    }),
                    fetch(activeBatchesApiUrl, { headers: { 'Accept': 'application/json' } }).then(function (response) {
                        return response.json();
                    }),
                ])
                    .then(function (results) {
                        const totalPayload = results[0] ?? {};
                        const activePayload = results[1] ?? {};
                        const totalPigs = Number(totalPayload.total_pigs ?? 0);
                        const activeCount = Number(activePayload.count ?? (Array.isArray(activePayload.data) ? activePayload.data.length : 0));

                        totalPigsValue.textContent = formatNumber(totalPigs);
                        activeBatchesValue.textContent = formatNumber(activeCount);

                        if (activeBatchesNote) {
                            activeBatchesNote.textContent = activeCount === 1
                                ? '1 active batch in production'
                                : formatNumber(activeCount) + ' active batches in production';
                        }
                    })
                    .catch(function () {
                        if (activeBatchesNote) {
                            activeBatchesNote.textContent = 'Unable to refresh active batch count right now';
                        }
                    })
                    .finally(function () {
                        clearLoadingState();
                    });
            });
        </script>
    </body>
</html>
