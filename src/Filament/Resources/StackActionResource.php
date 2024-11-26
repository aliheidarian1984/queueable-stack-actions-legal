<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Filament\Resources;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;
use Aliheidarian1984\QueueableStackActionsLegal\Filament\Resources\StackActionResource\Pages;
use Aliheidarian1984\QueueableStackActionsLegal\Support\Config;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StackActionResource extends Resource
{

    protected static ?string $navigationGroup = 'مدیریت دسته جمعی چک ها';
    protected static ?int $navigationSort = 23;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getPluralModelLabel(): string
    {
        return __('queueable-stack-actions::resource.plural_label');
    }

    public static function getModel(): string
    {
        return Config::stackActionModel();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('شناسه عملیات'),
                TextColumn::make('name')
                    ->label('نام'),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->color(fn($state) => Config::color($state))
                    ->badge()
                    ->formatStateUsing(fn(StatusEnum $state) => $state->getLabel()),
                TextColumn::make('message')
                    ->label('پیام')
                    ->wrap()
                    ->placeholder('-'),
                TextColumn::make('total_records')
                    ->label('تعداد کل رکوردها'),
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
            ->defaultSort('created_at', 'desc');

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStackActions::route('/'),
            'view' => Pages\ViewStackAction::route('/{record}'),
        ];
    }

    public function register(Panel $panel): void
    {
        if (Config::resource() != config('queueable-stack-actions-legal.model')) {
            $panel->resources([
                StackActionResource::class,
            ]);
        }
    }
}
