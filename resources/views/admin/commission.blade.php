<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray leading-tight">
            {{ __('Commission Settings') }}
        </h2>
        <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-gray-800 ml-4">Dashboard</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <h3 class="font-bold text-blue-700">How Fees Work</h3>
                    <p class="text-sm text-blue-600 mt-2">
                        <strong>Stripe Fees are now paid by the Vendor.</strong><br>
                        The system automatically adds the estimated Stripe Fee to your Commission.
                        This means the total amount deducted from the Vendor will be:
                        <br>
                        <code class="bg-blue-100 px-1 rounded">Your Commission + Stripe Percentage + Fixed Fee</code>
                    </p>
                    <p class="text-sm text-blue-600 mt-2">
                        Example: If Commission is 10%, Stripe Fee is 2.9% + $0.30, and Order is $100:<br>
                        - You keep: $10.00<br>
                        - Stripe takes: $3.20 ($2.90 + $0.30)<br>
                        - Vendor gets: $86.80<br>
                        <strong>You receive the full $10.00 profit without paying the fee.</strong>
                    </p>
                </div>

                <form action="{{ url('/admin/commission-settings') }}" method="POST">
                    @csrf


                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Stripe Fee Percentage (%)</label>
                        <input type="number" step="0.01" name="stripe_fee_percentage"
                               value="{{ $settings->stripe_fee_percentage ?? 2.90 }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-gray-500 text-xs mt-1">Usually 2.9% for US cards.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tax Percentage (%)</label>
                        <input type="number" step="0.01" name="tax_percentage"
                               value="{{ $settings->tax_percentage ?? 0.00 }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-gray-500 text-xs mt-1">Tax applied to the order total (optional).</p>
                    </div>


                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
