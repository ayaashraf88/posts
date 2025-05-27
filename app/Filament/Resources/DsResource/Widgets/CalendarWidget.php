<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Guava\Calendar\ValueObjects\CalendarEvent;
use \Guava\Calendar\Widgets\CalendarWidget;
use Guava\FilamentCalendar\Records\Event;
use Illuminate\Support\Collection;

class MyCalendarWidget extends CalendarWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getView(): string
    {
        return 'dayGridMonth'; // Default view
    }
    public function getEvents(array $fetchInfo = []): Collection | array
    {
        return Post::query()
            ->where('user_id', auth()->id())
            ->whereNotNull('scheduled_time')
            ->get()
            ->map(function (Post $post) {
                return collect([
                    'id' => $post->id,
                    'title' => $post->title,
                    'start' => $post->scheduled_time,
                    'end' => $post->scheduled_time,
                    'url' => PostResource::getUrl('edit', ['record' => $post]),

                ]);
            })
            ->toArray();
    }

}