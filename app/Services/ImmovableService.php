<?php

namespace App\Services;

use App\Models\Immovable;

class ImmovableService
{
    public function getByStatus(string $status, array $additionalConditions = []): mixed
    {
        $query = Immovable::where('status', $status);

        foreach ($additionalConditions as $condition) {
            $query->where($condition['field'], $condition['operator'], $condition['value']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
