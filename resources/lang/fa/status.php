<?php

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;

return [
    StatusEnum::QUEUED->value => 'در صف',
    StatusEnum::IN_PROGRESS->value => 'در حال انجام',
    StatusEnum::FINISHED->value => 'انجام شده',
    StatusEnum::FAILED->value => 'ناموفق',
];
