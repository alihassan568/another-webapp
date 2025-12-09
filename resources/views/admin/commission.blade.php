<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Commission Settings') }}
        </h2>
        <a href="{{ url('/') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 ml-4">Dashboard</a>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 dark:border-blue-400 p-4 mb-6">
                    <h3 class="font-bold text-blue-700 dark:text-blue-300 text-lg mb-2">🧾 How Fees Work in the System</h3>
                    <ul class="list-disc pl-5 text-blue-700 dark:text-blue-300 text-sm mb-3">
                        <li><strong>Stripe Fees</strong> are paid by the Vendor, not the platform.</li>
                        <li>The system automatically calculates and adds the Stripe fee to your commission.</li>
                        <li>The total amount deducted from the Vendor includes:
                            <ul class="list-disc pl-5">
                                <li>Your Commission</li>
                                <li>Stripe Percentage Fee</li>
                                <li>Stripe Fixed Fee</li>
                                <li>(Optional) Tax</li>
                            </ul>
                        </li>
                    </ul>
                    <div class="mb-2">
                        <span class="font-semibold text-blue-800 dark:text-blue-200">Step-by-Step Breakdown:</span>
                        <ol class="list-decimal pl-5 text-blue-700 dark:text-blue-300 text-sm mt-2">
                            <li>Order is placed and customer pays the full amount.</li>
                            <li>Platform takes a percentage as commission.<br><span class="text-xs">(e.g., 10% commission on $100 order = $10.00)</span></li>
                            <li>Stripe charges a percentage fee (e.g., 2.9%) plus a fixed fee (e.g., $0.30) per transaction.<br><span class="text-xs">(e.g., 2.9% of $100 = $2.90; Fixed fee = $0.30; Total Stripe fee = $3.20)</span></li>
                            <li>Any configured tax percentage is also deducted.</li>
                            <li>Vendor receives the remaining amount after all deductions.</li>
                        </ol>
                    </div>
                    <div class="mb-2">
                        <span class="font-semibold text-blue-800 dark:text-blue-200">Calculation Example:</span>
                        <ul class="list-none pl-0 text-blue-700 dark:text-blue-300 text-sm mt-2">
                            <li><strong>Order Total:</strong> $100.00</li>
                            <li><strong>Platform Commission (10%):</strong> $10.00</li>
                            <li><strong>Stripe Fee (2.9% + $0.30):</strong> $2.90 + $0.30 = $3.20</li>
                            <li><strong>Tax:</strong> (if set, e.g., 0%)</li>
                            <li><strong>Vendor Receives:</strong> $100.00 - $10.00 - $3.20 = <span class="font-bold">$86.80</span></li>
                        </ul>
                    </div>
                    <div class="mb-2">
                        <span class="font-semibold text-blue-800 dark:text-blue-200">Key Points:</span>
                        <ul class="list-disc pl-5 text-blue-700 dark:text-blue-300 text-sm mt-2">
                            <li>✅ Platform always receives the full commission.</li>
                            <li>✅ Vendor pays Stripe fees and any tax.</li>
                            <li>✅ All values are configurable in the admin panel.</li>
                            <li>✅ No hidden fees for the platform.</li>
                            <li>✅ Transparent breakdown for every transaction.</li>
                        </ul>
                    </div>
                    <div class="mt-2 text-blue-800 dark:text-blue-200 text-sm">
                        <strong>Summary:</strong> The platform’s profit is protected. Vendors cover payment processing costs. All deductions are clear and configurable, ensuring fairness and transparency for both parties.
                    </div>
                </div>

                <form action="{{ url('/admin/commission-settings') }}" method="POST">
                    @csrf


                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Stripe Fee Percentage (%)</label>
                        <input type="number" step="0.01" name="stripe_fee_percentage"
                               value="{{ $settings->stripe_fee_percentage ?? 2.90 }}"
                               class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-100 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 dark:focus:border-blue-400">
                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Usually 2.9% for US cards.</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Stripe Fee Fixed ($)</label>
                        <input type="number" step="0.01" name="stripe_fee_fixed"
                               value="{{ $settings->stripe_fee_fixed ?? 0.30 }}"
                               class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-100 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 dark:focus:border-blue-400">
                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Usually $0.30 for US cards.</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Default Commission (%)</label>
                        <input type="number" step="0.01" name="default_commission"
                               value="{{ $settings->rate ?? 10.00 }}"
                               class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-100 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 dark:focus:border-blue-400">
                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Commission taken by platform (default 10%).</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Tax Percentage (%)</label>
                        <input type="number" step="0.01" name="tax_percentage"
                               value="{{ $settings->tax_percentage ?? 0.00 }}"
                               class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-100 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 dark:focus:border-blue-400">
                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Tax applied to the order total (optional).</p>
                    </div>


                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-colors duration-200">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
