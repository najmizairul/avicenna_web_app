<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin(Request $request): \Inertia\Response
    {
        return Inertia::render('Patients/Auth/Login', []);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'ic' => 'required',
            'rn' => 'required',
        ]);

        $patient = Patient::where('ic', $data['ic'])->where('rn', $data['rn'])->first();

        if (!$patient) {
            return redirect()->back()->dangerBanner('Information provided not correct!');;
        }

        auth()->guard('patients')->loginUsingId($patient->id);

        return redirect()->route('patients.prescriptions')->banner('Track Prescriptions');
    }

    public function logout(Request $request)
    {
       auth()->guard('patients')->logout();

       return redirect()->route('patients.login')->banner('Successfully Logged out!');
    }

}
