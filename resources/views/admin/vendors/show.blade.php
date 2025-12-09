<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Vendor Details') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    View detailed information about {{ $vendor->name }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.vendors.index') }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Vendors
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-4" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 h-24"></div>
                        <div class="px-6 pb-6">
                            <div class="flex justify-center -mt-12 mb-4">
                                @if($vendor->image)
                                    <img class="h-24 w-24 rounded-full border-4 border-white dark:border-gray-800 object-cover" 
                                         src="{{ asset('storage/' . $vendor->image) }}" 
                                         alt="{{ $vendor->name }}">
                                @else
                                    <div class="h-24 w-24 rounded-full border-4 border-white dark:border-gray-800 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-3xl">{{ strtoupper(substr($vendor->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $vendor->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-3">Vendor ID: #{{ $vendor->id }}</p>
                                
                                @if($vendor->blocked_at)
                                    <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Blocked Since {{ $vendor->blocked_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Active Account
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-900 dark:text-white">{{ $vendor->email }}</span>
                                </div>

                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="text-gray-900 dark:text-white">{{ $vendor->phone ?? 'Not provided' }}</span>
                                </div>

                                <div class="flex items-start text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-gray-900 dark:text-white">{{ $vendor->address ?? 'Not provided' }}</span>
                                </div>

                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-900 dark:text-white">Joined {{ $vendor->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                @if($vendor->blocked_at)
                                    @if(auth()->user()->hasPermissionTo(\App\Modules\User\Enums\Permissions::CAN_UNBLOCK_VENDOR))
                                    <form action="{{ route('admin.vendors.unblock', $vendor->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to unblock this vendor?')"
                                                class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold transition-colors flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                            </svg>
                                            Unblock Vendor
                                        </button>
                                    </form>
                                    @endif
                                @else
                                    @if(auth()->user()->hasPermissionTo(\App\Modules\User\Enums\Permissions::CAN_BLOCK_VENDOR))
                                    <form action="{{ route('admin.vendors.block', $vendor->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to block this vendor? They will not be able to access their account.')"
                                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition-colors flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            </svg>
                                            Block Vendor
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Items</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalItems }}</p>
                                </div>
                                <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $approvedItems }}</p>
                                </div>
                                <div class="bg-green-100 dark:bg-green-900 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">{{ $pendingItems }}</p>
                                </div>
                                <div class="bg-yellow-100 dark:bg-yellow-900 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rejected</p>
                                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $rejectedItems }}</p>
                                </div>
                                <div class="bg-red-100 dark:bg-red-900 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Items</h3>
                        </div>
                        <div class="p-6">
                            @if($vendor->items->count() > 0)
                                <div class="space-y-4">
                                    @foreach($vendor->items as $item)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                        <div class="flex items-center space-x-4">
                                            @if($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-16 h-16 rounded-lg object-cover">
                                            @else
                                                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                                    <span class="text-white font-bold text-xl">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->category ?? 'Uncategorized' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            @if($item->status === 'approved')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Approved</span>
                                            @elseif($item->status === 'pending')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Pending</span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Rejected</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                @if($totalItems > 10)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('admin.items') }}?vendor_id={{ $vendor->id }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                                        View all {{ $totalItems }} items →
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400">No items yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
