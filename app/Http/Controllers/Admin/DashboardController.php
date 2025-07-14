<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $visitsByCountry = Visit::select('country', DB::raw('count(*) as total'))
            ->groupBy('country')
            ->get();

        $visitsByCity = Visit::select('city', DB::raw('count(*) as total'))
            ->groupBy('city')
            ->get();

        return view('admin.analytics.index', [
            'visitsByCountry' => $visitsByCountry,
            'visitsByCity' => $visitsByCity,
        ]);
    }
}
