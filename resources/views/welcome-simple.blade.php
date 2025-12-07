<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Another-Go | The Future of Commerce</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .font-orbitron { font-family: 'Orbitron', monospace; }
        .font-exo { font-family: 'Exo 2', sans-serif; }
        .neon-text {
            text-shadow: 0 0 10px #00f0ff, 0 0 20px #00f0ff, 0 0 30px #00f0ff;
        }
    </style>
</head>
<body class="min-h-screen bg-black text-white">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-black/80 backdrop-blur-sm border-b border-cyan-500/30">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-cyan-400 to-purple-600 flex items-center justify-center">
                        <span class="font-orbitron font-bold text-xl">AG</span>
                    </div>
                    <span class="font-orbitron font-bold text-2xl neon-text">Another-Go</span>
                </div>
                
                <div class="flex space-x-4">
                    <a href="/login" class="px-6 py-2 rounded-full border border-cyan-400 hover:bg-cyan-400 hover:text-black transition-all duration-300 font-exo">
                        Login
                    </a>
                    <a href="/register" class="px-6 py-2 rounded-full bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-purple-600 hover:to-pink-600 transition-all duration-300 font-exo font-semibold">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 min-h-screen flex items-center">
        <div class="container mx-auto px-6 text-center">
            <h1 class="font-orbitron font-black text-6xl md:text-8xl mb-6">
                <span class="bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                    ANOTHER-GO
                </span>
            </h1>
            <p class="font-exo text-2xl md:text-3xl mb-8 text-cyan-300">
                The Future of Digital Commerce
            </p>
            <div class="font-exo text-lg md:text-xl text-gray-300 mb-12 max-w-3xl mx-auto">
                Experience next-generation marketplace where technology meets innovation. 
                Discover futuristic products, connect with vendors, and shape tomorrow's world.
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16">
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-6 border border-cyan-500/30">
                    <div class="font-orbitron font-bold text-3xl text-cyan-400">{{ $totalApprovedItems ?? 0 }}</div>
                    <div class="font-exo text-gray-300">Active Items</div>
                </div>
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-6 border border-purple-500/30">
                    <div class="font-orbitron font-bold text-3xl text-purple-400">{{ $totalCategories ?? 0 }}</div>
                    <div class="font-exo text-gray-300">Categories</div>
                </div>
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-6 border border-pink-500/30">
                    <div class="font-orbitron font-bold text-3xl text-pink-400">{{ $totalVendors ?? 0 }}</div>
                    <div class="font-exo text-gray-300">Vendors</div>
                </div>
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-6 border border-green-500/30">
                    <div class="font-orbitron font-bold text-3xl text-green-400">24/7</div>
                    <div class="font-exo text-gray-300">Online</div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="px-8 py-3 rounded-full bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-purple-600 hover:to-pink-600 transition-all duration-300 font-exo font-semibold">
                    Get Started
                </a>
                <a href="#features" class="px-8 py-3 rounded-full border border-cyan-400 hover:bg-cyan-400 hover:text-black transition-all duration-300 font-exo">
                    Learn More
                </a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-900/30">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="font-orbitron font-bold text-4xl md:text-5xl mb-4">
                    <span class="bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
                        Why Choose Another-Go
                    </span>
                </h2>
                <p class="font-exo text-xl text-gray-300">
                    Experience the future of e-commerce today
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-8 border border-cyan-500/30">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-cyan-400 to-purple-600 flex items-center justify-center mb-6">
                        <span class="text-2xl">🚀</span>
                    </div>
                    <h3 class="font-orbitron font-bold text-xl mb-4 text-cyan-400">Next-Gen Technology</h3>
                    <p class="font-exo text-gray-300">
                        Built with cutting-edge technology for seamless performance and user experience.
                    </p>
                </div>
                
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-8 border border-purple-500/30">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-400 to-pink-600 flex items-center justify-center mb-6">
                        <span class="text-2xl">🔒</span>
                    </div>
                    <h3 class="font-orbitron font-bold text-xl mb-4 text-purple-400">Secure Platform</h3>
                    <p class="font-exo text-gray-300">
                        Advanced security measures to protect your data and transactions.
                    </p>
                </div>
                
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-8 border border-pink-500/30">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-pink-400 to-cyan-600 flex items-center justify-center mb-6">
                        <span class="text-2xl">⚡</span>
                    </div>
                    <h3 class="font-orbitron font-bold text-xl mb-4 text-pink-400">Lightning Fast</h3>
                    <p class="font-exo text-gray-300">
                        Optimized for speed with instant loading and real-time updates.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-black border-t border-gray-800 py-8">
        <div class="container mx-auto px-6 text-center">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-cyan-400 to-purple-600 flex items-center justify-center">
                    <span class="font-orbitron font-bold text-sm">AG</span>
                </div>
                <span class="font-orbitron font-bold text-xl neon-text">Another-Go</span>
            </div>
            <p class="font-exo text-gray-400">
                &copy; 2025 Another-Go. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>