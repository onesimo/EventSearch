<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function list(EventRequest $request)
    {
        $fields = $request->validated();
        
        $events = DB::table('events')
        ->whereDate('startDate', '>=', Carbon::today())
        //parameter term, search similar values in city and country
        ->when(isset($fields['term']), function ($query) use ($fields) {
            $query->where(function ($queryGroup) use ($fields) {
                $queryGroup->orWhere('city', 'LIKE', '%'. $fields['term'] . '%');
                $queryGroup->orWhere('country', 'LIKE', '%'. $fields['term'] . '%');
            });
        })
        //parameter date, search events equal to startDate
        ->when(isset($fields['date']), function ($query) use ($fields) {
            $query->Where('startDate', '=', Carbon::createFromFormat('d-m-Y', $fields['date'])->format('Y-m-d'));
        })
        ->simplePaginate();
         
        return $events;
    }
}
