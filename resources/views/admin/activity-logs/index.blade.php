<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Admin Activity Logs</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Track and monitor all admin activities in the system</p>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
                        <!-- Filters -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Admin User</label>
                                    <select name="admin_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">All Admins</option>
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }} ({{ $admin->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Action Type</label>
                                    <select name="action" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">All Actions</option>
                                        @foreach($actions as $key => $label)
                                            <option value="{{ $key }}" {{ request('action') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div class="md:col-span-4 flex gap-3">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium transition">
                                        Apply Filters
                                    </button>
                                    <a href="{{ route('admin.activity-logs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md font-medium transition">
                                        Clear Filters
                                    </a>
                                </div>
                            </form>
                        </div>

                        <!-- Activity Logs Table -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Timestamp</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Admin</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Invited By</th>
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
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->adminUser->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->adminUser->email }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    @if($log->invitedBy)
                                                        <div class="text-sm">{{ $log->invitedBy->name }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->invitedBy->email }}</div>
                                                    @else
                                                        <span class="text-gray-400 dark:text-gray-500 text-sm">Direct Admin</span>
                                                    @endif
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
                                                    @endphp
                                                    <span class="px-2 py-1 rounded {{ $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                                        {{ $actions[$log->action] ?? $log->action }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $log->description }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 dark:text-indigo-400">
                                                    <button type="button" onclick="showMetadata({{ json_encode($log->metadata) }})" class="underline hover:text-indigo-800">View</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No activity logs found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($logs->hasPages())
                                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
                                    {{ $logs->links() }}
                                </div>
                            @endif
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
                    </div>
    </div>
</x-app-layout>
