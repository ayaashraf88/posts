<?php

namespace App\Filament\Resources\PlatformResource\Pages;

use App\Filament\Resources\PlatformResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;

class EditPlatform extends EditRecord
{
    protected static string $resource = PlatformResource::class;
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['user_id'] = auth()->id();
    
        return $data;
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        
        $record->update($data);
    
        return $record;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->disabled(fn($record): bool => auth()->user()->is_admin!=1 ),

        ];
    }
   



    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('type')->required()
                    ->maxLength(255),
       

                ],
                    
                
                )->disabled(fn($record): bool => auth()->user()->is_admin!=1 );
                }
}
