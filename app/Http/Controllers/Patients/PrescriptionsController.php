<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PrescriptionsController extends Controller
{
    public function index()
    {
        $prescriptions =  Prescription::with('patient')
            ->where('patient_id', auth()->guard('patients')->id())
            ->get();

        return Inertia::render('Patients/Prescriptions/Index', [
            'prescriptions' => $prescriptions
        ]);
    }

    public function show($code, Request $request)
    {
        $prescription = Prescription::with('user', 'drugs')
            ->where('code', $code)
            ->firstOrFail();

        return Inertia::render('Patients/Prescriptions/Show', [
            'prescription' => $prescription,
            'patient' => auth()->guard('patients')->user(),
        ]);
    }
}
