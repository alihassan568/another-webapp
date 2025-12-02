<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __(ucfirst($status).' '.'Items') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex justify-center mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="">
                <div class="max-w-7x4">
                    @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove();">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 5.652a1 1 0 10-1.414-1.414L10 7.586 7.066 4.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 12.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934z" />
                            </svg>
                        </span>
                    </div>
                    @endif

                    <section>

                        <!-- Search & Date Filters -->
                        <form method="POST" action="{{ route('admin.items.filter') }}" class="flex justify-center flex-wrap gap-4 mt-4 mb-6">
                            <!-- Search Box -->
                            @csrf
                            <input type="hidden" value="{{ $status }}" name="status">
                            <input
                                type="text"
                                name="search"
                                placeholder="Search by item name, business, or email"
                                value="{{ request('search') }}"
                                class="border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300 w-1/3">

                            <!-- Date From -->
                            <input
                                type="date"
                                name="date_from"
                                class="border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300">

                            <!-- Date To -->
                            <input
                                type="date"
                                name="date_to"
                                class="border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300">

                            <button
                                type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                Filter
                            </button>

                            <a
                                href="{{ route('admin.items').'?status=all' }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                Reset
                            </a>
                        </form>
                        <!-- End Filters -->

                        <div class="flex items-center gap-8 pt-5">
                            <div class="overflow-x-auto w-full px-4 sm:px-6 lg:px-8">
                                <table class="min-w-full bg-white rounded-lg shadow text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-3 py-2 border-b text-left">ID</th>
                                            <th class="px-3 py-2 border-b text-left">Image</th>
                                            <th class="px-3 py-2 border-b text-left">Business Name</th>
                                            <th class="px-3 py-2 border-b text-left">Email</th>
                                            <th class="px-3 py-2 border-b text-left">Item</th>
                                            <th class="px-3 py-2 border-b text-left">Category</th>
                                            <th class="px-3 py-2 border-b text-left">Sub-category</th>
                                            <th class="px-3 py-2 border-b text-left">Description</th>
                                            <th class="px-3 py-2 border-b text-left">Commission</th>
                                            <th class="px-3 py-2 border-b text-left">Commission Request</th>
                                            <th class="px-3 py-2 border-b text-left">Date</th>
                                            <th class="px-3 py-2 border-b text-left">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($items->count()!=0)
                                        @foreach($items as $d)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-2 border-b">{{ $d->id }}</td>
                                            <td class="px-3 py-2 border-b">
                                                <img src="{{ asset($d->image) }}" alt="img" class="w-10 h-10 rounded">
                                            </td>
                                            <td class="px-3 py-2 border-b">{{ $d->user->name ?? '' }}</td>
                                            <td class="px-3 py-2 border-b">{{ $d->user->email ?? '' }}</td>
                                            <td class="px-3 py-2 border-b">{{ $d->name }}</td>
                                            <td class="px-3 py-2 border-b">{{ $d->category }}</td>
                                            <td class="px-3 py-2 border-b">{{ $d->sub_category }}</td>
                                            <td class="px-3 py-2 border-b truncate max-w-xs">{{ $d->description }}</td>
                                            <td class="px-3 py-2 border-b">{{ $d->commission }}%</td>
                                            <td class="px-3 py-2 border-b">{{ ucfirst($d->commission_status) }}</td>
                                            <td class="px-3 py-2 border-b whitespace-nowrap">{{ $d->created_at }}</td>
                                            <td class="px-3 py-2 border-b">
                                                <div class="flex space-x-2">
                                                    @if($d->status == 'pending' || $d->status == 'rejected')
                                                    <a href="{{ route('admin.item.accept',$d->id) }}"
                                                        class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">
                                                        Accept
                                                    </a>
                                                    @endif
                                                    @if($d->status == 'pending' || $d->status == 'approved')
                                                    <a href="{{ route('admin.item.reject',$d->id) }}"
                                                        class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded">
                                                        Reject
                                                    </a>
                                                    <a href="{{ route('admin.item.commission',$d->id) }}"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">
                                                        Set Commission
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="12" class="text-center py-4 text-gray-500">
                                                No Data Found
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6 mb-4 flex justify-end">
                            {{ $items->links() }}
                        </div>
                    </section>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>