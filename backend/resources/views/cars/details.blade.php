<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Car management') }}
        </h2>
    </x-slot>

    <div class="car-details">
        <x-secondary-button onclick="window.location.href='{{ url('/cars') }}'" class="back-btn" style="margin-bottom: 20px;">Back</x-secondary-button>

        <h1>Car: {{ $car->plate }}</h1>
        <p>Current Station: {{ $car->station }}</p>
        <p>Current status: {{ $car->status }}</p>

        <br>
        <br>
        <br>

        <form method="POST" action="{{ route('cars.set-station', $car->id) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="car_id" value="{{ $car->id }}">
            <label for="station-select">Select Station:</label>
            <select name="station" id="station-select">
                <option value="">Select Station</option>
                <option value="Station 1" {{ $car->station == 'Station 1' ? 'selected' : '' }}>Station 1</option>
                <option value="Station 2" {{ $car->station == 'Station 2' ? 'selected' : '' }}>Station 2</option>
            </select>

            <x-primary-button>Update Station</x-primary-button>
        </form>

        <br>
        <br>
        <br>

        <form method="POST" action="{{ route('cars.update-status', $car->id) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="car_id" value="{{ $car->id }}">
            <label for="status-select">Select status:</label>
            <select name="status" id="status-select">
                <option value="Not-started" {{ $car->status == 'Not-started' ? 'selected' : '' }}>Not-started</option>
                <option value="In-progress" {{ $car->status == 'In-progress' ? 'selected' : '' }}>In-progress</option>
                <option value="Done" {{ $car->status == 'Done' ? 'selected' : '' }}>Done</option>
            </select>

            <x-primary-button>Update Status</x-primary-button>
        </form>

        <br>
        <br>
        <br>

        <form method="POST" action="{{ route('cars.update-notes', ['car' => $car->id]) }}" style="margin-top: 20px;">
            @csrf
            @method('PUT')

            <label for="car-notes">Car Notes:</label><br>
            <textarea name="notes" id="car-notes" rows="5" style="width: 100%; border: 1px solid #ddd; border-radius: 4px; padding: 10px;">{{ $car->notes }}</textarea><br>

            <x-primary-button class="details-btn" style="margin-top: 10px;">Update Notes</x-primary-button>
        </form>

        <br>
        <br>
        <br>

        <p>Total work time: {{ $normalTotal }} </p>
        <p>Total lift time: {{ $liftTotal }} </p>

        <br>
        <br>
        <br>

        <div id="timers{{ $car->id }}" class="target:block border-y bg-gray-50 shadow-inner">
            <hr>

            <div class="p-6">
                <p class="flex gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>

                    Timers

                <div class="flex gap-4 mt-4">
                    <div class="mt-4">
                        <div class="mt-2">
                            Normal Timer:
                        </div>
                    </div>


                    @if (!$hasActiveNormalTimer && count($timers->where('type', 'normal')) == 0)
                    <form method="POST" action="{{ route('timers.create', $car) }}" class="flex gap-4 mt-4">
                        @csrf
                        <input name="type" class="hidden" value="normal" />
                        <x-primary-button class="h-10">Start</x-primary-button>
                    </form>
                    @else
                    <x-primary-button class="h-10 mt-4" disabled style="opacity: 50%;">Start</x-primary-button>
                    @endif

                    @if ($hasActiveNormalTimer)
                    <form method="POST" action="{{ route('timers.pause', $car) }}" class="flex gap-4 mt-4">
                        @csrf
                        <input name="type" class="hidden" value="normal" />
                        <x-primary-button class="h-10">Pause</x-primary-button>
                    </form>
                    @else
                    <x-primary-button class="h-10 mt-4" disabled style="opacity: 50%;">Pause</x-primary-button>
                    @endif

                    @if (!$hasActiveNormalTimer && count($timers->where('type', 'normal')) > 0)
                    <form method="POST" action="{{ route('timers.create', $car) }}" class="flex gap-4 mt-4">
                        @csrf
                        <input name="type" class="hidden" value="normal" />
                        <x-primary-button class="h-10">Resume</x-primary-button>
                    </form>
                    @else
                    <x-primary-button class="h-10 mt-4" disabled style="opacity: 50%;">Resume</x-primary-button>
                    @endif

                    @if ($hasActiveNormalTimer && count($timers->where('type', 'normal')) > 0)
                    <form method="POST" action="{{ route('timers.stop', $car) }}" class="flex gap-4 mt-4">
                        @csrf
                        <input name="type" class="hidden" value="normal" />
                        <x-primary-button class="h-10">Stop</x-primary-button>
                    </form>
                    @else
                    <x-primary-button class="h-10 mt-4" disabled style="opacity: 50%;">Stop</x-primary-button>
                    @endif
                </div>

                <div class="flex gap-4 mt-4">
                    <div class="mt-4">
                        <div class="mt-2">
                            Lift Timer:
                        </div>
                    </div>

                    @if (!$hasActiveLiftTimer)
                    <form method="POST" action="{{ route('timers.create', $car) }}" class="flex gap-4 mt-4">
                        @csrf
                        <input name="type" class="hidden" value="lift" />
                        <x-primary-button class="h-10">Start</x-primary-button>
                    </form>
                    @else
                    <x-primary-button class="h-10 mt-4" disabled style="opacity: 50%;">Start</x-primary-button>
                    @endif

                    @if ($hasActiveLiftTimer && count($timers->where('type', 'lift')) > 0)
                    <form method="POST" action="{{ route('timers.stop', $car) }}" class="flex gap-4 mt-4">
                        @csrf
                        <input name="type" class="hidden" value="lift" />
                        <x-primary-button class="h-10">Stop</x-primary-button>
                    </form>
                    @else
                    <x-primary-button class="h-10 mt-4" disabled style="opacity: 50%;">Stop</x-primary-button>
                    @endif
                </div>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
