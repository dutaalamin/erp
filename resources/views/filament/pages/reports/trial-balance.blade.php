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

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Code</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Account Name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Type</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Debit</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Credit</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($accounts as $account)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-2 font-mono text-xs">{{ $account['code'] }}</td>
                        <td class="px-4 py-2">{{ $account['name'] }}</td>
                        <td class="px-4 py-2"><span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ ucfirst($account['type']) }}</span></td>
                        <td class="px-4 py-2 text-right font-mono">Rp {{ number_format($account['debit'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-mono">Rp {{ number_format($account['credit'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-mono font-semibold {{ $account['balance'] < 0 ? 'text-red-600' : '' }}">Rp {{ number_format($account['balance'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No data for selected period.</td></tr>
                    @endforelse
                </tbody>
                @if($accounts->isNotEmpty())
                <tfoot class="bg-gray-100 dark:bg-gray-700 font-bold">
                    <tr>
                        <td colspan="3" class="px-4 py-3">Total</td>
                        <td class="px-4 py-3 text-right font-mono">Rp {{ number_format($accounts->sum('debit'), 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-mono">Rp {{ number_format($accounts->sum('credit'), 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-mono">Rp {{ number_format($accounts->sum('balance'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-filament-panels::page>
