<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait UsesJsonbMergePatch
{
    public static function bootUsesJsonbMergePatch()
    {
        static::updating(function ($model) {
            // Colonne JSONB da gestire
            $jsonbColumns = $model->jsonbColumns ?? [];

            foreach ($jsonbColumns as $column) {
                if ($model->isDirty($column)) {
                    $patch = $model->getDirty()[$column];

                    // Se è array, converti in JSON
                    if (is_array($patch)) {
                        $patch = json_encode($patch);
                    }

                    // Applica jsonb_merge_patch in SQL
                    DB::table($model->getTable())
                        ->where('id', $model->getKey())
                        ->update([
                            $column => DB::raw("jsonb_merge_patch($column, '$patch'::jsonb)")
                        ]);

                    // Evita che Eloquent sovrascriva il campo
                    $model->syncOriginalAttribute($column);
                }
            }
        });
    }
}