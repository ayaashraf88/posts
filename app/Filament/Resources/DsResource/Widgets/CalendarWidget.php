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

    // protected function getView(): string
    // {
    //     return 'dayGridMonth'; // Default view
    // }
    //register tooltip in the widget
    public function getOptions(): array
{
    return [
        'plugins' => ['tooltip'], // Enable tooltip plugin
        'eventDidMount' => $this->getEventDidMountScript(),
    ];
}

protected function getEventDidMountScript(): string
{
    return <<<JS
        function(info) {
            // Initialize tooltip
            new bootstrap.Tooltip(info.el, {
                title: info.event.extendedProps.tooltip?.text || '',
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });
        }
    JS;
}
    public function getEvents(array $fetchInfo = []): Collection | array
    {
        return Post::query()
            ->where('user_id', auth()->id())
            ->whereNotNull('scheduled_time')
            ->get()
             ->map(function (Post $post) {
            return [
                'id' => $post->id,
                'title' => $post->title . " (Status: " . $post->status . ")",
                'start' => $post->scheduled_time,
                'end' => $post->scheduled_time,
                'tooltip' => [  // Tooltip on hover
                    'title' => 'Post Status',
                    'text' => $post->status,
                ],
            ];
        })
        ->toArray();
    }

}