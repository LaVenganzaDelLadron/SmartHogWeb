<header class="w-full border-b border-[#e1ddd4] bg-white/80 backdrop-blur-sm animate-fadeIn">
    <div class="mx-auto max-w-6xl px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="h-12 w-12 text-4xl animate-float">🐷</span>
            <div>
                <p class="text-sm uppercase tracking-wide text-[#6b6257]">IoT Piggery System</p>
                <p class="text-lg font-semibold text-[#2f2a24]">SMART-HOG</p>
            </div>
        </div>
        <nav class="flex items-center gap-3 text-sm">
            @auth
               <a href="{{ route('show.dashobard') }}" class="px-4 py-2 rounded-md border border-[#2f5f3f] text-[#2f5f3f] transition-all duration-300 hover:bg-gradient-to-r hover:from-emerald-600 hover:to-blue-600 hover:text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/20 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2"> Dashboard </a>
            @else
                <a href="#features" class="px-4 py-2 rounded-md border border-transparent text-[#2f2a24] hover:border-[#d6cdbf] transition-all">Explore</a>
                <a href="{{ route('show.login') }}" class="px-4 py-2 rounded-md border border-[#2f5f3f] text-[#2f5f3f] hover:bg-[#2f5f3f] hover:text-white transition-all">Login</a>
            @endauth
        </nav>
    </div>
</header>
