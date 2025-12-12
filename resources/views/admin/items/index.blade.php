<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Items Management') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (session('success'))
                    <div class="bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-200 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.remove();">
                            <svg class="fill-current h-6 w-6 text-green-500 dark:text-green-400" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 5.652a1 1 0 10-1.414-1.414L10 7.586 7.066 4.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 12.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934z" />
                            </svg>
                        </span>
                    </div>
                    @endif

                    <!-- Filters Section -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('admin.items') }}" class="space-y-4">
                            <!-- First Row: Search and Status Filter -->
                            <div class="flex flex-wrap gap-4">
                                <!-- Search Box -->
                                <div class="flex-1 min-w-[300px]">
                                    <input
                                        type="text"
                                        name="search"
                                        placeholder="Search by item name, business name, category, or email..."
                                        value="{{ request('search') }}"
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Status Filter Dropdown -->
                                <div class="w-48">
                                    <select
                                        name="status"
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                
                                <!-- Surprise Bag Filter Dropdown -->
                                <div class="w-48">
                                    <select
                                        name="is_surprise_bag"
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="all" {{ request('is_surprise_bag', 'all') == 'all' ? 'selected' : '' }}>All Types</option>
                                        <option value="1" {{ request('is_surprise_bag') == '1' ? 'selected' : '' }}>Surprise Bags</option>
                                        <option value="0" {{ request('is_surprise_bag') == '0' ? 'selected' : '' }}>Regular Items</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Second Row: Date Filters and Buttons -->
                            <div class="flex flex-wrap gap-4 items-end">
                                <!-- Date From -->
                                <div class="flex-1 min-w-[150px]">
                                    <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                                    <input
                                        type="date"
                                        id="date_from"
                                        name="date_from"
                                        value="{{ request('date_from') }}"
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Date To -->
                                <div class="flex-1 min-w-[150px]">
                                    <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                                    <input
                                        type="date"
                                        id="date_to"
                                        name="date_to"
                                        value="{{ request('date_to') }}"
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Filter Button -->
                                <button
                                    type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition duration-150 ease-in-out font-medium">
                                    Apply Filters
                                </button>

                                <!-- Reset Button -->
                                <a
                                    href="{{ route('admin.items') }}"
                                    class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-6 py-2 rounded-lg shadow transition duration-150 ease-in-out font-medium inline-block text-center">
                                    Reset
                                </a>
                            </div>

                            <!-- Results Info -->
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} items
                                @if(request('search') || request('status') != 'all' || request('date_from') || request('date_to') || request('is_surprise_bag') != 'all')
                                    <span class="font-semibold">(Filtered)</span>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Items Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Item Name</th>
                                    <th class="px-4 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Item Type</th>
                                    <th class="px-4 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Business Name</th>
                                    <th class="px-4 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Commission</th>
                                    <th class="px-4 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse($items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        #{{ $item['id'] }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="font-medium">{{ $item['name'] }}</div>
                                        @if($item['is_surprise_bag'])
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                                Surprise Bag
                                            </span>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div>{{ $item['category'] }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item['sub_category'] }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="font-medium">{{ $item['user']['name'] ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item['user']['email'] ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="font-medium">{{ $item['commission'] ?? 0 }}%</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if($item['status'] == 'approved')
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Approved
                                        </span>
                                        @elseif($item['status'] == 'pending')
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                            Pending
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Rejected
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.item.show', $item['id']) }}"
                                            class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg shadow-sm transition duration-150 font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">No items found</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Try adjusting your filters or search query</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($items->hasPages())
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                        {{ $items->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>