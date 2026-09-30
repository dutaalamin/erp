<?php

namespace Database\Seeders;

use App\Models\UnitOfMeasure;
use Illuminate\Database\Seeder;

class UnitOfMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Unit type - base units (no conversion needed)
        $baseUnits = [
            ['code' => 'pcs', 'name' => 'Pieces', 'symbol' => 'pcs', 'type' => 'unit'],
            ['code' => 'unit', 'name' => 'Unit', 'symbol' => 'unit', 'type' => 'unit'],
            ['code' => 'pair', 'name' => 'Pair', 'symbol' => 'pr', 'type' => 'unit'],
            ['code' => 'dozen', 'name' => 'Dozen', 'symbol' => 'dz', 'type' => 'unit'],
            ['code' => 'set', 'name' => 'Set', 'symbol' => 'set', 'type' => 'unit'],
            ['code' => 'kg', 'name' => 'Kilogram', 'symbol' => 'kg', 'type' => 'weight'],
            ['code' => 'm', 'name' => 'Meter', 'symbol' => 'm', 'type' => 'length'],
            ['code' => 'l', 'name' => 'Liter', 'symbol' => 'l', 'type' => 'volume'],
            ['code' => 'hour', 'name' => 'Hour', 'symbol' => 'hr', 'type' => 'time'],
            ['code' => 'm2', 'name' => 'Square Meter', 'symbol' => 'm²', 'type' => 'area'],
        ];

        foreach ($baseUnits as $unit) {
            UnitOfMeasure::firstOrCreate(
                ['code' => $unit['code']],
                [
                    'name' => $unit['name'],
                    'symbol' => $unit['symbol'],
                    'type' => $unit['type'],
                    'base_unit_id' => null,
                    'conversion_factor' => 1.000000,
                    'is_active' => true,
                ]
            );
        }

        // Derived units with conversion factors
        $kg = UnitOfMeasure::where('code', 'kg')->first();
        $m = UnitOfMeasure::where('code', 'm')->first();
        $l = UnitOfMeasure::where('code', 'l')->first();
        $hour = UnitOfMeasure::where('code', 'hour')->first();

        $derivedUnits = [
            // Weight
            ['code' => 'g', 'name' => 'Gram', 'symbol' => 'g', 'type' => 'weight', 'base_unit_id' => $kg->id, 'conversion_factor' => 0.001000],
            ['code' => 'ton', 'name' => 'Ton', 'symbol' => 'ton', 'type' => 'weight', 'base_unit_id' => $kg->id, 'conversion_factor' => 1000.000000],
            // Length
            ['code' => 'cm', 'name' => 'Centimeter', 'symbol' => 'cm', 'type' => 'length', 'base_unit_id' => $m->id, 'conversion_factor' => 0.010000],
            ['code' => 'mm', 'name' => 'Millimeter', 'symbol' => 'mm', 'type' => 'length', 'base_unit_id' => $m->id, 'conversion_factor' => 0.001000],
            // Volume
            ['code' => 'ml', 'name' => 'Milliliter', 'symbol' => 'ml', 'type' => 'volume', 'base_unit_id' => $l->id, 'conversion_factor' => 0.001000],
            // Time
            ['code' => 'day', 'name' => 'Day', 'symbol' => 'day', 'type' => 'time', 'base_unit_id' => $hour->id, 'conversion_factor' => 24.000000],
        ];

        foreach ($derivedUnits as $unit) {
            UnitOfMeasure::firstOrCreate(
                ['code' => $unit['code']],
                [
                    'name' => $unit['name'],
                    'symbol' => $unit['symbol'],
                    'type' => $unit['type'],
                    'base_unit_id' => $unit['base_unit_id'],
                    'conversion_factor' => $unit['conversion_factor'],
                    'is_active' => true,
                ]
            );
        }
    }
}
