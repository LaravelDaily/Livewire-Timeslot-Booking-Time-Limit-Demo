<?php

namespace App\Http\Controllers;

use App\Models\Appointment;

class ConfirmAppointmentController extends Controller
{
    public function __invoke(Appointment $appointment)
    {
        return view('confirmAppointment', [
            'appointment' => $appointment
        ]);
    }
}
