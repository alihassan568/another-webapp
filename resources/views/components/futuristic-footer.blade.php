<!-- Futuristic Footer Component -->
<footer class="py-12 border-t border-cyan-500/30 holographic relative z-10">
    <div class="container mx-auto px-6 text-center">
        <div class="flex justify-center items-center space-x-3 mb-6">
            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-cyan-400 to-purple-600 pulse-glow"></div>
            <span class="font-orbitron font-bold text-xl">Another-Go</span>
        </div>
        <p class="font-exo text-gray-400">
            &copy; 2025 Another-Go. Shaping the future of commerce.
        </p>
        
        <!-- Optional Footer Links -->
        @if(isset($showFooterLinks) && $showFooterLinks)
        <div class="flex flex-wrap justify-center gap-6 mt-6 text-sm">
            <a href="#" class="text-gray-400 hover:text-cyan-400 transition-colors font-exo">Privacy Policy</a>
            <a href="#" class="text-gray-400 hover:text-cyan-400 transition-colors font-exo">Terms of Service</a>
            <a href="#" class="text-gray-400 hover:text-cyan-400 transition-colors font-exo">Support</a>
            <a href="#" class="text-gray-400 hover:text-cyan-400 transition-colors font-exo">Contact</a>
        </div>
        @endif
    </div>
</footer>