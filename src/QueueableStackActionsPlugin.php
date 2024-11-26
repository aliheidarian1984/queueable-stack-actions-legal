<?php

namespace Aliheidarian1984\QueueableStackActionsLegal;

use Aliheidarian1984\QueueableStackActionsLegal\Filament\Resources\StackActionResource;
use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class QueueableStackActionsPlugin implements Plugin
{
    use EvaluatesClosures;

    protected string | Closure $stackActionModel;

    protected string | Closure $stackActionRecordModel;

    protected string | array | Closure $renderHook;

    protected string | bool | Closure | null $pollingInterval;

    protected string | Closure $queueConnection;

    protected string | Closure $queueName;

    protected string | bool | Closure | null $resource;

    protected array | Closure $colors;

    public function __construct()
    {
        $this->stackActionModel = config('queueable-stack-actions.models.stack_action');
        $this->stackActionRecordModel = config('queueable-stack-actions.models.stack_action_record');
        $this->renderHook = config('queueable-stack-actions.render_hook');
        $this->pollingInterval = config('queueable-stack-actions.polling_interval');
        $this->queueConnection = config('queueable-stack-actions.queue.connection');
        $this->queueName = config('queueable-stack-actions.queue.queue');
        $this->resource = config('queueable-stack-actions.resource');
        $this->colors = config('queueable-stack-actions.colors');
    }

    public function getId(): string
    {
        return 'filament-queueable-stack-actions';
    }

    public function register(Panel $panel): void
    {
        $renderHooks = $this->getRenderHooks();
        if (! is_array($renderHooks)) {
            $renderHooks = [$renderHooks];
        }

        foreach ($renderHooks as $renderHook) {
            FilamentView::registerRenderHook(
                $renderHook,
                fn (array $scopes): string => Blade::render('@livewire(\'queueable-stack-actions.stack-action-notifications\', [\'identifier\' => \'' . $scopes[0] . '\'])'),
            );
        }

        if ($this->getResource() == StackActionResource::class) {
            $panel->resources([
                $this->getResource(),
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function stackActionRecordTable(string | Closure $table): static
    {
        $this->stackActionRecordTable = $table;

        return $this;
    }

    public function getStackActionRecordTable(): string
    {
        return $this->evaluate($this->stackActionRecordTable);
    }

    public function stackActionModel(string | Closure $model): static
    {
        $this->stackActionModel = $model;

        return $this;
    }

    public function getStackActionModel(): string
    {
        return $this->evaluate($this->stackActionModel);
    }

    public function stackActionRecordModel(string | Closure $model): static
    {
        $this->stackActionRecordModel = $model;

        return $this;
    }

    public function getStackActionRecordModel(): string
    {
        return $this->evaluate($this->stackActionRecordModel);
    }

    public function renderHook(string | array | Closure $renderHook): static
    {
        $this->renderHook = $renderHook;

        return $this;
    }

    public function getRenderHooks(): array | string
    {
        return $this->evaluate($this->renderHook);
    }

    public function pollingInterval(string | bool | Closure $pollingInterval): static
    {
        $this->pollingInterval = $pollingInterval;

        return $this;
    }

    public function getPollingInterval(): string | bool | null
    {
        return $this->evaluate($this->pollingInterval);
    }

    public function queue(string | Closure $connection, string | Closure $queue = 'default'): static
    {
        $this->queueConnection = $connection;
        $this->queueName = $queue;

        return $this;
    }

    public function queueConnection(string | Closure $queueConnection): static
    {
        $this->queueConnection = $queueConnection;

        return $this;
    }

    public function getQueueConnection(): string
    {
        return $this->evaluate($this->queueConnection);
    }

    public function queueName(string | Closure $queueName): static
    {
        $this->queueName = $queueName;

        return $this;
    }

    public function getQueueName(): string
    {
        return $this->evaluate($this->queueName);
    }

    public function resource(string | bool | Closure $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function getResource(): string | bool | null
    {
        return $this->evaluate($this->resource);
    }

    public function colors(array | Closure $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    public function getColors(): array
    {
        return $this->evaluate($this->colors);
    }
}
