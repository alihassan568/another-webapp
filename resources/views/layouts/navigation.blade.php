<nav 
    x-data="{ open:false }" 
    class="backdrop-blur-xl bg-white/80 dark:bg-gray-900/80 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-50 shadow-sm"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Left Section -->
            <div class="flex items-center space-x-10">

                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('logo.jpg') }}" class="h-9 w-9 rounded-full shadow-md" alt="Logo">
                    <span class="font-bold text-xl text-gray-800 dark:text-white tracking-tight">
                       {{ Auth::user()->name }}
                    </span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden sm:flex space-x-6">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->routeIs('dashboard') 
                                    ? 'text-white bg-blue-600 shadow-md' 
                                    : 'text-gray-700 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.items') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->routeIs('admin.items') 
                                    ? 'text-white bg-blue-600 shadow-md' 
                                    : 'text-gray-700 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        Items
                    </a>

                    <a href="{{ url('/admin/commission-settings') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->is('admin/commission-settings') 
                                    ? 'text-white bg-blue-600 shadow-md' 
                                    : 'text-gray-700 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        Commission Settings
                    </a>
                </div>
            </div>

            <!-- Right Section -->
            <div class="hidden sm:flex items-center space-x-4">

                <!-- User Dropdown -->
                <div x-data="{ dropdown:false }" class="relative">
                    <button 
                        @click="dropdown = !dropdown"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-sm font-medium transition-all"
                    >
                        <span class="text-gray-800 dark:text-gray-300">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div 
                        x-show="dropdown" 
                        @click.away="dropdown=false"
                        x-transition
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-xl py-2 border border-gray-200 dark:border-gray-700"
                    >
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button 
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-700/30 text-red-600 dark:text-red-400"
                            >
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button 
                @click="open = !open"
                class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="sm:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
        
        <div class="px-4 py-3 space-y-1">

            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-600 hover:text-white">
                Dashboard
            </a>

            <a href="{{ route('admin.items') }}"
               class="block px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-600 hover:text-white">
                Items
            </a>

            <a href="{{ url('/admin/commission-settings') }}"
               class="block px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-600 hover:text-white">
                Commission Settings
            </a>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-4">

            <div class="text-gray-800 dark:text-gray-300 font-bold">{{ Auth::user()->name }}</div>
            <div class="text-gray-500 dark:text-gray-400 text-sm">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">

                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button 
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="block w-full text-left px-4 py-2 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-700/40">
                        Log Out
                    </button>
                </form>
            </div>

        </div>
    </div>

</nav>
