<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Carbon;

class EventControllerOld extends Controller
{   
    const ALLOWED_FILTERS_LIKE = [
        'term' => ['city', 'country'],
        'name' => ['name']
    ];
    const ALLOWED_FILTERS_DATE = [
        'date' => ['startDate']
    ];

    public function list(Request $request)
    {   
        $fields = $request->validate([
            'term' => 'string',
            'date' => 'date_format:d-m-Y|after_or_equal:today',
            'name' => 'string'
        ]);

        //DB::enableQueryLog(); 
        //search allowed parameters in the Events model
        $events = Event::when(count($fields)>0, function ($query){  
            $query->whereDate('startDate','>',Carbon::today()); 
        })->where(function ($query) use ($fields) { 
            foreach ($fields as $column => $value) { 
                foreach (self::ALLOWED_FILTERS_LIKE[$column] ?? [] as $fieldName) {
                    $query->orWhere($fieldName, 'LIKE', '%'. $value . '%');
                } 
                foreach (self::ALLOWED_FILTERS_DATE[$column] ?? [] as $fieldName) {
                    $query->Where($fieldName, '=', $value);
                }
            } 

        })->paginate();
        //dd( Carbon::today());  
        return ($events);
    }
}
