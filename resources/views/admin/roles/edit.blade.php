<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Edit Role') }}: {{ $role['name'] }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Update role information and permissions
                </p>
            </div>
            <div class="flex items-center space-x-2 mt-4 sm:mt-0">
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Error Messages -->
            @if ($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4" role="alert">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            There were {{ $errors->count() }} error{{ $errors->count() > 1 ? 's' : '' }} with your submission
                        </h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Role Edit Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                <form action="{{ route('admin.roles.update', $role['id']) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Role Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Role Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $role['name']) }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="Enter role name"
                                       {{ $role['name'] === 'Super Admin' ? 'readonly' : '' }}
                                       required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Guard Name -->
                            <div>
                                <label for="guard_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Guard Name
                                </label>
                                <select id="guard_name" 
                                        name="guard_name" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        {{ $role['name'] === 'Super Admin' ? 'disabled' : '' }}>
                                    <option value="web" {{ old('guard_name', $role['guard_name']) == 'web' ? 'selected' : '' }}>Web</option>
                                    <option value="api" {{ old('guard_name', $role['guard_name']) == 'api' ? 'selected' : '' }}>API</option>
                                </select>
                                @error('guard_name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Role Type -->
                        <div class="mt-6">
                            <label for="type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Role Type <span class="text-red-500">*</span>
                            </label>
                            <select id="type" 
                                    name="type" 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    {{ $role['name'] === 'Super Admin' ? 'disabled' : '' }}
                                    required>
                                <option value="">Select role type</option>
                                <option value="internal" {{ old('type', $role['roleProperty']['type'] ?? '') == 'internal' ? 'selected' : '' }}>Internal</option>
                                <option value="external" {{ old('type', $role['roleProperty']['type'] ?? '') == 'external' ? 'selected' : '' }}>External</option>
                            </select>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Internal roles are for staff/admin users. External roles are for vendors/customers.
                            </p>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($role['name'] === 'Super Admin')
                        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-blue-800 dark:text-blue-200 text-sm">
                                    Super Admin role cannot be renamed or have guard changed for security reasons.
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Permissions Section -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Permissions</h3>
                            @if($role['name'] !== 'Super Admin')
                            <div class="flex space-x-2">
                                <button type="button" onclick="selectAllPermissions()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition-colors">
                                    Select All
                                </button>
                                <button type="button" onclick="deselectAllPermissions()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition-colors">
                                    Deselect All
                                </button>
                            </div>
                            @endif
                        </div>

                        @if($role['name'] === 'Super Admin')
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6 text-center">
                            <svg class="w-12 h-12 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-200 mb-2">Super Admin Privileges</h3>
                            <p class="text-yellow-600 dark:text-yellow-300">
                                Super Admin has all permissions by default. Permissions cannot be modified for this role.
                            </p>
                        </div>
                        @elseif($permissions && count($permissions) > 0)
                            @php
                                $currentPermissionNames = collect($role['permissions'])->pluck('name')->toArray();
                                $groupedPermissions = [];
                                foreach($permissions as $permission) {
                                    $parts = explode(' ', $permission['name']);
                                    $module = end($parts);
                                    $groupedPermissions[$module][] = $permission;
                                }
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($groupedPermissions as $module => $modulePermissions)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="font-semibold text-gray-900 dark:text-white capitalize">
                                            {{ str_replace('_', ' ', $module) }}
                                        </h4>
                                        <div class="flex space-x-1">
                                            <button type="button" onclick="selectModulePermissions('{{ $module }}')" class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-xs hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                                All
                                            </button>
                                            <button type="button" onclick="deselectModulePermissions('{{ $module }}')" class="px-2 py-1 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded text-xs hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors">
                                                None
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        @foreach($modulePermissions as $permission)
                                        <label class="flex items-center group cursor-pointer">
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permission['name'] }}"
                                                   data-module="{{ $module }}"
                                                   class="w-4 h-4 text-blue-600 bg-white dark:bg-gray-600 border-gray-300 dark:border-gray-500 rounded focus:ring-blue-500 focus:ring-2 transition-all"
                                                   {{ in_array($permission['name'], old('permissions', $currentPermissionNames)) ? 'checked' : '' }}>
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                                {{ ucwords(str_replace(['_', '-'], ' ', $permission['name'])) }}
                                            </span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6 text-center">
                                <svg class="w-12 h-12 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-200 mb-2">No Permissions Available</h3>
                                <p class="text-yellow-600 dark:text-yellow-300">
                                    No permissions found in the system. Please create some permissions first.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.roles.index') }}" class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-purple-600 hover:to-pink-600 text-white rounded-xl font-semibold transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Update Role</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Permission Management -->
    <script>
        function selectAllPermissions() {
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = true);
        }

        function deselectAllPermissions() {
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        }

        function selectModulePermissions(module) {
            const checkboxes = document.querySelectorAll(`input[data-module="${module}"]`);
            checkboxes.forEach(checkbox => checkbox.checked = true);
        }

        function deselectModulePermissions(module) {
            const checkboxes = document.querySelectorAll(`input[data-module="${module}"]`);
            checkboxes.forEach(checkbox => checkbox.checked = false);
        }
    </script>
</x-app-layout>