<?php

namespace App\Http\Controllers;

use App\Actions\GenerateUniqueID;
use App\Models\Drug;
use App\Models\DrugPrescription;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function doctorsIndex(Request $request) //: ?\Inertia\Response
    {
        $patients = PatientVisit::query()->with('patient', 'referrer')
            ->where('status', 'waiting')
            ->where('user_id', auth()->user()->id);

        if($request->has('search')) {
             $patients->whereHas('patient', function($query) use ($request) {
                 return $query->where('name', 'like', '%' .$request->get('search') . '%');
             })->orWhereHas('referrer', function($query) use ($request) {
                 return $query->where('name', 'like', '%' .$request->get('search') . '%');
             });
        }

        return Inertia::render('Doctors/Patients/Index', [
            'patients' => tap($patients->paginate($request->get('perPage', 10)), function ($p) {
                return $p->transform(function($patient) {
                    return array_merge($patient->patient->toArray(), [
                        'referrer' => $patient->referrer->name,
                    ]);
                });
            })
        ]);
    }


    public function doctorsShow($code): \Inertia\Response
    {
        $patient =  Patient::where('rn', $code)->firstOrFail();

        return Inertia::render('Doctors/Patients/Show', [
            'patient' => $patient,
            'visit' => PatientVisit::where('patient_id', $patient->id)
                ->where('user_id', auth()->id())
                ->where('status', 'waiting')
                ->select(['id', 'status'])
                ->first(),
            'drugs' => Drug::select('id', 'name')->get()->toArray(),
            'medicationHistory' => Prescription::with('user', 'drugs.drug')->where('patient_id', $patient->id)
                ->where('status', 'filled')
                ->orderBy('updated_at', 'desc')
                ->get()->map(function($prescription) {
                return  [
                    'drugs'=> $prescription->drugs->map(function($drug) { return $drug->drug->name; }),
                    'doctor' => $prescription->user->name,
                    'prescribed_at' =>  $prescription->created_at->format('d M Y'), // change to collected_at
                ];
            }),
            'previousDiagnosisHistory' => PatientVisit::with('doctor')->where('patient_id', $patient->id)
                ->orderBy('visited_at', 'desc')
                ->get()->map(function($visit) {
                return
                    [
                        'diagnosis' => $visit->diagnosis,
                        'doctor' => $visit->doctor->name,
                        'diagnosed_at' => $visit->visited_at,
                    ];
            })
        ]);
    }

    public function doctorsFinishConsultation($code, Request $request)
    {
        $data = $request->validate([
            "visit_id" => "required",
            "diagnosis" => "required|min:5",
            "patient_id" => "required",
            "prescriptions" => "sometimes|array",
            //"prescriptions.*.dosage" => "string"
        ]);

        $visit = PatientVisit::find($data['visit_id']);

        $visit->diagnosis = $data['diagnosis'];
        $visit->status = 'closed';

        $visit->save();

        $prescription = Prescription::create([
            'patient_id' => $data['patient_id'],
            'patient_visit_id' => $data['visit_id'],
            'user_id' => auth()->id()
        ]);

        collect($data['prescriptions'])->map(function($drug) use ($prescription) {
            DrugPrescription::create(array_merge($drug, [
                'prescription_id' => $prescription->id
            ]));
        });

        return redirect()->route('doctors.dashboard', [], 303);


    }

    public function seePatient(Request $request)
    {
        $visit = PatientVisit::findOrFail($request->visit_id);

        $visit->status = "active";

        $visit->save();

        return redirect()->back(303);

    }

    public function receptionIndex(Request $request): \Inertia\Response
    {
        $patients = Patient::orderBy('last_visited_at', 'desc');

        if($request->has('search'))   {
            $searchKey = $request->get('search');

            $patients->where('rn', $searchKey)
                ->orWhere('ic', $searchKey)
                ->orWhere('name', 'like', '%' . $searchKey . '%');
        }
        return Inertia::render('Reception/Patients/Index', [
            'patients' => $patients->paginate($request->get('perPage', 10))
        ]);
    }

    public function receptionShow($code): \Inertia\Response
    {
        return Inertia::render('Reception/Patients/Show', [
            'patient' => Patient::where('rn', $code)->firstOrFail(),
            'doctors' => User::where('role', 'doctor')->where('is_available', true)->get()
        ]);
    }

    public function receptionAssignDoctor(Request $request)
    {
        $data = $request->all();
        $referrer = $request->user();

        PatientVisit::create([
            'referrer_id' => $referrer->id,
            'patient_id' => $data['patient_id'],
            'notes' => $data['notes'],
            'user_id' => $data['doctor'],
        ]);

        return  redirect()->route('reception.dashboard', 303);
    }

//    public function receptionSearch(Request $request): \Inertia\Response
//    {
//        $searchKey = $request->get('search');
//
//        return Inertia::render('Reception/Patients/Index', [
//            'patients' => Patient::where('rn', $searchKey)
//                ->orWhere('ic', $searchKey)
//                ->orWhere('name', 'like', '%' . $searchKey . '%')
//                ->orderBy('last_visited_at', 'desc')
//                ->get()
//        ]);
//    }

    public function register(Request $request): \Inertia\Response
    {
        return Inertia::render('Reception/Patients/Create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {

        $data = $request->validate([
            'name' => 'required',
            'ic' => 'required',
            'sex' => 'required',
            'dob' => 'required|date',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'phone' => 'required',
        ]);

        Patient::create(array_merge($data, [
            'rn' => GenerateUniqueID::rnNumber(),
            'last_visited_at' => now()
        ]));

        return  redirect()->route('reception.dashboard', 303);
    }
}
