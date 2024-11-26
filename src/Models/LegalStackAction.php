<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Models;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StackActions\TypeEnum;
use Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;
use Aliheidarian1984\QueueableStackActionsLegal\Models\Traits\HasStatus;
use Aliheidarian1984\QueueableStackActionsLegal\Support\Config;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LegalStackAction extends Model
{
    use HasStatus;

    protected $fillable = [
        'name',
        'type',
        'identifier',
        'status',
        'job',
        'team_id',
        'total_records',
        'data',
    ];

    protected $casts = [
        'type' => TypeEnum::class,
        'status' => StatusEnum::class,
        'total_records' => 'int',
        'data' => 'json',
        'dismissed_at' => 'datetime',
        'started_at' => 'datetime',
        'failed_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(Config::stackActionRecordModel(), 'legal_stack_action_id');
    }

    public function updateIfFinished(): void
    {
        $processedCount = $this->records()
            ->whereIn('status', [StatusEnum::FINISHED, StatusEnum::FAILED])
            ->count();

        if ($processedCount >= $this->total_records) {
            $this->updateStatus(StatusEnum::FINISHED);
        }
    }
}
