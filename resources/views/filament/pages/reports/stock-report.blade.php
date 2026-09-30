<x-filament-panels::page>
    <div class="space-y-4">
        <div class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Search Product</label>
                <input type="text" wire:model="search" placeholder="Search by code or name..." class="block w-full rounded-lg border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <x-filament::button wire:click="generateReport">
                Generate
            </x-filament::button>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Products</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stockData->count() }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Stock Value</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($stockData->sum('value'), 0, ',', '.') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Quantity</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stockData->sum('total_qty'), 0, ',', '.') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="text-sm text-red-500">Low Stock Items</div>
                <div class="text-2xl font-bold text-red-600">{{ $stockData->where('is_low', true)->count() }}</div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Code</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Product Name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Type</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-600 dark:text-gray-300">Unit</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Quantity</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Reserved</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Available</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Min. Stock</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($stockData as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ $item['is_low'] ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                        <td class="px-4 py-2 font-mono text-xs">{{ $item['code'] }}</td>
                        <td class="px-4 py-2">
                            {{ $item['name'] }}
                            @if($item['is_low'])
                                <span class="ml-1 px-1.5 py-0.5 text-xs rounded bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">LOW</span>
                            @endif
                        </td>
                        <td class="px-4 py-2"><span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ ucfirst($item['type']) }}</span></td>
                        <td class="px-4 py-2 text-center">{{ $item['unit'] }}</td>
                        <td class="px-4 py-2 text-right font-mono">{{ number_format($item['total_qty'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-mono">{{ number_format($item['reserved'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-mono font-semibold">{{ number_format($item['available'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-mono text-gray-500">{{ number_format($item['minimum_stock'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-mono">Rp {{ number_format($item['value'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-4 py-8 text-center text-gray-500">No stock data found.</td></tr>
                    @endforelse
                </tbody>
                @if($stockData->isNotEmpty())
                <tfoot class="bg-gray-100 dark:bg-gray-700 font-bold">
                    <tr>
                        <td colspan="4" class="px-4 py-3">Total</td>
                        <td class="px-4 py-3 text-right font-mono">{{ number_format($stockData->sum('total_qty'), 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-mono">{{ number_format($stockData->sum('reserved'), 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-mono">{{ number_format($stockData->sum('available'), 0, ',', '.') }}</td>
                        <td class="px-4 py-3"></td>
                        <td class="px-4 py-3 text-right font-mono">Rp {{ number_format($stockData->sum('value'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-filament-panels::page>
