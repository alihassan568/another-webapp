<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation - Another Go</title>
    
    <!-- Include Futuristic Styles Component -->
    @include('components.futuristic-styles')
</head>
<body class="min-h-screen bg-black text-white overflow-x-hidden relative flex items-center justify-center p-4">
    
    <!-- Include Futuristic Background Component -->
    @include('components.futuristic-background', ['id' => 'invite'])

    <div class="w-full max-w-2xl relative z-10">
        <!-- Logo/Brand Section -->
        <div class="text-center mb-8 floating">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-cyan-400 to-purple-600 rounded-2xl mb-4 pulse-glow">
                <span class="font-orbitron font-bold text-2xl">AG</span>
            </div>
            <h1 class="font-orbitron font-bold text-3xl">
                <span class="bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                    Another-Go
                </span>
            </h1>
        </div>

        <!-- Invitation Card -->
        <div class="cyber-card rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="px-8 pt-8 pb-6 holographic border-b border-cyan-500/30">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-400 to-purple-600 rounded-full flex items-center justify-center pulse-glow">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="font-orbitron font-bold text-2xl text-white text-center mb-2">You're Invited!</h2>
                <p class="font-exo text-cyan-300 text-center">Join our futuristic platform</p>
            </div>

            <div class="p-8">
                @if (session('error'))
                    <div class="mb-6 p-4 holographic border border-red-500/50 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="font-exo text-sm text-red-300 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Invitation Details -->
                <div class="space-y-6 mb-8">
                    <div class="holographic rounded-xl p-6 border border-cyan-500/30">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-cyan-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            <div>
                                <p class="font-exo text-sm text-gray-400">Email Address</p>
                                <p class="font-orbitron text-white font-semibold">{{ $invite->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <div>
                                <p class="font-exo text-sm text-gray-400">Assigned Role</p>
                                <p class="font-orbitron text-white font-semibold">{{ $invite->role->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Role Description -->
                    <div class="bg-gradient-to-r from-cyan-500/10 to-purple-500/10 rounded-xl p-6 border border-cyan-500/20">
                        <h3 class="font-orbitron font-bold text-lg text-cyan-400 mb-3">What You'll Get</h3>
                        <ul class="font-exo text-gray-300 space-y-2">
                            @if(in_array(strtolower($invite->role->name), ['admin', 'editor', 'manager']))
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-cyan-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Access to the admin dashboard
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-cyan-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Manage users and content
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-cyan-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Advanced permissions and controls
                                </li>
                            @else
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-cyan-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Access to our marketplace
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-cyan-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Browse and purchase items
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-cyan-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Exclusive member benefits
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <form action="{{ route('invite.cancel', $invite->token) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="w-full px-6 py-4 border border-gray-500 hover:border-gray-400 text-gray-300 hover:text-white rounded-xl font-exo font-semibold transition-all duration-300">
                            Decline
                        </button>
                    </form>
                    
                    <form action="{{ route('invite.confirm', $invite->token) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="cyber-button w-full px-6 py-4 rounded-xl font-orbitron font-bold text-white transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Accept Invitation
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-8 text-center">
            <p class="font-exo text-gray-400 text-sm">
                By accepting, you agree to our Terms of Service and Privacy Policy
            </p>
        </footer>
    </div>
</body>
</html>
