<div>
    <h2 class="text-xl">Confirmation for Appointment at: {{ $appointment->start_time }}</h2>

    <div class="mt-4 mb-4">
        You have <span id="time" class="font-bold text-red-800"></span> to confirm this appointment. Otherwise, it will
        be cancelled.
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        let timeBox = document.getElementById('time');

        const eventTime = {{ \Carbon\Carbon::parse($appointment->reserved_at)->addMinutes(config('app.appointmentReservationTime'))->unix() }};
        const currentTime = moment().unix();
        const diffTime = eventTime - currentTime;
        let duration = moment.duration(diffTime * 1000, 'milliseconds');
        const interval = 1000;

        setInterval(function () {
            duration = moment.duration(duration - interval, 'milliseconds');
            timeBox.innerHTML = duration.hours() + ":" + duration.minutes() + ":" + duration.seconds()

            if(duration.hours() == 0 && duration.minutes() == 0 && duration.seconds() == 0) {
                window.location.href = '{{ route('dashboard') }}';
            }
        }, interval);
    </script>
@endpush