<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Picture;

class PeopleTableSeeder extends Seeder
{
    public function run()
    {
        $p1 = Person::create([ 'name' => 'Alice', 'age' => 25, 'location' => 'Tokyo' ]);
        Picture::create(['person_id' => $p1->id, 'url' => 'https://placekitten.com/400/400', 'order' => 0]);

        $p2 = Person::create([ 'name' => 'Bob', 'age' => 28, 'location' => 'Osaka' ]);
        Picture::create(['person_id' => $p2->id, 'url' => 'https://placekitten.com/401/401', 'order' => 0]);
    }
}
