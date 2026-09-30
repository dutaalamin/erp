<?php

namespace App\Traits;

use App\Services\NumberSequenceService;

trait HasAutoNumber
{
    public static function bootHasAutoNumber(): void
    {
        static::creating(function ($model) {
            $field = $model->getNumberField();
            $type = $model->getNumberType();

            if (empty($model->{$field})) {
                $model->{$field} = NumberSequenceService::generate($type);
            }
        });
    }

    abstract protected function getNumberField(): string;
    abstract protected function getNumberType(): string;
}
