<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Support;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;
use Aliheidarian1984\QueueableStackActionsLegal\QueueableStackActionsPlugin;
use Filament\Facades\Filament;

class Config
{
    public static function isPluginRegister(): bool
    {
        return Filament::getCurrentPanel() && Filament::getCurrentPanel()->hasPlugin('queueable-stack-actions');
    }

    public static function stackActionModel(): string
    {
        if (Config::isPluginRegister()) {

            return QueueableStackActionsPlugin::get()->getStackActionModel() ?? config('queueable-stack-actions.models.stack_action');
        }

        return config('queueable-stack-actions.models.stack_action');
    }

    public static function stackActionRecordModel(): string
    {
        if (Config::isPluginRegister()) {
            return QueueableStackActionsPlugin::get()->getStackActionRecordModel() ?? config('queueable-stack-actions.models.stack_action_record');
        }

        return config('queueable-stack-actions.models.stack_action_record');
    }

    public static function renderHooks(): string | array
    {
        if (Config::isPluginRegister()) {
            return QueueableStackActionsPlugin::get()->getRenderHooks() ?? config('queueable-stack-actions.render_hook');
        }

        return config('queueable-stack-actions.render_hook');
    }

    public static function pollingInterval(): ?string
    {
        if (Config::isPluginRegister()) {
            return QueueableStackActionsPlugin::get()->getPollingInterval() ?? config('queueable-stack-actions.polling_interval');
        }

        return config('queueable-stack-actions.polling_interval');
    }

    public static function queueConnection(): string
    {
        if (Config::isPluginRegister()) {
            return QueueableStackActionsPlugin::get()->getQueueConnection() ?? config('queueable-stack-actions.queue.connection');
        }

        return config('queueable-stack-actions.queue.connection');
    }

    public static function queueName(): string
    {
        if (Config::isPluginRegister()) {
            return QueueableStackActionsPlugin::get()->getQueueName() ?? config('queueable-stack-actions.queue.queue');
        }

        return config('queueable-stack-actions.queue.queue');
    }

    public static function resource(): ?string
    {

        if (Config::isPluginRegister()) {

            return QueueableStackActionsPlugin::get()->getResource() ?? config('queueable-stack-actions.resource');
        }

        return config('queueable-stack-actions.resource');
    }

    public static function colors(): array
    {
        if (Config::isPluginRegister()) {
            return QueueableStackActionsPlugin::get()->getColors() ?? config('queueable-stack-actions.colors');
        }

        return config('queueable-stack-actions.colors');
    }

    public static function color(StatusEnum | string $status, string $default = 'sate'): string
    {
        $status = $status instanceof StatusEnum ? $status->value : $status;

        return Config::colors()[$status] ?? $default;
    }
}
