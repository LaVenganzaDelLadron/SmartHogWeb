<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sign Up | {{ config('app.name', 'SMART-HOG') }}</title>

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
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-500">Smart Farming Automation</p>
                    <h1 class="mt-3 text-3xl font-semibold text-slate-900">Create Account</h1>
                    <p class="mt-2 text-sm text-slate-600">Set up your SMART-HOG profile to start monitoring and managing your system.</p>
                </div>

                <form id="signupForm" data-firebase-auth="true" method="POST" action="{{ Route::has('signup') ? route('signup') : '#' }}" class="space-y-5">
                    @csrf
                    <div id="signupError" class="hidden rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700"></div>

                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Full Name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            autocomplete="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 transition-[border-color,box-shadow,background-color] duration-200 placeholder:text-slate-400 focus:border-teal-500 focus:shadow-[0_0_0_3px_rgba(45,212,191,0.22),0_0_20px_rgba(14,116,144,0.2)] focus:outline-none"
                            placeholder="Juan Dela Cruz"
                        >
                        @error('name')
                            <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email Address</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 transition-[border-color,box-shadow,background-color] duration-200 placeholder:text-slate-400 focus:border-teal-500 focus:shadow-[0_0_0_3px_rgba(45,212,191,0.22),0_0_20px_rgba(14,116,144,0.2)] focus:outline-none"
                            placeholder="you@example.com"
                        >
                        @error('email')
                            <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 transition-[border-color,box-shadow,background-color] duration-200 placeholder:text-slate-400 focus:border-teal-500 focus:shadow-[0_0_0_3px_rgba(45,212,191,0.22),0_0_20px_rgba(14,116,144,0.2)] focus:outline-none"
                            placeholder="Create a strong password"
                        >
                        @error('password')
                            <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Confirm Password</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 transition-[border-color,box-shadow,background-color] duration-200 placeholder:text-slate-400 focus:border-teal-500 focus:shadow-[0_0_0_3px_rgba(45,212,191,0.22),0_0_20px_rgba(14,116,144,0.2)] focus:outline-none"
                            placeholder="Retype your password"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full overflow-hidden rounded-xl bg-gradient-to-r from-emerald-500 via-cyan-500 to-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-900/30 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2 focus:ring-offset-white"
                    >
                        Create Account
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-600">
                    Already have an account?
                    <a href="{{ route('show.login') }}" class="font-medium text-cyan-600 transition hover:text-cyan-700">Login</a>
                </p>
            </main>
        </div>
    </body>
</html>
