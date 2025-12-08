<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Another Go</title>
    
    <!-- Include Futuristic Styles Component -->
    @include('components.futuristic-styles')
</head>
<body class="min-h-screen bg-black text-white overflow-x-hidden relative flex items-center justify-center p-4">
    
    <!-- Include Futuristic Background Component -->
    @include('components.futuristic-background', ['id' => 'login'])

    <div class="w-full max-w-md relative z-10">
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

        <!-- Login Card -->
        <div class="cyber-card rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="px-8 pt-8 pb-6 holographic border-b border-cyan-500/30">
                <h2 class="font-orbitron font-bold text-2xl text-white mb-2">Welcome Back</h2>
                <p class="font-exo text-gray-300">Please sign in to your account</p>
            </div>

            <div class="p-8">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-6 p-4 holographic border border-cyan-500/50 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-cyan-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="font-exo text-sm text-cyan-300 font-medium">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block font-exo text-sm font-semibold text-cyan-400 mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="username"
                                   class="cyber-input block w-full pl-10 pr-3 py-3 rounded-xl font-exo"
                                   placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 font-exo text-sm text-red-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block font-exo text-sm font-semibold text-cyan-400 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   required 
                                   autocomplete="current-password"
                                   class="cyber-input block w-full pl-10 pr-3 py-3 rounded-xl font-exo"
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 font-exo text-sm text-red-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" 
                                   name="remember" 
                                   type="checkbox" 
                                   class="h-4 w-4 text-cyan-400 focus:ring-cyan-500 border-cyan-500/30 rounded bg-black/50 transition-colors">
                            <label for="remember_me" class="ml-2 block font-exo text-sm text-gray-300">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-exo text-sm font-medium text-cyan-400 hover:text-cyan-300 transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" 
                            class="cyber-button w-full flex justify-center items-center py-3 px-4 rounded-xl font-orbitron font-bold text-white transition-all duration-300 transform hover:scale-[1.02]">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Sign In
                    </button>
                </form>

                <!-- Register Link -->
                @if (Route::has('register'))
                    <div class="mt-6 text-center">
                        <p class="font-exo text-sm text-gray-400">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="font-medium text-cyan-400 hover:text-cyan-300 transition-colors">
                                Create one now
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-12 text-center">
            <div class="flex justify-center items-center space-x-3 mb-4">
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-cyan-400 to-purple-600 pulse-glow"></div>
                <span class="font-orbitron font-bold text-xl neon-text">Another-Go</span>
            </div>
            <p class="font-exo text-gray-400">
                &copy; {{ date('Y') }} Another-Go. Shaping the future of commerce.
            </p>
        </footer>
    </div>
</body>
</html>
