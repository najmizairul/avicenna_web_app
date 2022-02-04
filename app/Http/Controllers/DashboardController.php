<?php

namespace App\Http\Controllers;

use App\Actions\RedirectAuthenticatedUser;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Prescription;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{

    private RedirectAuthenticatedUser $redirectAuthenticatedUser;

    public function __construct()
    {
        $this->redirectAuthenticatedUser = new RedirectAuthenticatedUser();
    }

    public function index(\Request $request): \Illuminate\Http\RedirectResponse
    {
        return $this->redirectAuthenticatedUser->handle();
    }

    public function receptionDashboard(): \Inertia\Response
    {
        // no more than 3 waiting patients for a user
        $unavailableDoctors = [];

//        PatientVisit::where(function ($query) {
//            return $query->where('status', 'waiting')->count() > 3;
//        })->groupBy('user_id')->get(); //>pluck('id');


        // dd($unavailableDoctors);
        return Inertia::render('Reception/Dashboard', [
            'stats' => [
                'patientsToday' => Patient::where('created_at', '>=', today())->count(),
                'doctorsAvailable' => User::where('role', 'doctor')->whereNotIn('id', $unavailableDoctors)->count()
            ]
        ]);
    }

    public function doctorsDashboard(): \Inertia\Response
    {
        $user = auth()->user();

        $patientCount =  PatientVisit::query()->with('patient', 'referrer')
            ->where('status', 'waiting')
            ->where('user_id', $user->id)
            ->count();

        return Inertia::render('Doctors/Dashboard',  [
            'stats' => [
                'patientsToday' =>  PatientVisit::query()
                    ->where('created_at', '>=', today())
                    ->where('status', 'closed')
                    ->where('user_id', $user->id)
                    ->count(),
                'patientsWaiting' => $patientCount,
            ]
        ]);
    }

    public function pharmacyDashboard(): \Inertia\Response
    {
        return Inertia::render('Pharmacy/Dashboard', [
            'nearbyPatients' => Prescription::with('patient')
                ->where(function($query) {
                    $query->where('is_patient_coming', true)
                        ->orWhere('is_patient_nearby', true);
                })
                ->where('status', '!=', 'filled')
                ->get()
        ]);
    }
}
