@php
    $deviceOnline = $deviceOnline ?? true;
    $statusDot = $deviceOnline ? 'bg-emerald-500' : 'bg-rose-500';
    $statusText = $deviceOnline ? 'Online' : 'Offline';
    $statusTone = $deviceOnline ? 'text-emerald-700' : 'text-rose-700';
    $profileName = trim((string) (auth()->user()->name ?? 'Profile'));

    $mainMenu = [
        [
            'label' => 'Dashboard',
            'icon' => 'dashboard',
            'route' => Route::has('dashboard') ? route('dashboard') : (Route::has('show.dashboard') ? route('show.dashboard') : '#'),
            'active' => request()->routeIs('dashboard') || request()->routeIs('show.dashboard'),
        ],
        [
            'label' => 'Pig Management',
            'icon' => 'pig',
            'route' => Route::has('show.pig') ? route('show.pig') : '#',
            'active' => request()->routeIs('Pig Management') || request()->routeIs('show.pig'),
        ],
        [
            'label' => 'Feeding Management',
            'icon' => 'feeding',
            'route' => Route::has('show.feeding') ? route('show.feeding') : '#',
            'active' => request()->routeIs('Feeding Management') || request()->routeIs('show.feeding'),
        ],
        [
            'label' => 'Monitoring & Analytics',
            'icon' => 'analytics',
            'route' => Route::has('show.monitor') ? route('show.monitor') : '#',
            'active' => request()->routeIs('Monitoring MAangement') || request()->routeIs('show.monitor'),
        ],
        [
            'label' => 'Notifications',
            'icon' => 'notifications',
            'route' => Route::has('show.notifications') ? route('show.notifications') : (Route::has('notifications.system') ? route('notifications.system') : '#'),
            'active' => request()->routeIs('notifications.*') || request()->routeIs('show.notifications'),
        ],
        [
            'label' => 'Reports',
            'icon' => 'reports',
            'route' => Route::has('reports.index') ? route('reports.index') : '#',
            'active' => request()->routeIs('reports.*'),
        ],
    ];

    $secondaryMenu = [
        [
            'label' => 'Settings',
            'icon' => 'settings',
            'route' => Route::has('settings.index') ? route('settings.index') : '#',
            'active' => request()->routeIs('settings.*'),
        ],
    ];

    $iconPaths = [
        'dashboard' => 'M3 10.5 12 3l9 7.5v8.25a1.5 1.5 0 0 1-1.5 1.5H15v-6h-6v6H4.5A1.5 1.5 0 0 1 3 18.75z',
        'feeding' => 'M4.5 4.5h15v4.5h-15zm0 6.75h15v4.5h-15zm0 6.75h9',
        'pig' => 'M4.5 12a7.5 7.5 0 0 1 15 0v2.25A1.75 1.75 0 0 1 17.75 16H16v-2.25A1.75 1.75 0 0 0 14.25 12.5H9.75A1.75 1.75 0 0 0 8 14.25V16H6.25A1.75 1.75 0 0 1 4.5 14.25z',
        'analytics' => 'M4.5 18V9m5.25 9V6m5.25 12v-4.5m5.25 4.5V3.75',
        'notifications' => 'M12 3.75a4.5 4.5 0 0 0-4.5 4.5V10.5c0 .9-.3 1.8-.86 2.5L5.25 14.9v1.35h13.5V14.9L17.36 13c-.56-.7-.86-1.6-.86-2.5V8.25A4.5 4.5 0 0 0 12 3.75zM9.75 17.25a2.25 2.25 0 0 0 4.5 0',
        'reports' => 'M6 3.75h8.25L18 7.5v8.75A1.75 1.75 0 0 1 16.25 18h-10.5A1.75 1.75 0 0 1 4 16.25V5.5A1.75 1.75 0 0 1 5.75 3.75zM14.25 3.75V7.5H18',
        'settings' => 'M10.5 3.9a1.6 1.6 0 0 1 3 0l.21.7a1.6 1.6 0 0 0 1.81 1.1l.72-.15a1.6 1.6 0 0 1 1.5 2.6l-.5.55a1.6 1.6 0 0 0 0 2.2l.5.55a1.6 1.6 0 0 1-1.5 2.6l-.72-.15a1.6 1.6 0 0 0-1.8 1.1l-.22.7a1.6 1.6 0 0 1-3 0l-.22-.7a1.6 1.6 0 0 0-1.8-1.1l-.72.15a1.6 1.6 0 0 1-1.5-2.6l.5-.55a1.6 1.6 0 0 0 0-2.2l-.5-.55a1.6 1.6 0 0 1 1.5-2.6l.72.15a1.6 1.6 0 0 0 1.8-1.1zM12 13.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5',
        'profile' => 'M12 12a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9m-7.5 7.5a7.5 7.5 0 0 1 15 0',
    ];
