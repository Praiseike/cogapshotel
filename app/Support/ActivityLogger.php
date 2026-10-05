<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * System-wide audit trail. Logging must never break the request that
 * triggered it, so every failure is swallowed into the Laravel log.
 */
class ActivityLogger
{
    public static function log(
        string $action,
        ?Model $subject = null,
        array $properties = [],
        ?string $description = null,
    ): void {
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'subject_type' => $subject ? $subject->getMorphClass() : null,
                'subject_id' => $subject?->getKey(),
                'description' => $description,
                'properties' => $properties ?: null,
                'ip' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Activity log write failed: '.$e->getMessage(), ['action' => $action]);
        }
    }
}
