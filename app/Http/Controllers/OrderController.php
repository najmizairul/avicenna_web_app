<?php

namespace App\Http\Controllers;

//use App\Models\Drug;
//use App\Models\Patient;
//use App\Models\PatientVisit;
//use Illuminate\Http\Request;
use App\Actions\GenerateUniqueID;
use App\Models\Drug;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function pharmacyIndex(): \Inertia\Response
    {
        return Inertia::render('Pharmacy/Dashboard');
    }

    public function pharmacyOrders(Request $request): \Inertia\Response
    {
        $prescriptions = Prescription::with('drugs', 'patient', 'user')
            ->orderBy('collect_at', 'asc');
//        ->orderBy('collect_at', 'desc');

        if($request->has('status') && $request->get('status') !== 'all') {
            $prescriptions = $prescriptions->where('status', '=', $request->get('status'));
        }

        if($request->has('search')) {
            $prescriptions = $prescriptions->whereHas('patient', function($query) use ($request) {
                return $query->where('name', 'like', '%' .$request->get('search') . '%');
            })->orWhereHas('user', function($query) use ($request) {
                return $query->where('name', 'like', '%' .$request->get('search') . '%');
            });
        }
        return Inertia::render('Pharmacy/Orders/Index', [
            'prescriptions' => $prescriptions->paginate($request->get('perPage', 10))
        ]);
    }

    public function pharmacyOrderShow($id): \Inertia\Response
    {
        $prescription =   Prescription::with('drugs', 'patient', 'user')->findOrFail($id);

        //dd($prescription->toArray());

        return Inertia::render('Pharmacy/Orders/Show', [
            'prescription' => $prescription
        ]);
    }


    public function pharmacyOrderShow2($id): \Inertia\Response
    {
        $prescription =   Prescription::with('drugs', 'patient', 'user')->findOrFail($id);

        //dd($prescription->toArray());

        return Inertia::render('Pharmacy/Orders/Show2', [
            'prescription' => $prescription
        ]);
    }

    public function pharmacyVerifyPrescription($code): \Inertia\Response
    {
        $prescription  = Prescription::with('drugs', 'patient', 'user')->whereCode($code)->firstOrFail();

        return Inertia::render('Pharmacy/Orders/Verify', [
            'prescription' => $prescription
        ]);
    }



    public function pharmacyUpdateInFence($id, Request $request) {
        $order = Prescription::findOrFail($id);

        $order->update(['is_patient_nearby' => true]);

        return redirect()->back()->banner('Updated Successfully!');

    }

    public function pharmacyUpdateOnWay($id, Request $request) {
        $order = Prescription::findOrFail($id);

        $order->update(['is_patient_coming' => true]);

        return redirect()->back()->banner('Updated Successfully!');
    }

    public function pharmacyUpdate($id, Request $request)
    {
        $order = Prescription::with('drugs', 'patient', 'user')->findOrFail($id);

        $order->collect_at = $request->get('collect_at');
        $order->collected_at = $request->get('collected_at');
        $order->status = $request->get('status', 'active');



        if($order->status === 'ready') {
            $order->code = GenerateUniqueID::prescriptionCode();

            $order->drugs->map(function ($drug) {
                Drug::where('id', $drug->drug->id)->update(['quantity' => $drug->drug->quantity - ($drug->daily_dose * $drug->duration) ]);

            });

        }

        if($order->status === 'filled') {
            $order->code = GenerateUniqueID::prescriptionCode();
        }

        $order->save();

        return redirect()->back(303);
    }
}
