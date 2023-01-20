<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Regency;

class LocationController extends Controller
{
    //
    public function provinces(Request $request)
    {
        return Province::all();
    }
    public function regencies(Request $request, $provincies_id)
    {
        return Regency::where('province_id', $provincies_id)->get();
    }
    public function distrik(Request $request, $regency_id)
    {
        return District::where('regency_id', $regency_id)->get();
    }
}