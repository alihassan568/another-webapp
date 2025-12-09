<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.activity-logs.index') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Activity Logs for {{ $admin->name }}</h1>
        </div>
        <p class="text-gray-600 dark:text-gray-400 ml-9">{{ $admin->email }}</p>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <!-- Admin Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Activities</p>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $logs->total() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Role</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $admin->roles->first()->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Member Since</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $admin->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Activity Logs Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Details</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div>{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $actionColors = [
                                            'approve_item' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'reject_item' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'set_commission' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            'invite_user' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                            'create_role' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'update_role' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                        ];
                                        $colorClass = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                        {{ str_replace('_', ' ', ucwords($log->action, '_')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $log->description }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($log->metadata)
                                        <button onclick="showMetadata({{ json_encode($log->metadata) }})" 
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            View Details
                                        </button>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <p class="text-lg font-medium">No activities found for this admin</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
                <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Metadata Modal -->
    <div id="metadataModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[80vh] overflow-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Activity Details</h3>
                    <button onclick="closeMetadata()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <pre id="metadataContent" class="bg-gray-100 dark:bg-gray-900 rounded-md p-4 text-sm text-gray-800 dark:text-gray-200 overflow-auto"></pre>
            </div>
        </div>
    </div>
    <script>
    function showMetadata(metadata) {
        document.getElementById('metadataContent').textContent = JSON.stringify(metadata, null, 2);
        document.getElementById('metadataModal').classList.remove('hidden');
    }
    function closeMetadata() {
        document.getElementById('metadataModal').classList.add('hidden');
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMetadata();
    });
    document.getElementById('metadataModal').addEventListener('click', function(e) {
        if (e.target === this) closeMetadata();
    });
    </script>
</x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.activity-logs.index') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Activity Logs for {{ $admin->name }}</h1>
        </div>
        <p class="text-gray-600 dark:text-gray-400 ml-9">{{ $admin->email }}</p>
    </div>

    <!-- Admin Info Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Activities</p>
                <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $logs->total() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Role</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $admin->roles->first()->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Member Since</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $admin->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div>{{ $log->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $actionColors = [
                                        'approve_item' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'reject_item' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        'set_commission' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'invite_user' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                        'create_role' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'update_role' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                    ];
                                    $colorClass = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                    {{ str_replace('_', ' ', ucwords($log->action, '_')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                {{ $log->description }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($log->metadata)
                                    <button onclick="showMetadata({{ json_encode($log->metadata) }})" 
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        View Details
                                    </button>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <p class="text-lg font-medium">No activities found for this admin</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Metadata Modal -->
<div id="metadataModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[80vh] overflow-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Activity Details</h3>
                <button onclick="closeMetadata()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <pre id="metadataContent" class="bg-gray-100 dark:bg-gray-900 rounded-md p-4 text-sm text-gray-800 dark:text-gray-200 overflow-auto"></pre>
        </div>
    </div>
</div>

<script>
function showMetadata(metadata) {
    document.getElementById('metadataContent').textContent = JSON.stringify(metadata, null, 2);
    document.getElementById('metadataModal').classList.remove('hidden');
}

function closeMetadata() {
    document.getElementById('metadataModal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMetadata();
});

document.getElementById('metadataModal').addEventListener('click', function(e) {
    if (e.target === this) closeMetadata();
});
</script>
@endsection
