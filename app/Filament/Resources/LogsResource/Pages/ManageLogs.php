<?php

namespace App\Filament\Resources\LogsResource\Pages;

use App\Filament\Resources\LogsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Spatie\Activitylog\Models\Activity;
use Filament\Tables\Table;
use Filament\Tables;
class ManageLogs extends ManageRecords
{
    protected static string $resource = LogsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('description'),
            Tables\Columns\TextColumn::make('subject_type'),
            Tables\Columns\TextColumn::make('causer.name'),
        ]);
    }
}
