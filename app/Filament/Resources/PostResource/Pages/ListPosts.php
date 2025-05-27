<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function table(Table $table): Table
    {
        
        return $table
        ->query(
            Post::query()->with('platforms') // Eager load platforms
        )
        ->columns([
            Tables\Columns\TextColumn::make('title')->searchable()->wrap(),
            Tables\Columns\TextColumn::make('content')->wrap(),
            Tables\Columns\TextColumn::make('scheduled_time')->sortable(),
            Tables\Columns\ImageColumn::make('image_url')
            ->state(function ($record) {
                return url('/uploads/user/' . $record->image_url);
            })
            ->circular(),
            Tables\Columns\TextColumn::make('platforms.name')
            ->label('Platforms')
            ->badge()
            ->color('primary'),
        ]);
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'draft' => Tab::make('Drafts')->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'draft'))->badge(Post::query()->where('status', 'draft')->where('user_id', auth()->id())->count())->badgeColor('info'),
            'scheduled' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'scheduled'))->badge(Post::query()->where('status', 'scheduled')->where('user_id', auth()->id())->count())->badgeColor('danger'),
            'published' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'published'))->badge(Post::query()->where('status', 'published')->where('user_id', auth()->id())->count())->badgeColor('success')

        ];
    }
}
