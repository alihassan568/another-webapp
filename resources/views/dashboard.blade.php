<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Product Card -->
                    <div class="bg-white rounded-lg p-4 shadow-md flex flex-col justify-between">
                        <div class="flex flex-col items-start">
                            <!-- Custom Product Image -->
                            <!-- <img src="{{ asset('storage/icons/product.png') }}" alt="Product Icon" class="h-4 mb-2" /> -->
                            <h2 class="text-[#F09436] font-bold text-lg">Total User</h2>
                        </div>
                        <p class="text-gray-800 text-2xl text-right mt-auto font-bold">{{ $totalUsers }}</p>
                    </div>

                    <!-- Projects Card -->
                    <div class="bg-white rounded-lg p-4 shadow-md flex flex-col justify-between">
                        <div class="flex flex-col items-start">
                            <!-- Custom Projects Image -->
                            <!-- <img src="{{ asset('storage/icons/project.png') }}" alt="Projects Icon" class="h-4 mb-2" /> -->
                            <h2 class="text-[#505050] font-bold text-lg">Total Venders</h2>
                        </div>
                        <p class="text-gray-800 text-2xl text-right mt-auto font-bold">{{$totalVenders}}</p>
                    </div>

                    <!-- Peoples Card -->
                    <div class="bg-white rounded-lg p-4 shadow-md flex flex-col justify-between">
                        <div class="flex flex-col items-start">
                            <!-- Custom People Image -->
                            <!-- <img src="{{ asset('storage/icons/people.png') }}" alt="People Icon" class="h-4 mb-2" /> -->
                            <h2 class="text-[#0084AB] font-bold text-lg">Accepted Items</h2>
                        </div>
                        <p class="text-gray-800 text-2xl text-right mt-auto font-bold">{{$acceptedItems}}</p>
                    </div>

                    <!-- Companies Card -->
                    <div class="bg-white rounded-lg p-4 shadow-md flex flex-col justify-between">
                        <div class="flex flex-col items-start">
                            <!-- Custom Companies Image -->
                            <!-- <img src="{{ asset('storage/icons/company.png') }}" alt="Companies Icon" class="h-4 mb-2" /> -->
                            <h2 class="text-[#F22613] font-bold text-lg font-bold">Pending Items</h2>
                        </div>
                        <p class="text-gray-800 text-2xl text-right mt-auto font-bold">{{$pendingItems}}</p>
                    </div>

                </div>
            </div>

            <div class="container mt-5">
                
            </div>
        </div>
    </div>
</x-app-layout>
