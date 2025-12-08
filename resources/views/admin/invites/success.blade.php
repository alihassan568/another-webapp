<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Aboard - Another Go</title>
    
    <!-- Include Futuristic Styles Component -->
    @include('components.futuristic-styles')
</head>
<body class="min-h-screen bg-black text-white overflow-x-hidden relative flex items-center justify-center p-4">
    
    <!-- Include Futuristic Background Component -->
    @include('components.futuristic-background', ['id' => 'success'])

    <div class="w-full max-w-2xl relative z-10" x-data="{ showPassword: false }">
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

        <!-- Success Card -->
        <div class="cyber-card rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="px-8 pt-8 pb-6 holographic border-b border-cyan-500/30">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-cyan-600 rounded-full flex items-center justify-center pulse-glow">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="font-orbitron font-bold text-2xl text-white text-center mb-2">Welcome Aboard!</h2>
                <p class="font-exo text-cyan-300 text-center">Your account has been created successfully</p>
            </div>

            <div class="p-8">
                <!-- Warning Message -->
                <div class="mb-6 p-4 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/50 rounded-xl">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="font-orbitron font-bold text-yellow-400 mb-1">Important!</h3>
                            <p class="font-exo text-sm text-yellow-200">
                                Save your credentials now! This password will not be shown again after you reload this page.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Credentials Display -->
                <div class="space-y-4 mb-8">
                    <!-- Email -->
                    <div class="holographic rounded-xl p-6 border border-cyan-500/30">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <svg class="w-6 h-6 text-cyan-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="font-exo text-sm text-gray-400 mb-1">Email Address</p>
                                    <p class="font-orbitron text-white font-semibold" id="email-text">{{ $email }}</p>
                                </div>
                            </div>
                            <button onclick="copyToClipboard('{{ $email }}', 'email')" 
                                    class="ml-4 px-3 py-2 bg-cyan-500/20 hover:bg-cyan-500/30 border border-cyan-500/50 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="holographic rounded-xl p-6 border border-purple-500/30">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <svg class="w-6 h-6 text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="font-exo text-sm text-gray-400 mb-1">Password</p>
                                    <div class="flex items-center space-x-3">
                                        <p class="font-mono text-white font-semibold" id="password-text" x-text="showPassword ? '{{ $password }}' : '••••••••••••'"></p>
                                        <button @click="showPassword = !showPassword" 
                                                class="text-gray-400 hover:text-white transition-colors">
                                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button onclick="copyToClipboard('{{ $password }}', 'password')" 
                                    class="ml-4 px-3 py-2 bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/50 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="holographic rounded-xl p-6 border border-pink-500/30">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-pink-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <div>
                                <p class="font-exo text-sm text-gray-400 mb-1">Assigned Role</p>
                                <p class="font-orbitron text-white font-semibold">{{ $role }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mb-6 p-4 bg-gradient-to-r from-cyan-500/10 to-purple-500/10 rounded-xl border border-cyan-500/20">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-cyan-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="font-orbitron font-bold text-cyan-400 mb-1">Next Steps</h3>
                            <p class="font-exo text-sm text-gray-300">
                                Use these credentials to log in to your account. We recommend changing your password after your first login for better security.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <a href="{{ route('login') }}" 
                   class="cyber-button w-full px-6 py-4 rounded-xl font-orbitron font-bold text-white transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Go to Login
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-8 text-center">
            <p class="font-exo text-gray-400 text-sm">
                Need help? Contact support at support@another-go.com
            </p>
        </footer>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script>
        function copyToClipboard(text, type) {
            navigator.clipboard.writeText(text).then(() => {
                // Show temporary success message
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg font-exo font-semibold z-50';
                message.textContent = type.charAt(0).toUpperCase() + type.slice(1) + ' copied to clipboard!';
                document.body.appendChild(message);
                
                setTimeout(() => {
                    message.remove();
                }, 3000);
            });
        }
    </script>
</body>
</html>
