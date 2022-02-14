<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use File;

class EventSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Event::truncate();

        $json = File::get("database/data/events.json");
        $events = json_decode($json,true);
        
        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
