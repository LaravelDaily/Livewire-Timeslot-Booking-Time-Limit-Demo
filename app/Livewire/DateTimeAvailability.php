<?php

namespace App\Livewire;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class DateTimeAvailability extends Component
{
    public string $date;

    public array $availableTimes = [];

    public Collection $appointments;

    public string $startTime = '';

    public ?Appointment $appointment = null;

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');

        $this->getIntervalsAndAvailableTimes();
    }

    public function updatedDate(): void
    {
        $this->getIntervalsAndAvailableTimes();
    }

    public function render()
    {
        return view('livewire.date-time-availability');
    }

    public function save()
    {
        $this->validate([
            'startTime' => 'required',
        ]);

        $this->appointment = Appointment::create([
            'start_time' => Carbon::parse($this->startTime),
            'reserved_at' => now()
        ]);

        $this->dispatch('appointmentAdded', [
            'time' => Carbon::parse($this->appointment->reserved_at)->addMinutes(config('app.appointmentReservationTime'))->unix()
        ]);
    }

    public function confirmAppointment(): void
    {
        $this->appointment->confirmed = true;
        $this->appointment->save();

        $this->dispatch('appointmentConfirmed');
        $this->redirectRoute('appointment-confirmed', $this->appointment->id);
    }

    public function cancelAppointment()
    {
        Appointment::find($this->appointment->id)->delete();

        $this->dispatch('appointmentCancelled');
        $this->reset('appointment');
    }

    protected function getIntervalsAndAvailableTimes(): void
    {
        $this->reset('availableTimes');

        $carbonIntervals = Carbon::parse($this->date . ' 8 am')->toPeriod($this->date . ' 8 pm', 30, 'minute');

        $this->appointments = Appointment::whereDate('start_time', $this->date)->get();

        foreach ($carbonIntervals as $interval) {
            $this->availableTimes[$interval->format('h:i A')] = !$this->appointments->contains('start_time', $interval);
        }
    }
}
