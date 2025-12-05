@extends('layouts.futuristic')

@section('title', 'Another-Go | The Future of Commerce')

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20">
        <div class="container mx-auto px-6 text-center">
            <div class="floating">
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
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16">
                <div class="holographic rounded-2xl p-6 pulse-glow">
                    <div class="font-orbitron font-bold text-3xl text-cyan-400">{{ $totalApprovedItems }}</div>
                    <div class="font-exo text-gray-300">Active Items</div>
                </div>
                <div class="holographic rounded-2xl p-6 pulse-glow">
                    <div class="font-orbitron font-bold text-3xl text-purple-400">{{ $totalCategories }}</div>
                    <div class="font-exo text-gray-300">Categories</div>
                </div>
                <div class="holographic rounded-2xl p-6 pulse-glow">
                    <div class="font-orbitron font-bold text-3xl text-pink-400">{{ $totalVendors }}</div>
                    <div class="font-exo text-gray-300">Vendors</div>
                </div>
                <div class="holographic rounded-2xl p-6 pulse-glow">
                    <div class="font-orbitron font-bold text-3xl text-green-400">24/7</div>
                    <div class="font-exo text-gray-300">Online</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Latest Items Section -->
    <section class="py-20 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="font-orbitron font-bold text-4xl md:text-5xl mb-4">
                    <span class="bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
                        Latest Approved Items
                    </span>
                </h2>
                <p class="font-exo text-xl text-gray-300">
                    Discover cutting-edge products from the future
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($latestItems as $item)
                <div class="item-card rounded-2xl overflow-hidden group">
                    <div class="relative h-64 bg-gradient-to-br from-cyan-900/20 to-purple-900/20">
                        @if($item->image)
                            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                                 onerror="this.src='data:image/svg+xml;base64,{{ base64_encode('<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 400 300\" fill=\"#1e293b\"><rect width=\"400\" height=\"300\" fill=\"#0f172a\"/><circle cx=\"200\" cy=\"150\" r=\"60\" fill=\"#334155\" stroke=\"#00f0ff\" stroke-width=\"2\"/><text x=\"200\" y=\"155\" text-anchor=\"middle\" fill=\"#00f0ff\" font-family=\"monospace\" font-size=\"14\">' . substr($item->name, 0, 8) . '</text></svg>') }}'">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-cyan-400 to-purple-600 flex items-center justify-center">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                        </svg>
                                    </div>
                                    <div class="font-exo text-sm text-gray-400">{{ $item->category }}</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($item->discount_percentage > 0)
                        <div class="absolute top-4 right-4 bg-gradient-to-r from-pink-500 to-red-500 text-white px-3 py-1 rounded-full font-bold text-sm">
                            -{{ $item->discount_percentage }}%
                        </div>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-exo text-cyan-400 uppercase tracking-wide">{{ $item->category }}</span>
                            <span class="text-xs font-exo text-gray-400">by {{ $item->user->name ?? 'Unknown' }}</span>
                        </div>
                        
                        <h3 class="font-orbitron font-bold text-lg mb-3 text-white group-hover:text-cyan-400 transition-colors">
                            {{ $item->name }}
                        </h3>
                        
                        <p class="font-exo text-gray-300 text-sm mb-4 line-clamp-2">
                            {{ Str::limit($item->description, 100) }}
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                @if($item->discount_percentage > 0 && $item->discounted_price < $item->price)
                                    <span class="font-orbitron font-bold text-xl text-cyan-400">${{ number_format($item->discounted_price, 2) }}</span>
                                    <span class="font-exo text-gray-500 line-through text-sm">${{ number_format($item->price, 2) }}</span>
                                @else
                                    <span class="font-orbitron font-bold text-xl text-cyan-400">${{ number_format($item->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <button class="px-4 py-2 rounded-full bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-purple-600 hover:to-pink-600 transition-all duration-300 font-exo text-sm font-semibold">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="font-exo text-xl text-gray-400 mb-4">No approved items yet</div>
                    <div class="font-exo text-gray-500">Check back soon for amazing futuristic products!</div>
                </div>
                @endforelse
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20">
        <div class="container mx-auto px-6 text-center">
            <div class="holographic rounded-3xl p-12 max-w-4xl mx-auto">
                <h2 class="font-orbitron font-bold text-4xl mb-6">
                    <span class="bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
                        Ready to Join the Future?
                    </span>
                </h2>
                <p class="font-exo text-xl text-gray-300 mb-8">
                    Start your journey in the next-generation marketplace today
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}" class="px-8 py-4 rounded-full border-2 border-cyan-400 hover:bg-cyan-400 hover:text-black transition-all duration-300 font-exo font-bold text-lg">
                        Start Shopping
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Additional page-specific scripts can go here
</script>
@endpush