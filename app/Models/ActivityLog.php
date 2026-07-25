<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Icon + color pair for the dashboard activity feed, keyed by action.
     *
     * @return array{icon: string, class: string}
     */
    public function presenter(): array
    {
        return match ($this->action) {
            'created' => ['icon' => 'fa-plus', 'class' => 'success'],
            'updated' => ['icon' => 'fa-pen', 'class' => 'info'],
            'deleted' => ['icon' => 'fa-trash', 'class' => 'danger'],
            'restored' => ['icon' => 'fa-rotate-left', 'class' => 'warning'],
            'logged_in' => ['icon' => 'fa-right-to-bracket', 'class' => 'primary'],
            'registered' => ['icon' => 'fa-user-plus', 'class' => 'success'],
            default => ['icon' => 'fa-circle-info', 'class' => 'secondary'],
        };
    }
}
