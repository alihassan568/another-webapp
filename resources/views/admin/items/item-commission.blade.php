<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray dark:text-gray leading-tight">
            {{ __('Add Commission') }}
        </h2>
    </x-slot>

    <form method="post" action="{{ route('admin.item.commission',$id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
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
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Current Commission: <strong>{{ $item->commission ?? '0' }}%</strong>
                                </p>
                            </header>
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8">
                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <x-input-label for="commission" :value="__('Commission Percentage (%)')" />
                                            <input 
                                                type="number" 
                                                name="commission" 
                                                id="commission"
                                                step="0.01" 
                                                min="0" 
                                                max="100" 
                                                value="{{ old('commission', $item->commission ?? 0) }}"
                                                class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                required>
                                            <p class="mt-1 text-sm text-gray-500">Enter a value between 0 and 100 (decimals allowed)</p>
                                            @error('commission')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
  
                                </div>

                                <div class="flex items-center gap-4 justify-end pt-5">
                                    <x-primary-button>{{ __('Submit') }}</x-primary-button>
                                </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>