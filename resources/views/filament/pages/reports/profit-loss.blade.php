<x-filament-panels::page>
    <div class="space-y-4">
        <div class="flex gap-4 items-end">
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                <input type="date" wire:model="startDate" class="block w-full rounded-lg border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                <input type="date" wire:model="endDate" class="block w-full rounded-lg border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <x-filament::button wire:click="generateReport">
                Generate
            </x-filament::button>
        </div>

        {{-- Revenue Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <div class="px-4 py-3 bg-green-50 dark:bg-green-900/30 border-b border-green-200 dark:border-green-800">
                <h3 class="text-lg font-semibold text-green-800 dark:text-green-200">Revenue</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Code</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Account Name</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($revenueAccounts as $account)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-2 font-mono text-xs">{{ $account['code'] }}</td>
                        <td class="px-4 py-2">{{ $account['name'] }}</td>
                        <td class="px-4 py-2 text-right font-mono">Rp {{ number_format($account['balance'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">No revenue data.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-green-50 dark:bg-green-900/20 font-bold">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-green-800 dark:text-green-200">Total Revenue</td>
                        <td class="px-4 py-3 text-right font-mono text-green-800 dark:text-green-200">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Expense Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <div class="px-4 py-3 bg-red-50 dark:bg-red-900/30 border-b border-red-200 dark:border-red-800">
                <h3 class="text-lg font-semibold text-red-800 dark:text-red-200">Expenses</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Code</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Account Name</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($expenseAccounts as $account)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-2 font-mono text-xs">{{ $account['code'] }}</td>
                        <td class="px-4 py-2">{{ $account['name'] }}</td>
                        <td class="px-4 py-2 text-right font-mono">Rp {{ number_format($account['balance'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">No expense data.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-red-50 dark:bg-red-900/20 font-bold">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-red-800 dark:text-red-200">Total Expenses</td>
                        <td class="px-4 py-3 text-right font-mono text-red-800 dark:text-red-200">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Net Profit/Loss Summary --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 flex justify-between items-center {{ $netProfitLoss >= 0 ? 'bg-green-100 dark:bg-green-900/40' : 'bg-red-100 dark:bg-red-900/40' }}">
                <span class="text-lg font-bold {{ $netProfitLoss >= 0 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                    {{ $netProfitLoss >= 0 ? 'Net Profit' : 'Net Loss' }}
                </span>
                <span class="text-2xl font-bold font-mono {{ $netProfitLoss >= 0 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                    Rp {{ number_format(abs($netProfitLoss), 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>
</x-filament-panels::page>
