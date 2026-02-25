<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login | {{ config('app.name', 'SMART-HOG') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

    </head>
    <body class="min-h-screen bg-[radial-gradient(60rem_35rem_at_10%_-10%,rgba(16,185,129,0.22),transparent),radial-gradient(55rem_35rem_at_90%_0%,rgba(6,182,212,0.2),transparent),radial-gradient(55rem_35rem_at_50%_120%,rgba(37,99,235,0.22),transparent),#020617] font-sans text-slate-100 antialiased">
        <div class="relative flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="pointer-events-none absolute left-8 top-8 h-40 w-40 rounded-full bg-emerald-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute right-8 top-20 h-44 w-44 rounded-full bg-cyan-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-10 left-1/2 h-52 w-52 -translate-x-1/2 rounded-full bg-blue-500/20 blur-3xl"></div>

            <main class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-6 text-slate-800 shadow-2xl sm:p-8">
                <div class="mb-8 text-center">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">Smart Farming Automation</p>
                    <h1 class="mt-3 text-3xl font-semibold text-slate-900">Welcome Back</h1>
                    <p class="mt-2 text-sm text-slate-600">Login to your SMART-HOG dashboard and manage your feeding system in real-time.</p>
                </div>

                <form id="loginForm" data-firebase-auth="true" method="POST" action="{{ Route::has('login') ? route('login') : '#' }}" class="space-y-5">
                    
                    @csrf
                    <div id="loginError" class="hidden rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700"></div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email or Username</label>
                        <input
                            id="email"
                            name="email"
                            type="text"
                            autocomplete="username"
                            value="{{ old('email') }}"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 transition-[border-color,box-shadow,background-color] duration-200 placeholder:text-slate-400 focus:border-teal-500 focus:shadow-[0_0_0_3px_rgba(45,212,191,0.22),0_0_20px_rgba(14,116,144,0.2)] focus:outline-none"
                            placeholder="you@example.com"
                        >
                        @error('email')
                            <p class="mt-2 text-xs text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 transition-[border-color,box-shadow,background-color] duration-200 placeholder:text-slate-400 focus:border-teal-500 focus:shadow-[0_0_0_3px_rgba(45,212,191,0.22),0_0_20px_rgba(14,116,144,0.2)] focus:outline-none"
                            placeholder="Enter your password"
                        >
                        @error('password')
                            <p class="mt-2 text-xs text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2 text-slate-600">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 bg-white text-emerald-500 focus:ring-emerald-400" {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>
                        <a href="{{ Route::has('password.request') ? route('password.request') : '#' }}" class="text-cyan-300 transition hover:text-cyan-200">Forgot Password?</a>
                    </div>

                    <button
                        type="submit"
                        class="w-full overflow-hidden rounded-xl bg-gradient-to-r from-emerald-500 via-cyan-500 to-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-900/30 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2 focus:ring-offset-slate-900"
                    >
                        Login
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-600">
                    New to SMART-HOG?
                    <a href="{{ route('show.signup') }}" class="font-medium text-emerald-300 transition hover:text-emerald-200">Sign Up</a>
                </p>
            </main>
        </div>
    </body>
</html>
