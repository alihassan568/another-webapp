<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Item Details') }} - #{{ $itemData['id'] }}
            </h2>
            <a href="{{ route('admin.items') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Items
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Item Status Badge -->
                    <div class="mb-6">
                        @if($itemData['status'] == 'approved')
                        <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Approved
                        </span>
                        @elseif($itemData['status'] == 'pending')
                        <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pending Approval
                        </span>
                        @else
                        <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Rejected
                        </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column: Item Images Gallery -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                                    Item Images 
                                    @if(!empty($itemData['images']) && is_array($itemData['images']))
                                    <span class="text-sm font-normal text-gray-500">({{ count($itemData['images']) }})</span>
                                    @endif
                                </h3>
                                @if(!empty($itemData['images']) && is_array($itemData['images']))
                                    <!-- Main Image -->
                                    <div class="mb-4">
                                        <img id="mainImage" src="{{ asset($itemData['images'][0]) }}" alt="{{ $itemData['name'] }}" class="w-full h-auto rounded-lg shadow-lg object-cover">
                                        <p class="text-xs text-center mt-2 text-gray-500 dark:text-gray-400">Main Image</p>
                                    </div>
                                    
                                    <!-- Thumbnail Grid (if more than 1 image) -->
                                    @if(count($itemData['images']) > 1)
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($itemData['images'] as $index => $imageUrl)
                                        <div class="relative cursor-pointer thumbnail-container" onclick="changeMainImage('{{ asset($imageUrl) }}', {{ $index }})">
                                            <img src="{{ asset($imageUrl) }}" alt="{{ $itemData['name'] }} - Image {{ $index + 1 }}" class="w-full h-20 object-cover rounded-md shadow hover:shadow-lg transition-shadow duration-200 thumbnail-image {{ $index === 0 ? 'ring-2 ring-blue-500' : '' }}" data-index="{{ $index }}">
                                            @if($index === 0)
                                            <span class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-1.5 py-0.5 rounded font-semibold">MAIN</span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <script>
                                        function changeMainImage(imageUrl, index) {
                                            document.getElementById('mainImage').src = imageUrl;
                                            
                                            // Remove ring from all thumbnails
                                            document.querySelectorAll('.thumbnail-image').forEach(img => {
                                                img.classList.remove('ring-2', 'ring-blue-500');
                                            });
                                            
                                            // Add ring to clicked thumbnail
                                            document.querySelector(`[data-index="${index}"]`).classList.add('ring-2', 'ring-blue-500');
                                        }
                                    </script>
                                    @endif
                                @else
                                <div class="w-full h-64 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-2 text-gray-500 dark:text-gray-400">No images available</p>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Quick Actions -->
                            <div class="mt-6 space-y-3">
                                @if($itemData['status'] == 'pending' || $itemData['status'] == 'rejected')
                                <a href="{{ route('admin.item.accept', $itemData['id']) }}" 
                                    class="w-full inline-flex items-center justify-center bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2.5 rounded-lg shadow-sm transition duration-150 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Approve Item
                                </a>
                                @endif
                                
                                @if($itemData['status'] == 'pending' || $itemData['status'] == 'approved')
                                <a href="{{ route('admin.item.reject.form', $itemData['id']) }}" 
                                    class="w-full inline-flex items-center justify-center bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2.5 rounded-lg shadow-sm transition duration-150 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reject Item
                                </a>
                                
                                <a href="{{ route('admin.item.commission', $itemData['id']) }}" 
                                    class="w-full inline-flex items-center justify-center bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow-sm transition duration-150 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Set Commission
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column: Item Details -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Item Information -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Item Information
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Item Name</label>
                                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $itemData['name'] }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Item ID</label>
                                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">#{{ $itemData['id'] }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $itemData['category'] }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sub-Category</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $itemData['sub_category'] }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</label>
                                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $itemData['description'] ?? 'No description provided' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing Information -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pricing Details
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Original Price</label>
                                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">${{ number_format($itemData['price'], 2) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Discount</label>
                                        <p class="mt-1 text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $itemData['discount_percentage'] ?? 0 }}%</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Final Price</label>
                                        <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($itemData['discounted_price'], 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Vendor Information -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Vendor Information
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Business Name</label>
                                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $itemData['user']['business_name'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vendor Name</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $itemData['user']['name'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vendor Email</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $itemData['user']['email'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Commission Information -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Commission Information
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Commission</label>
                                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $itemData['commission'] }}%</p>
                                    </div>
                                    
                                    @if(!empty($itemData['requested_commission']) && $itemData['requested_commission'] > 0 && $itemData['commission_status'] == 'pending')
                                    <div>
                                        <label class="text-xs font-medium text-yellow-600 dark:text-yellow-400 uppercase tracking-wider">Requested Commission</label>
                                        <p class="mt-1 text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $itemData['requested_commission'] }}%</p>
                                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Awaiting vendor approval</p>
                                    </div>
                                    @endif
                                    
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vendor Approval Status</label>
                                        <p class="mt-1">
                                            @if($itemData['commission_status'] == 'approved')
                                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                ✓ Approved by Vendor
                                            </span>
                                            @elseif($itemData['commission_status'] == 'rejected')
                                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                ✕ Rejected by Vendor
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                ⏳ Pending Vendor Approval
                                            </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                @if($itemData['commission_status'] == 'approved')
                                <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
                                    <p class="text-sm text-green-800 dark:text-green-300">
                                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Vendor has approved the commission rate
                                    </p>
                                </div>
                                @endif
                            </div>

                            <!-- Rejection Reason (if rejected) -->
                            @if($itemData['status'] == 'rejected' && !empty($itemData['rejection_reason']))
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-800 dark:text-red-400 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Rejection Reason
                                </h3>
                                <p class="text-sm text-red-700 dark:text-red-300">{{ $itemData['rejection_reason'] }}</p>
                            </div>
                            @endif

                            <!-- Timestamps -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Timeline
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created At</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $itemData['created_at']['human'] }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Updated</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $itemData['updated_at']['human'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
