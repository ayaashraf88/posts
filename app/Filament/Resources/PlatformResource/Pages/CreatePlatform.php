<?php

namespace App\Filament\Resources\PlatformResource\Pages;

use App\Filament\Resources\PlatformResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;

class CreatePlatform extends CreateRecord
{
    protected static string $resource = PlatformResource::class;
    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('type')->required()
                    ->maxLength(255),
            
            ]);
    }
}
