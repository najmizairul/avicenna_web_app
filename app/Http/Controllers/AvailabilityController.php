<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function toggleAvailability(Request $request)
    {
       $user = $request->user();

       $user->is_available = !$user->is_available;

       $user->save();

       return redirect()->back(303);//->flash('banner', 'You have updated your availability!');
    }
}
