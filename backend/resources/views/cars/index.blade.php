<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cars') }}
        </h2>
    </x-slot>

    <div class="py-12">

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <dialog open class="static border border-red-500 text-red-700 bg-red-200 px-4 py-2 rounded mb-4 max-w-7xl mx-auto w-full">
                    <span>{{ $error }}</span>

                    <form method="dialog" class="float-right">
                        <button>&times;</button>
                    </form>

                    <div class="clear-both"></div>
                </dialog>
            @endforeach
        @endif

        <form method="POST" action="{{ route('cars.register') }}" class="max-w-7xl flex gap-4 m-auto">
            @csrf

            <x-text-input name="plate" placeholder="AB 12 345" pattern="[a-z]{2} \d{2} \d{3}" />

            <x-primary-button class="h-10">Register car</x-primary-button>
        </form>

        <div class="max-w-7xl mx-auto bg-white mt-4 shadow rounded">
            @foreach ($cars as $car)
                <div class="py-4 px-6 flex gap-4 items-center">
                    <span>{{ $car->plate }}</span>

                    <div class="flex-1"></div>

                    <div onclick="timers{{ $car->id }}.style.display = getComputedStyle(timers{{ $car->id }}).display === 'block' ? 'none' : 'block'" class="flex gap-2 cursor-pointer select-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                        Timers
                    </div>

                    <form method="POST" action="{{ route('cars.unregister', $car) }}">
                        @csrf
                        @method('DELETE')

                        <x-primary-button class="h-full" onclick="return confirm('Are you sure you want to remove this car?')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </x-primary-button>
                    </form>
                </div>

                <div id="timers{{ $car->id }}" class="hidden target:block border-y bg-gray-50 shadow-inner">
                    <hr>

                    <div class="p-6">
                        <p class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>

                            Timers

                            @if (count($car->timers))
                                <table class="mt-4">
                                    <tr>
                                        <th class="border border-gray-300 py-2 px-4">Type</th>
                                        <th class="border border-gray-300 py-2 px-4">Start</th>
                                        <th class="border border-gray-300 py-2 px-4">End</th>
                                        <th class="border border-gray-300 py-2 px-4">Total</th>
                                        <th class="border border-gray-300 py-2 px-4"></th>
                                    </tr>
                                    @foreach ($car->timers as $timer)
                                        <tr>
                                            <td class="border border-gray-300 py-2 px-4">{{ ucfirst($timer->type) }}</td>
                                            <td class="border border-gray-300 py-2 px-4">{{ $timer->start_time }}</td>
                                            <td class="border border-gray-300 py-2 px-4">{{ $timer->end_time }}</td>
                                            <td class="border border-gray-300 py-2 px-4">{{ $timer->end_time?->diffForHumans($timer->start_time, true) }}</td>
                                            <td class="border border-gray-300 py-2 px-4">
                                                <form method="POST" action="{{ route('timers.stop', $car) }}">
                                                    @csrf

                                                    <x-secondary-button type="submit" :disabled="isset($timer->end_time)">Stop</x-secondary-button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                            <form method="POST" action="{{ route('timers.create', $car) }}" class="flex gap-4 mt-4">
                                @csrf

                                <select name="type" class="border-gray-300 rounded shadow-sm">
                                    <option value="normal">Normal</option>
                                    <option value="lift">Lift</option>
                                </select>

                                <x-primary-button class="h-10">Create</x-primary-button>
                            </form>
                        </p>
                    </div>
                </div>

                @if (!$loop->last)
                    <hr>
                @endif
            @endforeach
        </div>
    </div>
</x-app-layout>

