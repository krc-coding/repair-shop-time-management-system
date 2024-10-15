<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cars') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <form method="POST" action="{{ route('cars.register') }}" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @csrf

            <x-text-input name="plate" placeholder="AB 123 45" />

            <x-primary-button>Register car</x-primary-button>
        </form>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white mt-4 shadow rounded">
            @foreach ($cars as $car)
                <div class="p-4 flex justify-between">
                    <span>{{ $car->plate }}</span>

                    <form method="POST" action="{{ route('cars.unregister', $car) }}">
                        @csrf
                        @method('DELETE')

                        <x-primary-button onclick="return confirm('Are you sure you want to remove this car?')">X</x-primary-button>
                    </form>
                </div>

                @if (!$loop->last)
                    <hr>
                @endif
            @endforeach
        </div>
    </div>
</x-app-layout>

