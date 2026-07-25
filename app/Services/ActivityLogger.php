<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Central place every module uses to record auditable actions.
 * Keeping it as a tiny service (instead of a model trait) means
 * controllers/services can log non-model events too (logins, exports...).
 */
class ActivityLogger
{
    public static function log(string $action, string $description, ?Model $subject = null): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'ip_address' => request()->ip(),
        ]);
    }
}
