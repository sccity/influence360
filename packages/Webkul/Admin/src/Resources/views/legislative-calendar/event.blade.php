<x-admin::layouts>
    <x-slot:title>
        {{ $event->description }}
    </x-slot>

    <div class="flex gap-4">
        <div class="w-2/3 bg-white p-4 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-4">{{ $event->description }}</h1>
            <p><strong>Date:</strong> {{ $event->mtg_date }}</p>
            <p><strong>Time:</strong> {{ $event->start_time }} - {{ $event->end_time }}</p>
            <p><strong>Location:</strong> {{ $event->location }}</p>
            <p><strong>Event Type:</strong> {{ $event->event_type }}</p>
            <p><strong>Meeting ID:</strong> {{ $event->meeting_id }}</p>
            
            <div class="mt-4">
                @if($event->agenda_url)
                    <a href="{{ $event->agenda_url }}" class="text-blue-600 hover:underline mr-4">Agenda</a>
                @endif
                @if($event->minutes_url)
                    <a href="{{ $event->minutes_url }}" class="text-blue-600 hover:underline mr-4">Minutes</a>
                @endif
                @if($event->media_url)
                    <a href="{{ $event->media_url }}" class="text-blue-600 hover:underline mr-4">Media</a>
                @endif
                @if($event->emtg_url)
                    <a href="{{ $event->emtg_url }}" class="text-blue-600 hover:underline mr-4">EMTG</a>
                @endif
                @if($event->ics_url)
                    <a href="{{ $event->ics_url }}" class="text-blue-600 hover:underline">ICS</a>
                @endif
            </div>
        </div>
        
        <div class="w-1/3 bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Comments</h2>
            <p class="text-gray-500">Comments module will be added here.</p>
        </div>
    </div>
</x-admin::layouts>
