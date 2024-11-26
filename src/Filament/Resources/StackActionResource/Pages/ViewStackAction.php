<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Filament\Resources\StackActionResource\Pages;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;
use Aliheidarian1984\QueueableStackActionsLegal\Filament\Resources\StackActionResource;
use Aliheidarian1984\QueueableStackActionsLegal\Models\LegalStackActionRecord;
use Aliheidarian1984\QueueableStackActionsLegal\Support\Config;
use App\Models\LegalChequeAccept;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class ViewStackAction extends ListRecords
{
    use InteractsWithRecord;

    protected static string $resource = StackActionResource::class;

    protected static string $view = 'queueable-stack-actions::filament.resources.stack-action-resource.pages.view-stack-action';

    public function mount(): void
    {
        $this->record = $this->resolveRecord($this->record);
    }

    public function getModel(): string
    {
        return Config::stackActionRecordModel();
    }

    public function getTitle(): string|Htmlable
    {
        return $this->getRecord()->name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('record')
                    ->label('رکورد')
                    ->getStateUsing(fn(LegalStackActionRecord $record) => $record->record->name ?? $record->record_id),
                TextColumn::make('record')
                    ->label('شناسه صیادی')
                    ->getStateUsing(function (LegalStackActionRecord $record) {
                        $legalChequeAccept = LegalChequeAccept::find($record->record_id);
                        return $legalChequeAccept ? $legalChequeAccept->sayadid: 'نامشخص';
                    }),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->color(fn($state) => Config::color($state))
                    ->badge()
                    ->formatStateUsing(fn(StatusEnum $state) => $state->getLabel()),
                TextColumn::make('message')
                    ->label('پیام')
                    ->wrap()
                    ->placeholder('-'),
                TextColumn::make('started_at')
                    ->label('تاریخ شروع')
                    ->jalaliDateTime(),
                TextColumn::make('failed_at')
                    ->label('تاریخ شکست')
                    ->jalaliDateTime(),
                TextColumn::make('finished_at')
                    ->label('تاریخ اتمام')
                    ->jalaliDateTime(),
            ])
            ->defaultSort('created_at', 'acs')
            ->poll('10s')
            ->actions([
                Action::make('retry')
                    ->label('تلاش مجدد')
                    ->icon('heroicon-o-arrow-path')
                    ->iconButton()
                    ->tooltip('تلاش مجدد')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (LegalStackActionRecord $record) {
                        $record->status = StatusEnum::QUEUED;
                        $record->started_at = null;
                        $record->failed_at = null;
                        $record->save();
                        $this->record->job::dispatch($record);
                    })
                    ->visible(fn(LegalStackActionRecord $record) => $record->status == StatusEnum::FAILED),
            ])
            ->recordUrl(null);
    }

    protected function getTableQuery(): Builder
    {
        return Config::stackActionRecordModel()::query()
                     ->with(['record'])
                     ->where('legal_stack_action_id', $this->record->getKey())
                     ->orderBy('status');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
