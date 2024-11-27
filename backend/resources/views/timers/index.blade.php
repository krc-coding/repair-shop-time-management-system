<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Timers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white mt-4 shadow rounded">
            @foreach ($timers as $timer)
                <div class="p-4 flex justify-between">
                    <span>{{ $timer->start_time }}</span>

                    <span>{{ $timer->end_time }}</span>

                    <form method="POST" action="{{ route('timers.destroy', $timer) }}">
                        @csrf
                        @method('DELETE')

                        <x-primary-button onclick="return confirm('Are you sure you want to remove this timer?')">X</x-primary-button>
                    </form>
                </div>

                @if (!$loop->last)
                    <hr>
                @endif
            @endforeach
        </div>
    </div>
</x-app-layout>