@endphp

<div class="lg:hidden">
    <input id="sidebar-mobile" type="checkbox" class="peer sr-only">

    <label for="sidebar-mobile" class="fixed left-4 top-4 z-40 inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-white px-3 py-2 text-sm font-medium text-emerald-900 shadow-sm">
        <span class="h-2.5 w-2.5 rounded-full {{ $statusDot }}"></span>
        Menu
    </label>

    <label for="sidebar-mobile" class="fixed inset-0 z-40 hidden bg-slate-900/35 peer-checked:block"></label>

    <aside class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full overflow-y-auto rounded-r-3xl border-r border-emerald-100 bg-[#f7fbf6] p-4 shadow-xl transition-transform duration-300 peer-checked:translate-x-0">
        <div class="flex h-full flex-col">
            <div class="rounded-2xl border border-emerald-100 bg-white p-4">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 1 15 0v2.25A1.75 1.75 0 0 1 17.75 16H16v-2.25A1.75 1.75 0 0 0 14.25 12.5H9.75A1.75 1.75 0 0 0 8 14.25V16H6.25A1.75 1.75 0 0 1 4.5 14.25z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">SMART-HOG</p>
                            <p class="text-sm font-semibold text-slate-900">Farm Control</p>
                        </div>
                    </div>
                    <label for="sidebar-mobile" class="cursor-pointer rounded-md p-1.5 text-slate-500 hover:bg-slate-100">
                        <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                        </svg>
                    </label>
                </div>
                <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium {{ $statusTone }}">
                    <span class="h-2 w-2 rounded-full {{ $statusDot }}"></span>
                    Device {{ $statusText }}
                </div>
            </div>

            <nav class="mt-4 flex-1 space-y-5 overflow-y-auto">
                <section>
                    <p class="mb-2 px-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Main Menu</p>
                    <ul class="space-y-1.5">
                        @foreach ($mainMenu as $item)
                            <li>
                                <a href="{{ $item['route'] }}" class="{{ $item['active'] ? 'bg-emerald-100 text-emerald-900' : 'text-slate-700 hover:bg-emerald-50' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths[$item['icon']] }}" />
                                    </svg>
                                    <span class="flex w-full items-center justify-between">
                                        <span>{{ $item['label'] }}</span>
                                        @if (!empty($item['badge']) && $item['badge'] > 0)
                                            <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-semibold text-rose-700">{{ $item['badge'] }}</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section>
                    <p class="mb-2 px-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">System</p>
                    <ul class="space-y-1.5">
                        @foreach ($secondaryMenu as $item)
                            <li>
                                <a href="{{ $item['route'] }}" class="{{ $item['active'] ? 'bg-emerald-100 text-emerald-900' : 'text-slate-700 hover:bg-emerald-50' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths[$item['icon']] }}" />
                                    </svg>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </nav>

            <div id="profile" class="mt-4 space-y-2 border-t border-emerald-100 pt-4">
                <a href="{{ Route::has('profile.show') ? route('profile.show') : '#' }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-emerald-50">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths['profile'] }}" />
                    </svg>
                    <span data-profile-name="{{ $profileName }}">{{ $profileName }}</span>
                </a>

                <form method="POST" class="js-firebase-logout" action="{{ Route::has('web.logout') ? route('web.logout') : '#' }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-700 hover:bg-rose-50">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m-8.25-3h12m0 0-3-3m3 3-3 3" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>

