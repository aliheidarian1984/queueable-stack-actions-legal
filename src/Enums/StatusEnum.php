<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Enums;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

enum StatusEnum: string
{
    case QUEUED = 'queued';
    case IN_PROGRESS = 'in-progress';
    case FINISHED = 'finished';
    case FAILED = 'failed';

    public function getLabel(): Stringable
    {
        return Str::of(__('queueable-stack-actions::status.' . $this->value));
    }
}
