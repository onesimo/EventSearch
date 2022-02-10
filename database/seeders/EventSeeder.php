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
        $event = json_decode($json);
  
        foreach ($event as $value) {
            Event::create([
                "id" => $value->id,
                "name" => $value->name,
                "city" => $value->city,
                "country" => $value->country,
                "startDate" => $value->startDate,
                "endDate" => $value->endDate,
            ]);
        }
    }
}