<div class="relative hidden lg:block">
    <input id="sidebar-collapse" type="checkbox" class="peer sr-only">

    <aside class="fixed inset-y-0 left-0 z-30 w-72 overflow-y-auto border-r border-emerald-100 bg-[#f7fbf6] p-4 shadow-sm transition-all duration-300 peer-checked:w-24 peer-checked:[&_.text-label]:hidden peer-checked:[&_.group-label]:hidden peer-checked:[&_.brand-text]:hidden peer-checked:[&_.status-text]:hidden peer-checked:[&_.menu-link]:justify-center peer-checked:[&_.menu-link]:px-2.5 peer-checked:[&_.menu-link]:gap-0 peer-checked:[&_.account-link]:justify-center peer-checked:[&_.account-link]:px-2.5 peer-checked:[&_.account-link]:gap-0">
        <div class="flex h-full flex-col">
            <div class="rounded-2xl border border-emerald-100 bg-white p-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 1 15 0v2.25A1.75 1.75 0 0 1 17.75 16H16v-2.25A1.75 1.75 0 0 0 14.25 12.5H9.75A1.75 1.75 0 0 0 8 14.25V16H6.25A1.75 1.75 0 0 1 4.5 14.25z" />
                            </svg>
                        </div>
                        <div class="brand-text">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">SMART-HOG</p>
                            <p class="text-sm font-semibold text-slate-900">Farm Control</p>
                        </div>
                    </div>

                    <label for="sidebar-collapse" class="cursor-pointer rounded-md p-1.5 text-slate-500 hover:bg-slate-100" title="Toggle sidebar">
                        <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" d="M12.5 5 7.5 10l5 5" />
                        </svg>
                    </label>
                </div>

                <div class="status-text mt-3 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium {{ $statusTone }}">
                    <span class="h-2 w-2 rounded-full {{ $statusDot }}"></span>
                    Device {{ $statusText }}
                </div>

            </div>

            <nav class="mt-4 flex-1 space-y-5 overflow-y-auto">
                <section>
                    <p class="group-label mb-2 px-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Main Menu</p>
                    <ul class="space-y-1.5">
                        @foreach ($mainMenu as $item)
                            <li>
                                <a href="{{ $item['route'] }}" class="menu-link {{ $item['active'] ? 'bg-emerald-100 text-emerald-900' : 'text-slate-700 hover:bg-emerald-50' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition" title="{{ $item['label'] }}">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths[$item['icon']] }}" />
                                    </svg>
                                    <span class="text-label flex w-full items-center justify-between">
                                        <span>{{ $item['label'] }}</span>
                                        @if (!empty($item['badge']) && $item['badge'] > 0)
                                            <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-semibold text-rose-700">{{ $item['badge'] }}</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section>
                    <p class="group-label mb-2 px-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">System</p>
                    <ul class="space-y-1.5">
                        @foreach ($secondaryMenu as $item)
                            <li>
                                <a href="{{ $item['route'] }}" class="menu-link {{ $item['active'] ? 'bg-emerald-100 text-emerald-900' : 'text-slate-700 hover:bg-emerald-50' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition" title="{{ $item['label'] }}">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths[$item['icon']] }}" />
                                    </svg>
                                    <span class="text-label">{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </nav>

            <div class="mt-4 space-y-2 border-t border-emerald-100 pt-4">
                <a href="{{ Route::has('profile.show') ? route('profile.show') : '#' }}" class="account-link flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-emerald-50" title="Profile">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths['profile'] }}" />
                    </svg>
                    <span class="text-label" data-profile-name="{{ $profileName }}">{{ $profileName }}</span>
                </a>

                <form method="POST" class="js-firebase-logout" action="{{ Route::has('web.logout') ? route('web.logout') : '#' }}">
                    @csrf
                    <button type="submit" class="account-link flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-700 hover:bg-rose-50" title="Logout">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m-8.25-3h12m0 0-3-3m3 3-3 3" />
                        </svg>
                        <span class="text-label">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>
