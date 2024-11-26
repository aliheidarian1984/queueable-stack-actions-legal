<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Models;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;
use Aliheidarian1984\QueueableStackActionsLegal\Models\Traits\HasStatus;
use Aliheidarian1984\QueueableStackActionsLegal\Support\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LegalStackActionRecord extends Model
{
    use HasStatus;

    protected $fillable = [
        'legal_stack_action_id',
        'record_id',
        'record_type',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
        'started_at' => 'datetime',
        'failed_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function stackAction(): BelongsTo
    {
        return $this->belongsTo(Config::stackActionModel(), 'legal_stack_action_id');
    }

    public function record(): MorphTo
    {
        return $this->morphTo(type: 'record_type', id: 'record_id');
    }
}
