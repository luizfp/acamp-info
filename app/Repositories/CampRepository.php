<?php

namespace App\Repositories;

use App\Models\AcampType;
use App\Models\Camp;
use App\Models\Camper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampRepository
{
    protected $entity;

    public function __construct(Camp $model)
    {
        $this->entity = $model;
    }

    public function getAllCamps()
    {
        return $this->entity->orderBy('name')->paginate();
    }

    public function storeCamp(Request $request)
    {
        $this->entity->create($request->all());
    }

    public function getCamp($id)
    {
        $camp = $this->entity->where('id', $id)->first();

        return $camp;
    }

    public function updateCamp($camp, $data)
    {
        $camp->update($data);
    }

    public function deleteCamp($camp)
    {
        $camp->delete();
    }

    public function getAllTypes()
    {
        return AcampType::all();
    }

    public function getCampers($id)
    {
        $campers = DB::table('campers as c')->select(
            'c.person_id',
            'c.id',
            'c.group',
            'p.name',
            'p.date_birthday',
            'p.contact',
            'p.parish',
        )
        ->join('people as p', 'p.id', '=', 'c.person_id')
        ->where('camp_id', $id)->get();
        return $campers;
    }

    public function getServants($id)
    {
        $servants = DB::table('servants as s')->select(
            's.person_id',
            's.id',
            's.group',
            's.sector',
            'p.name',
            'p.date_birthday',
            'p.contact',
            'p.parish',
        )
        ->join('people as p', 'p.id', '=', 's.person_id')
        ->where('camp_id', $id)->get();
        return $servants;
    }

    public function getNoCampers($id)
    {
        $campers = DB::table('people as p')
        ->whereNotIn('p.id', DB::table('campers')->select('person_id')->where('camp_id', '=' , $id))->get();
        return $campers;
    }

    public function getNoServantsForFac($id)
    {
        $servants = DB::table('people as p')
        ->select(
            'p.name',
            'p.date_birthday',
            'p.contact',
            'p.parish',
            'p.id'
        )
        ->join('campers as ca', 'p.id', '=', 'ca.person_id')
        ->join('camps as c', 'c.id', '=', 'ca.camp_id')
        ->whereNotIn('p.id', DB::table('campers')->select('person_id')->where('camp_id', '=' , $id))
        ->whereNotIn('p.id', DB::table('servants')->select('person_id')->where('camp_id', '=' , $id))->get();
        return $servants;
    }

    public function getNoCampersSearch(Request $request, $id)
    {
        $campers = DB::table('people as p')
        ->where('p.name', 'LIKE', '%'.$request->search.'%')
        ->whereNotIn('p.id', DB::table('campers')->select('person_id')->where('camp_id', '=' , $id))->get();
        return $campers;
    }

    public function addCampers(Request $request, $id)
    {
        foreach($request->campers as $new){
            $camper = new Camper;
            $camper->person_id = $new;
            $camper->camp_id = $id;
            $camper->save();
        }
    }

    public function getCamper($id)
    {
        $camper = Camper::where('id', $id)->first();

        return $camper;
    }

    public function deleteCamper($camper)
    {
        $camper->delete();
    }

    public function changeGroup($camper, $group)
    {
        $camper->group = $group;
        $camper->update();
    }

}
