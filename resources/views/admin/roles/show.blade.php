<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Role Details') }}: {{ $role['name'] }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    View role information and assigned permissions
                </p>
            </div>
            <div class="flex items-center space-x-2 mt-4 sm:mt-0">
                <a href="{{ route('admin.roles.edit', $role['id']) }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Role</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-semibold transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Roles</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Role Information Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $role['name'] }}</h3>
                                @if(isset($role['role_property']['type']))
                                <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $role['role_property']['type'] }} Role</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Guard Name</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $role['guard_name'] }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Permissions</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ count($role['permissions']) }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Created</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($role['created_at'])->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Updated</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($role['updated_at'])->diffForHumans() }}</span>
                            </div>
                        </div>

                        @if($role['name'] === 'Super Admin')
                        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-blue-800 dark:text-blue-200 text-sm font-medium">Super Admin</p>
                                    <p class="text-blue-600 dark:text-blue-300 text-xs">Has all system permissions</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Permissions Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Assigned Permissions</h3>
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium">
                                {{ count($role['permissions']) }} permissions
                            </span>
                        </div>

                        @if(count($role['permissions']) > 0)
                            @php
                                $groupedPermissions = [];
                                foreach($role['permissions'] as $permission) {
                                    $parts = explode(' ', $permission['name']);
                                    $module = end($parts);
                                    $groupedPermissions[$module][] = $permission;
                                }
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($groupedPermissions as $module => $modulePermissions)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-white capitalize mb-4 flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                        {{ str_replace('_', ' ', $module) }}
                                        <span class="ml-2 px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full text-xs">
                                            {{ count($modulePermissions) }}
                                        </span>
                                    </h4>
                                    
                                    <div class="space-y-2">
                                        @foreach($modulePermissions as $permission)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ ucwords(str_replace(['_', '-'], ' ', $permission['name'])) }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Permissions Assigned</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-4">This role doesn't have any permissions assigned yet.</p>
                                <a href="{{ route('admin.roles.edit', $role['id']) }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors">
                                    Assign Permissions
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Role Actions -->
            <div class="mt-8 flex items-center justify-center space-x-4">
                <a href="{{ route('admin.roles.edit', $role['id']) }}" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-purple-600 hover:to-pink-600 text-white rounded-xl font-semibold transition-all duration-300 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit This Role</span>
                </a>
                
                @if($role['name'] !== 'Super Admin')
                <form action="{{ route('admin.roles.destroy', $role['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Delete Role</span>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>