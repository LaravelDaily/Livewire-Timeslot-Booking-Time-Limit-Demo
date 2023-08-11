<div class="space-y-4">
    <form wire:submit="save" class="space-y-4">
        <div class="w-full bg-gray-600 text-center">
            <input
                    type="text"
                    id="date"
                    wire:model="date"
                    class="bg-gray-200 text-sm sm:text-base pl-2 pr-4 rounded-lg border border-gray-400 py-1 my-1 focus:outline-none focus:border-blue-400"
                    autocomplete="off"
            />
        </div>

        <div class="grid gap-4 grid-cols-6">
            @foreach($availableTimes as $key => $time)
                <div class="w-full group">
                    <input
                            type="radio"
                            id="interval-{{ $key }}"
                            name="time"
                            value="{{ $date . ' ' . $key }}"
                            @disabled(!$time)
                            wire:model="startTime"
                            class="hidden peer">
                    <label
                            @class(['inline-block w-full text-center border py-1 peer-checked:bg-green-400 peer-checked:border-green-700', 'bg-blue-400 hover:bg-blue-500' => $time, 'bg-gray-100 cursor-not-allowed' => ! $time])
                            wire:key="interval-{{ $key }}"
                            for="interval-{{ $key }}">
                        {{ $key }}
                    </label>
                </div>
            @endforeach
        </div>

        <button class="mt-4 bg-blue-200 hover:bg-blue-600 px-4 py-1 rounded">
            Reserve
        </button>
    </form>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <script>
        new Pikaday({
            field: document.getElementById('date'),
            onSelect: function () {
                @this.
                set('date', this.getMoment().format('YYYY-MM-DD'));
            }
        })
    </script>
@endpush
