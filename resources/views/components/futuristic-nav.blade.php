<nav class="fixed top-0 w-full z-50 holographic border-b border-cyan-500/30 relative">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-cyan-400 to-purple-600 flex items-center justify-center pulse-glow">
                        <span class="font-orbitron font-bold text-xl">AG</span>
                    </div>
                    <span class="font-orbitron font-bold text-2xl neon-text">Another-Go</span>
                </a>
            </div>
            
            @if (Route::has('login'))
            <div class="flex space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2 rounded-full bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-purple-600 hover:to-pink-600 transition-all duration-300 font-exo font-semibold">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2 rounded-full border border-cyan-400 hover:bg-cyan-400 hover:text-black transition-all duration-300 font-exo">
                        Login
                    </a>
                @endauth
            </div>
            @endif
        </div>
    </div>
</nav>