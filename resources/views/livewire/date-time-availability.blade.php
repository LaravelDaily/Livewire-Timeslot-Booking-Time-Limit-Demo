@php use Carbon\Carbon; @endphp
<div class="space-y-4">
    @if(!$appointment)
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
    @endif

    <div class="@if(!$appointment) hidden @endif">
        <h2 class="text-xl">Confirmation for Appointment at: {{ $appointment?->start_time }}</h2>

        <div class="mt-4 mb-4">
            You have <span id="time" class="font-bold text-red-800"></span> to confirm this appointment. Otherwise, it
            will be cancelled.
        </div>
        <div class="mt-4">
            <button wire:click="confirmAppointment"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Confirm
            </button>
            <button wire:click="cancelAppointment"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Cancel
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <script>
        let runningInterval = null;

        // Allows you to select a day from the calendar
        new Pikaday({
            field: document.getElementById('date'),
            onSelect: function () {
                @this.
                set('date', this.getMoment().format('YYYY-MM-DD'));
            }
        })

        // When appointment is added, we will start a timer to count down
        window.addEventListener('appointmentAdded', (e) => {
            let timeBox = document.getElementById('time');

            const eventTime = e.detail[0].time;
            const currentTime = moment().unix();
            const diffTime = eventTime - currentTime;
            let duration = moment.duration(diffTime * 1000, 'milliseconds');
            const interval = 1000;

            runningInterval = setInterval(function () {
                duration = moment.duration(duration - interval, 'milliseconds');
                timeBox.innerHTML = duration.hours() + ":" + duration.minutes() + ":" + duration.seconds()

                if (duration.hours() == 0 && duration.minutes() == 0 && duration.seconds() == 0) {
                    window.location.href = '{{ route('dashboard') }}';
                }
            }, interval);
        });

        // On confirmation or cancellations we'll clear the interval
        window.addEventListener('appointmentConfirmed', (e) => {
            clearInterval(runningInterval);
        });
        window.addEventListener('appointmentCancelled', (e) => {
            clearInterval(runningInterval);
        });
    </script>
@endpush
