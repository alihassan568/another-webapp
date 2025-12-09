<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Role Management') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Manage user roles and permissions
                </p>
            </div>
            <div class="flex items-center space-x-2 mt-4 sm:mt-0">
                <a href="{{ route('admin.roles.create') }}" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-purple-600 hover:to-pink-600 text-white rounded-xl font-semibold transition-all duration-300 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Add New Role</span>
                </a>
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
                </div>
            </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4" role="alert">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-red-800 dark:text-red-200 font-medium">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Roles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($roles as $role)
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $role['name'] }}</h3>
                                @if(isset($role['role_property']['type']))
                                <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $role['role_property']['type'] }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                <a href="{{ route('admin.roles.show', $role['id']) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                                <a href="{{ route('admin.roles.edit', $role['id']) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Role
                                </a>
                                @if($role['name'] !== 'Super Admin')
                                <button type="button" 
                                        @click="open = false; $dispatch('open-delete-modal', { roleId: {{ $role['id'] }}, roleName: '{{ $role['name'] }}' })" 
                                        class="w-full flex items-center px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete Role
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Permissions Count -->
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-semibold">{{ count($role['permissions']) }}</span> permissions assigned
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-500">
                            Created {{ \Carbon\Carbon::parse($role['created_at'])->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Roles Found</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-center mb-4">Get started by creating your first role.</p>
                    <a href="{{ route('admin.roles.create') }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors">
                        Create Role
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ 
            showDeleteModal: false, 
            roleToDelete: null, 
            roleNameToDelete: '' 
        }"
        @open-delete-modal.window="showDeleteModal = true; roleToDelete = $event.detail.roleId; roleNameToDelete = $event.detail.roleName"
        x-show="showDeleteModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        
        <!-- Backdrop -->
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showDeleteModal = false"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <!-- Modal Content -->
            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white">
                            Delete Role
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Are you sure you want to delete the role <span class="font-semibold text-gray-900 dark:text-white" x-text="roleNameToDelete"></span>? This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                    <form :action="'/admin/roles/' + roleToDelete" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm transition-colors">
                            Delete
                        </button>
                    </form>
                    <button type="button" 
                            @click="showDeleteModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- AlpineJS for dropdowns and modals -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>