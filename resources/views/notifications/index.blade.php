<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Notifications | {{ config('app.name', 'SMART-HOG') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-[radial-gradient(70rem_45rem_at_0%_-10%,rgba(16,185,129,0.12),transparent),radial-gradient(60rem_40rem_at_100%_0%,rgba(59,130,246,0.09),transparent),#f5f7f8] text-slate-800 antialiased">
        @include('layouts.sidebar', ['deviceOnline' => true, 'newNotificationsCount' => $newNotificationsCount ?? 0])

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

                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="space-y-4" id="notifications-list">
                        @include('notifications.notif_card', ['notifications' => $notifications ?? collect()])
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>
