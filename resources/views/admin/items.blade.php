<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-white leading-tight">
                    {{ __(ucfirst($status).' Items Management') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Manage and review marketplace items
                </p>
            </div>
            <div class="flex items-center space-x-2 mt-4 sm:mt-0">
                <x-status-badge :status="$status" />
                <span class="text-sm text-gray-500">{{ $items->total() }} total items</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-4" role="alert">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-green-800 dark:text-green-200 font-medium">
                            {{ session('success') }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" class="text-green-400 hover:text-green-600" onclick="this.parentElement.parentElement.parentElement.remove();">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Data Table with Filters -->
            <x-data-table :headers="['ID', 'Image', 'Business', 'Email', 'Item Name', 'Category', 'Sub-Category', 'Description', 'Commission', 'Status', 'Date']" :actions="true">
                
                <!-- Search and Filters Slot -->
                <x-slot name="search">
                    <form method="POST" action="{{ route('admin.items.filter') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" value="{{ $status }}" name="status">
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search Input -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        name="search"
                                        placeholder="Search by item name, business, or email"
                                        value="{{ request('search') }}"
                                        class="pl-10 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Date From -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                                <input
                                    type="date"
                                    name="date_from"
                                    value="{{ request('date_from') }}"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Date To -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                                <input
                                    type="date"
                                    name="date_to"
                                    value="{{ request('date_to') }}"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex flex-wrap gap-3">
                            <x-action-button type="submit" variant="primary" 
                                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 2v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>'>
                                Apply Filters
                            </x-action-button>
                            
                            <x-action-button 
                                href="{{ route('admin.items').'?status=all' }}" 
                                variant="outline"
                                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>'>
                                Reset Filters
                            </x-action-button>
                        </div>
                    </form>
                </x-slot>

                <!-- Table Rows -->
                @if($items->count() > 0)
                    @foreach($items as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <!-- ID -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            #{{ $item['id'] }}
                        </td>
                        
                        <!-- Image -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex-shrink-0 h-12 w-12">
                                <img class="h-12 w-12 rounded-xl object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm" 
                                     src="{{ asset($item['image']) }}" 
                                     alt="{{ $item['name'] }}"
                                     onerror="this.src='data:image/svg+xml;base64,{{ base64_encode('<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\" fill=\"#e5e7eb\"><rect width=\"100\" height=\"100\" fill=\"#f3f4f6\"/><path d=\"M25 25h50v50H25z\" fill=\"#d1d5db\" stroke=\"#9ca3af\" stroke-width=\"2\"/><circle cx=\"35\" cy=\"35\" r=\"3\" fill=\"#9ca3af\"/><path d=\"M30 50 40 40 50 45 70 30 70 65 30 65z\" fill=\"#9ca3af\"/></svg>') }}'">
                            </div>
                        </td>
                        
                        <!-- Business Name -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item['user']['name'] ?? 'N/A' }}
                            </div>
                        </td>
                        
                        <!-- Email -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $item['user']['email'] ?? 'N/A' }}
                            </div>
                        </td>
                        
                        <!-- Item Name -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white max-w-xs truncate">
                                {{ $item['name'] }}
                            </div>
                        </td>
                        
                        <!-- Category -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                {{ $item['category'] }}
                            </span>
                        </td>
                        
                        <!-- Sub Category -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $item['sub_category'] ?? 'N/A' }}
                            </span>
                        </td>
                        
                        <!-- Description -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate" title="{{ $item['description'] }}">
                                {{ Str::limit($item['description'], 50) }}
                            </div>
                        </td>
                        
                        <!-- Commission -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $item['commission'] }}%
                            </div>
                        </td>
                        
                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                <x-status-badge :status="$item['status']" />
                                <x-status-badge :status="$item['commission_status']" :text="ucfirst($item['commission_status'])" />
                            </div>
                        </td>
                        
                        <!-- Date -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $item['created_at']['human'] }}</div>
                            <div class="text-xs text-gray-500">{{ $item['created_at']['formatted'] }} at {{ $item['created_at']['time'] }}</div>
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                @if($item['status'] == 'pending' || $item['status'] == 'rejected')
                                <x-action-button 
                                    href="{{ route('admin.item.accept', $item['id']) }}" 
                                    variant="success" 
                                    size="xs"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'>
                                    Accept
                                </x-action-button>
                                @endif
                                
                                @if($item['status'] == 'pending' || $item['status'] == 'approved')
                                <x-action-button 
                                    href="{{ route('admin.item.reject', $item['id']) }}" 
                                    variant="danger" 
                                    size="xs"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'>
                                    Reject
                                </x-action-button>
                                
                                <x-action-button 
                                    href="{{ route('admin.item.commission', $item['id']) }}" 
                                    variant="primary" 
                                    size="xs"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>'>
                                    Commission
                                </x-action-button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif

                <!-- Empty State -->
                @if($items->count() == 0)
                <x-slot name="empty" :empty="true"></x-slot>
                @endif
                
                <!-- Pagination -->
                <x-slot name="pagination">
                    {{ $items->links() }}
                </x-slot>
                
            </x-data-table>
        </div>
    </div>
</x-app-layout>