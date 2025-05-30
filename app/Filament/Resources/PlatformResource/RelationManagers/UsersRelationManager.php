<?php

namespace App\Filament\Resources\PlatformResource\RelationManagers;

use App\Models\PlatformUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static ?string $title = 'Activate Platform';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        // $data['platform_id'] = auth()->id();


        return $data;
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('Activate this platform ')
                    ->required(),
                Forms\Components\Hidden::make('platform_id')
                    ->default(function () {
                        $profileId = request()->route('platform_id');  // Get profile ID from current request, if it's defined in your routing, but perhaps you have to implement other kind of logic
                        return $profileId;
                    }),
                Forms\Components\Hidden::make('user_id')
                    ->default(function () {
                        $profileId = auth()->id();  // Get profile ID from current request, if it's defined in your routing, but perhaps you have to implement other kind of logic
                        return $profileId;
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        // dd(request()->route('platform_id'));
        return $table
            ->emptyStateHeading('No platform added yet')
            ->emptyStateDescription('Activate this platform')
            ->recordTitleAttribute('is_active')
            ->query(
                parent::table($table)
                    ->getQuery()
                    ->where('user_id', auth()->id())
            )
            ->columns([
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->filters([
                //
            ])

            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Activate this platform')
                    ->disableCreateAnother()
                    ->hidden(
                        fn() => $this->getOwnerRecord()->users()
                            ->where('platform_id', $this->getOwnerRecord()->id)
                            ->where('user_id',  auth()->id())
                            ->exists()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
            
            ]);
    }
}
