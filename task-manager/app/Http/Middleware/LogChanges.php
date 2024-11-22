<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as DebugLog;
use App\Models\Log;

class LogChanges
{
    public function handle(Request $request, Closure $next)
    {
        // if ($request->isMethod('put') || $request->isMethod('patch')) {
        //     // Fetch the entity being updated
        //     $entity = $request->route()->parameter('task') ?? $request->route()->parameter('comment');

        //     if ($entity) {
        //         // Log the original data before updating
        //         $originalData = $entity->getOriginal();

        //         // Capture validated input from the request
        //         $newData = $request->only(array_keys($originalData));

        //         // Compare and log changes
        //         foreach ($newData as $key => $newValue) {
        //             $oldValue = $originalData[$key] ?? null;

        //             if ($newValue !== $oldValue) {
        //                 Log::create([
        //                     'entity_type' => get_class($entity),
        //                     'entity_id' => $entity->id,
        //                     'field_changed' => $key,
        //                     'old_value' => $this->formatDateIfNeeded($oldValue),
        //                     'new_value' => $this->formatDateIfNeeded($newValue),
        //                 ]);
        //             }
        //         }
        //     }
        // }

        return $next($request);
    }

    private function formatDateIfNeeded($value)
    {
        if ($value instanceof \Carbon\Carbon) {
            return $value->format('Y-m-d H:i:s'); // Standardize the format
        }

        return $value;
    }

}

