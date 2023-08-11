<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;

class AppointmentConfirmation extends Component
{
    public ?Appointment $appointment;

    public function render()
    {
        return view('livewire.appointment-confirmation', [
            'appointment' => $this->appointment
        ]);
    }

    public function confirmAppointment(): void
    {
        $this->appointment->confirmed = true;
        $this->appointment->save();

        $this->redirectRoute('appointment-confirmed', $this->appointment->id);
    }

    public function cancelAppointment()
    {
        Appointment::find($this->appointment->id)->delete();

        return $this->redirectRoute('dashboard');
    }
}
