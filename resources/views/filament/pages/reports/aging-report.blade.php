<x-filament-panels::page>
    <div class="space-y-4">
        <div class="flex gap-4 items-end">
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">As of Date</label>
                <input type="date" wire:model="asOfDate" class="block w-full rounded-lg border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <x-filament::button wire:click="generateReport">
                Generate
            </x-filament::button>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="text-xs text-gray-500 dark:text-gray-400">Current</div>
                <div class="text-lg font-bold text-green-600">Rp {{ number_format($summary['current'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="text-xs text-gray-500 dark:text-gray-400">1-30 Days</div>
                <div class="text-lg font-bold text-yellow-600">Rp {{ number_format($summary['1_30'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="text-xs text-gray-500 dark:text-gray-400">31-60 Days</div>
                <div class="text-lg font-bold text-orange-600">Rp {{ number_format($summary['31_60'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="text-xs text-gray-500 dark:text-gray-400">61-90 Days</div>
                <div class="text-lg font-bold text-red-500">Rp {{ number_format($summary['61_90'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="text-xs text-gray-500 dark:text-gray-400">90+ Days</div>
                <div class="text-lg font-bold text-red-700">Rp {{ number_format($summary['90_plus'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border-2 border-gray-300 dark:border-gray-600">
                <div class="text-xs text-gray-500 dark:text-gray-400">Total Outstanding</div>
                <div class="text-lg font-bold text-gray-900 dark:text-white">Rp {{ number_format($summary['total'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Detail Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Invoice #</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Customer</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Invoice Date</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Due Date</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Total</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Paid</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Outstanding</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-600 dark:text-gray-300">Days Overdue</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-600 dark:text-gray-300">Bucket</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-2 font-mono text-xs">{{ $invoice['invoice_number'] }}</td>
                        <td class="px-4 py-2">{{ $invoice['customer'] }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($invoice['invoice_date'])->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($invoice['due_date'])->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-right font-mono">Rp {{ number_format($invoice['total_amount'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-mono">Rp {{ number_format($invoice['paid_amount'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-mono font-semibold">Rp {{ number_format($invoice['outstanding'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center">
                            @if($invoice['days_overdue'] > 0)
                                <span class="font-semibold {{ $invoice['days_overdue'] > 90 ? 'text-red-700' : ($invoice['days_overdue'] > 60 ? 'text-red-500' : ($invoice['days_overdue'] > 30 ? 'text-orange-600' : 'text-yellow-600')) }}">
                                    {{ $invoice['days_overdue'] }}
                                </span>
                            @else
                                <span class="text-green-600">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            @php
                                $bucketLabels = [
                                    'current' => ['Current', 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'],
                                    '1_30' => ['1-30', 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'],
                                    '31_60' => ['31-60', 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200'],
                                    '61_90' => ['61-90', 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'],
                                    '90_plus' => ['90+', 'bg-red-200 text-red-900 dark:bg-red-800 dark:text-red-100'],
                                ];
                                $label = $bucketLabels[$invoice['bucket']] ?? ['?', 'bg-gray-100 text-gray-800'];
                            @endphp
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $label[1] }}">{{ $label[0] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-4 py-8 text-center text-gray-500">No unpaid invoices found.</td></tr>
                    @endforelse
                </tbody>
                @if($invoices->isNotEmpty())
                <tfoot class="bg-gray-100 dark:bg-gray-700 font-bold">
                    <tr>
                        <td colspan="4" class="px-4 py-3">Total</td>
                        <td class="px-4 py-3 text-right font-mono">Rp {{ number_format($invoices->sum('total_amount'), 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-mono">Rp {{ number_format($invoices->sum('paid_amount'), 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-mono">Rp {{ number_format($invoices->sum('outstanding'), 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-filament-panels::page>
