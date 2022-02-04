<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class CollectionController  extends Controller
{
    public function show($code, Request $request)
    {
        $prescription  = Prescription::with('drugs', 'user')
            ->whereCode($code)
            ->firstOrFail();

        if($prescription->code) {
            $prescription->qr_code = 'https://api.qrserver.com/v1/create-qr-code?qr=1&size=300x300&data=' . route('pharmacy.orders.code', $prescription->code);
        }

        return $prescription->toArray();
    }

    public function notify()
    {
      return "Hello from Server";
    }
}
