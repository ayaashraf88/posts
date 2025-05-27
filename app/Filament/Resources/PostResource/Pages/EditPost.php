<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log; 
use App\Jobs\UpdatePostState;
class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['user_id'] = auth()->id();
    
        return $data;
    }

protected function handleRecordUpdate(Model $record, array $data): Model
{
    if($data['scheduled_time']!=null){
        $data['status']="scheduled";
    }
    $record->update($data);

    return $record;
}
protected function afterSave(): void
{
    // --- Debugging Log: Confirm this line appears in storage/logs/laravel.log ---
    Log::info('EditPost: afterSave method is executing for Post ID: ' . $this->record->id);

    $scheduledTime = $this->record->scheduled_time;
    $postStatus = $this->record->status;


    if ($scheduledTime instanceof \Carbon\Carbon && $postStatus === 'scheduled') {
        Log::info('EditPost: Post is scheduled or re-scheduled. Dispatching UpdatePostState job for Post ID: ' . $this->record->id . ' with delay to ' . $scheduledTime->toDateTimeString());
        UpdatePostState::dispatch($this->record)
            ->delay($scheduledTime);
    }
    elseif ($postStatus !== 'scheduled' || !$scheduledTime) {
        Log::info('EditPost: Post ID: ' . $this->record->id . ' is no longer "scheduled" or has no schedule time. No job dispatched (or relies on job internal check).');
       
    }
}

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()
                    ->maxLength(100),
                Forms\Components\RichEditor::make('content')->required()
                    ->maxLength(255),
                    FileUpload::make('image_url')
                    ->image()
                    ->directory('user')
                    ->disk('user')
                    ->visibility('public')
                    ->openable()
                    ->downloadable()
                    ->preserveFilenames() 
                    ->imageResizeTargetWidth(800) 
                    ->imageResizeTargetHeight(800) 
                    ->uploadingMessage('Uploading image please wait...')
                    ->imagePreviewHeight('250'), 
                    Forms\Components\DatePicker::make('scheduled_time')
                    ->minDate(now()),
                Forms\Components\Select::make('platform')
                ->relationship('platforms', 'name', fn (Builder $query) => $query->whereHas('users', function ($query) {
                    $query->where('user_id', auth()->id())
                          ->where('is_active', true);
                }))
                ->searchable()
                ->preload()
                ->required(),
            ]);
    }
}
