<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
          $drugs = Drug::orderBy('name', 'ASC');

          if($request->has('search')) {

              $searchKey = $request->get('search');

              $drugs->where('name', 'like', '%'. $searchKey . '%')
                  ->orWhere('manufacturer', 'like', '%'. $searchKey . '%');
          }

        return Inertia::render('Pharmacy/Stock/Index', [
            'drugs' => tap($drugs->paginate($request->get('perPage', 10)), function ($d) {

                return $d->transform(function($drug) {
                    return array_merge($drug->toArray(), [
                        'expires' => $drug->expires_at->toDateString(),
                    ]);
                });
            })
        ]);
    }

    public function update( Request $request, $id = null): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'drug.id' => 'sometimes',
            'drug.name' => 'sometimes',
            'drug.manufacturer' => 'sometimes',
            'drug.quantity' => 'sometimes|int',
            'drug.sku' => 'sometimes',
            'drug.expires_at' => 'sometimes|date',
        ]);

        $data = $data['drug'];

        $drug = !is_null($id) ? Drug::findOrFail($id) : new Drug();

        $drug->name = $data['name'];
        $drug->manufacturer = $data['manufacturer'];
        $drug->sku = $data['sku'];
        $drug->quantity = (int)$data['quantity'];
        $drug->expires_at = $data['expires_at'];

        $drug->save();

        return redirect()->route('pharmacy.stock', [], 303);
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Drug::find($id)->delete();

        return redirect()->back(303);
    }
}
