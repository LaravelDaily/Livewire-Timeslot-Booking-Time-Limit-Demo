<?php

namespace App\Http\Controllers;

use App\Models\Appointment;

class AppointmentConfirmedController extends Controller
{
    public function __invoke(Appointment $appointment)
    {
        return view('appointmentConfirmed', [
            'appointment' => $appointment
        ]);
    }
}
