<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Jobs;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;
use Aliheidarian1984\QueueableStackActionsLegal\Models\LegalStackAction;
use Aliheidarian1984\QueueableStackActionsLegal\Support\Config;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Throwable;

class StackActionSetupJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public function __construct(
        protected LegalStackAction $stackAction,
        protected Collection $records
    ) {
        $this->onConnection(Config::queueConnection());
        $this->onQueue(Config::queueName());
    }

    public function handle(): void
    {
        $this->stackAction->updateStatus(StatusEnum::IN_PROGRESS);

        $this->records->each(function ($record) {
            $stackActionRecord = $this->stackAction->records()->create([
                'record_id' => $record->getKey(),
                'record_type' => $record::class,
            ]);

            $this->stackAction->job::dispatch($stackActionRecord);
        });
    }

    public function failed(Throwable $e): void
    {
        $this->stackAction->updateStatus(StatusEnum::FAILED, $e->getMessage());
        $this->delete();
    }
}
