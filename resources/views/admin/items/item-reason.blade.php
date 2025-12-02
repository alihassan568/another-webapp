<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Item Rejection Reason') }}
        </h2>
    </x-slot>

    <form method="post" action="{{ route('admin.item.reject',$id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-7xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-bold font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Item Rejection Reason') }}
                                </h2>
                            </header>
                                <input type="hidden" name="item_id" value="{{ $id }}">
                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <x-input-label for="password" :value="__('Reason')" />
                                            <textarea name="rejection_reason" id="" class="mt-1 block w-full h-32 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                            <x-input-error class="mt-2" :messages="$errors->get('reason')" />
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