<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Jobs;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;
use Aliheidarian1984\QueueableStackActionsLegal\Filament\Actions\ActionResponse;
use Aliheidarian1984\QueueableStackActionsLegal\Models\LegalStackActionRecord;
use Aliheidarian1984\QueueableStackActionsLegal\Support\Config;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

abstract class StackActionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public function __construct(
        protected LegalStackActionRecord $stackActionRecord,
    ) {
        $this->onConnection(Config::queueConnection());
        $this->onQueue(Config::queueName());
    }

    public function handle(): void
    {
        $this->stackActionRecord->updateStatus(StatusEnum::IN_PROGRESS);

        $response = $this->action($this->stackActionRecord->record, $this->stackActionRecord->stackAction->data);

        $this->stackActionRecord->updateStatus($response->isSuccess() ? StatusEnum::FINISHED : StatusEnum::FAILED, $response->getMessage());
        $this->stackActionRecord->stackAction->updateIfFinished();
    }

    abstract protected function action($record, ?array $data): ActionResponse;

    public function failed(Throwable $e): void
    {
        $this->stackActionRecord->updateStatus(StatusEnum::FAILED, $e->getMessage());
        $this->stackActionRecord->stackAction->updateIfFinished();
        $this->delete();
    }
}
