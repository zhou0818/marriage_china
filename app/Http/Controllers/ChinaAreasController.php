<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;

class ChinaAreasController extends Controller
{
    public function provinces()
    {
        $provinces = Province::all(['id', 'name as text']);
        return $provinces;
    }

    public function cities(Request $request)
    {
        $province_id = $request->get('q');
        $cities = City::where('province_id', $province_id)->get(['id', 'name as text']);
        return $cities;
    }

    public function areas(Request $request)
    {
        $province_id = $request->get('q');
        $areas = Area::where('city_id', $province_id)->get(['id', 'name as text']);
        return $areas;
    }
}
