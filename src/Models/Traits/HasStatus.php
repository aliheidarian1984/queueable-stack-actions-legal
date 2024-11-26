<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Models\Traits;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;

trait HasStatus
{
    public function updateStatus(StatusEnum $status, ?string $message = null): void
    {
        $this->status = $status;
        $this->message = $message;

        $timestamp = match ($status) {
            StatusEnum::IN_PROGRESS => 'started_at',
            StatusEnum::FINISHED => 'finished_at',
            StatusEnum::FAILED => 'failed_at',
            default => null
        };

        if ($timestamp) {
            $this->{$timestamp} = now();
        }
        $this->save();
    }
}
