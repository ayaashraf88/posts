<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Guava\Calendar\ValueObjects\CalendarEvent;
use App\Jobs\UpdatePostState;
use App\Models\Post;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Log; 

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
    public function afterCreate(): void
    {
        Log::info('afterSave method is executing for Post ID: ' . $this->record->id);

        $scheduledTime = $this->record->scheduled_time; 
        $postStatus = $this->record->status;

        if ($scheduledTime instanceof \Carbon\Carbon && $postStatus === 'scheduled') {
            Log::info('Dispatching UpdatePostState job for Post ID: ' . $this->record->id . ' with delay to ' . $scheduledTime->toDateTimeString());
            UpdatePostState::dispatch($this->record)
                ->delay($scheduledTime); 
        } else {
            Log::warning('Job not dispatched for Post ID: ' . $this->record->id . '. Status: ' . $postStatus . ', Scheduled Time: ' . ($scheduledTime ? $scheduledTime->toDateTimeString() : 'NULL'));
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['user_id'] = auth()->id();
    if($data['scheduled_time']!=null){
        $data['status']="scheduled";
    }
  
    return $data;
}

    public  function form(Form $form): Form
    {           

        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()
                    ->maxLength(100)
                  ,
                Forms\Components\RichEditor::make('content')->required()
                    ->maxLength(255),
                    FileUpload::make('image_url')
                    ->image()
                    ->openable()
                    ->disk('user')
                    ->visibility('public') // Set visibility during upload
                    ->uploadingMessage('Uploading image please wait...'),
                    Forms\Components\DateTimePicker::make('scheduled_time')
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
