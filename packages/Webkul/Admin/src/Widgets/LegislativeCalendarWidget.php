<?php

namespace Webkul\Admin\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Webkul\LegislativeCalendar\Models\LegislativeCalendar;
use Saade\FilamentFullCalendar\Data\EventData;

class LegislativeCalendarWidget extends FullCalendarWidget
{
    public function fetchEvents(array $fetchInfo): array
    {
        return LegislativeCalendar::query()
            ->where('mtg_date', '>=', $fetchInfo['start'])
            ->where('mtg_date', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (LegislativeCalendar $event) {
                return EventData::make()
                    ->id($event->guid)
                    ->title($event->description)
                    ->start($event->mtg_date . ' ' . $event->start_time)
                    ->end($event->mtg_date . ' ' . $event->end_time)
                    ->extendedProps([
                        'location' => $event->location,
                        'event_type' => $event->event_type,
                    ]);
            })
            ->toArray();
    }

    protected function headerActions(): array
    {
        return [];
    }

    protected function modalActions(): array
    {
        return [];
    }

    protected function viewAction(): ?Action
    {
        return null;
    }

    public function config(): array
    {
        return [
            'editable' => false,
            'selectable' => false,
            'initialView' => 'dayGridMonth',
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
            ],
        ];
    }

    public function eventDidMount(): string
    {
        return <<<JS
            function(info) {
                var tooltip = new Tooltip(info.el, {
                    title: info.event.extendedProps.location + '<br>' + info.event.extendedProps.event_type,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body',
                    html: true
                });
            }
        JS;
    }
}
