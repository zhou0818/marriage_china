<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinces;
use App\Models\Cities;
use App\Models\Areas;

class ChinaAreasController extends Controller
{
    public function provinces()
    {
        $provinces = Provinces::all(['id', 'name as text']);
        return json_encode($provinces, JSON_UNESCAPED_UNICODE);
    }

    public function cities(Request $request)
    {
        $province_id = $request->get('q');
        $cities = Cities::where('province_id', $province_id)->get(['id', 'name as text']);
        return json_encode($cities, JSON_UNESCAPED_UNICODE);
    }

    public function areas(Request $request)
    {
        $province_id = $request->get('q');
        $areas = Areas::where('city_id', $province_id)->get(['id', 'name as text']);
        return json_encode($areas, JSON_UNESCAPED_UNICODE);
    }
}
