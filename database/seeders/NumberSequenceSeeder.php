<?php

namespace Database\Seeders;

use App\Models\NumberSequence;
use Illuminate\Database\Seeder;

class NumberSequenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sequences = [
            ['type' => 'purchase_request', 'prefix' => 'PR', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'purchase_order', 'prefix' => 'PO', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'goods_receipt', 'prefix' => 'GR', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'purchase_invoice', 'prefix' => 'PINV', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'quotation', 'prefix' => 'QUO', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'sales_order', 'prefix' => 'SO', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'delivery_order', 'prefix' => 'DO', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'sales_invoice', 'prefix' => 'INV', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'stock_adjustment', 'prefix' => 'ADJ', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'stock_transfer', 'prefix' => 'TRF', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'payment', 'prefix' => 'PAY', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'journal_entry', 'prefix' => 'JE', 'pad_length' => 4, 'reset_period' => 'yearly'],
            ['type' => 'work_order', 'prefix' => 'WO', 'pad_length' => 4, 'reset_period' => 'yearly'],
        ];

        foreach ($sequences as $sequence) {
            NumberSequence::firstOrCreate(
                ['type' => $sequence['type']],
                $sequence
            );
        }
    }
}
