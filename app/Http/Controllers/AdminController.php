<?php

namespace App\Http\Controllers;

use App\Models\Camp;
use App\Models\Camper;
use App\Models\Person;
use App\Repositories\PersonRepository;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $countPeople = Person::count();
        $countCampers = Camper::distinct('person_id')->count('person_id');
        $countCamps = Camp::count();
        $camps = Camp::get();
        return view('admin.pages.dashboard',[
            'countPeople' => $countPeople,
            'countCampers' => $countCampers,
            'countCamps' => $countCamps,
            'camps' => $camps,
        ]);
    }
}
