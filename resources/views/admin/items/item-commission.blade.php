<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray dark:text-gray leading-tight">
            {{ __('Add Commission') }}
        </h2>
    </x-slot>

    <form method="post" action="{{ route('admin.item.commission', $item->id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-7xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-bold font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Set Commission for: ') }} {{ $item->name }}
                                </h2>
                            </header>

                            <!-- Current Commission Info Box -->
                            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Current Commission Rate</p>
                                        <p class="mt-1 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ $item->commission ?? '0' }}%</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-blue-700 dark:text-blue-400">Status</p>
                                        <p class="mt-1">
                                            @if($item->commission_status == 'approved')
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                ✓ Approved
                                            </span>
                                            @elseif($item->commission_status == 'rejected')
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                ✕ Rejected
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                ⏳ Pending
                                            </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-8">
                                <div class="sm:col-span-3">
                                    <div class="mt-2">
                                        <x-input-label class="block mb-2 text-gray-700 dark:text-gray-300 font-semibold" for="commission" :value="__('New Commission Percentage (%)')" />
                                        <input 
                                            type="number" 
                                            name="commission" 
                                            id="commission"
                                            step="0.01" 
                                            min="0" 
                                            max="100" 
                                            value="{{ old('commission', $item->commission ?? 0) }}"
                                            class="block w-full px-4 py-3 text-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 rounded-lg shadow-sm"
                                            required
                                            placeholder="Enter commission rate">
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Enter a value between 0 and 100 (decimals allowed). This will update the commission rate for this item.
                                        </p>
                                        @error('commission')
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
  
                            </div>

                                <div class="flex items-center gap-4 justify-end pt-5">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 dark:bg-blue-500 dark:hover:bg-blue-600 dark:active:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg active:shadow-sm transform hover:-translate-y-0.5 active:translate-y-0 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ __('Submit Commission') }}
                                    </button>
                                </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>