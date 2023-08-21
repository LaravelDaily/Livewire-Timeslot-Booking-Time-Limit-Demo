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
    @else

        <div class="@if(!$appointment) hidden @endif"
             x-data="timer('{{ Carbon::parse($appointment->reserved_at)->addMinutes(config('app.appointmentReservationTime'))->unix() }}')"
        >
            <h2 class="text-xl">Confirmation for Appointment at: {{ $appointment?->start_time }}</h2>

            <div class="mt-4 mb-4">
                <p class="text-center">Please confirm your appointment within the next:</p>
                <div class="flex items-center justify-center space-x-4 mt-4"
                     x-init="init();">
                    <div class="flex flex-col items-center px-4">
                        <span x-text="time().days" class="text-4xl lg:text-5xl">00</span>
                        <span class="text-gray-400 mt-2">Days</span>
                    </div>
                    <span class="w-[1px] h-24 bg-gray-400"></span>
                    <div class="flex flex-col items-center px-4">
                        <span x-text="time().hours" class="text-4xl lg:text-5xl">23</span>
                        <span class="text-gray-400 mt-2">Hours</span>
                    </div>
                    <span class="w-[1px] h-24 bg-gray-400"></span>
                    <div class="flex flex-col items-center px-4">
                        <span x-text="time().minutes" class="text-4xl lg:text-5xl">59</span>
                        <span class="text-gray-400 mt-2">Minutes</span>
                    </div>
                    <span class="w-[1px] h-24 bg-gray-400"></span>
                    <div class="flex flex-col items-center px-4">
                        <span x-text="time().seconds" class="text-4xl lg:text-5xl">28</span>
                        <span class="text-gray-400 mt-2">Seconds</span>
                    </div>
                </div>
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
    @endif
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <script>
        // Allows you to select a day from the calendar
        new Pikaday({
            field: document.getElementById('date'),
            onSelect: function () {
                @this.
                set('date', this.getMoment().format('YYYY-MM-DD'));
            }
        })

        function timer(expiry) {
            return {
                expiry: expiry,
                remaining: null,
                init() {
                    this.setRemaining()
                    setInterval(() => {
                        this.setRemaining();
                    }, 1000);
                },
                setRemaining() {
                    const diff = this.expiry - moment().unix();
                    this.remaining = diff;
                },
                days() {
                    return {
                        value: this.remaining / 86400,
                        remaining: this.remaining % 86400
                    };
                },
                hours() {
                    return {
                        value: this.days().remaining / 3600,
                        remaining: this.days().remaining % 3600
                    };
                },
                minutes() {
                    return {
                        value: this.hours().remaining / 60,
                        remaining: this.hours().remaining % 60
                    };
                },
                seconds() {
                    return {
                        value: this.minutes().remaining,
                    };
                },
                format(value) {
                    return ("0" + parseInt(value)).slice(-2)
                },
                time() {
                    return {
                        days: this.format(this.days().value),
                        hours: this.format(this.hours().value),
                        minutes: this.format(this.minutes().value),
                        seconds: this.format(this.seconds().value),
                    }
                }
            }
        }
    </script>
@endpush
