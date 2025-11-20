<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstNames = [
            'male' => ['James', 'Michael', 'David', 'Ryan', 'Daniel', 'Alexander', 'Benjamin', 'William', 'Matthew', 'Joseph', 
                       'Andrew', 'Christopher', 'Joshua', 'Ethan', 'Nathan', 'Tyler', 'Kevin', 'Brandon', 'Jason', 'Justin',
                       'Aaron', 'Adam', 'Brian', 'Dylan', 'Eric', 'Gabriel', 'Henry', 'Isaac', 'Jack', 'Kyle',
                       'Lucas', 'Mason', 'Noah', 'Oliver', 'Patrick', 'Samuel', 'Thomas', 'Victor', 'Zachary', 'Adrian'],
            'female' => ['Sarah', 'Emma', 'Olivia', 'Sophie', 'Isabella', 'Mia', 'Charlotte', 'Amelia', 'Emily', 'Ava',
                         'Madison', 'Abigail', 'Elizabeth', 'Sofia', 'Grace', 'Hannah', 'Victoria', 'Lily', 'Natalie', 'Chloe',
                         'Zoe', 'Ella', 'Aria', 'Scarlett', 'Penelope', 'Layla', 'Riley', 'Nora', 'Hazel', 'Luna',
                         'Stella', 'Aurora', 'Maya', 'Lucy', 'Anna', 'Caroline', 'Julia', 'Ruby', 'Alice', 'Violet']
        ];

        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
                      'Wilson', 'Anderson', 'Taylor', 'Thomas', 'Moore', 'Jackson', 'Martin', 'Lee', 'Thompson', 'White',
                      'Harris', 'Clark', 'Lewis', 'Robinson', 'Walker', 'Young', 'Allen', 'King', 'Wright', 'Scott',
                      'Green', 'Baker', 'Adams', 'Nelson', 'Hill', 'Carter', 'Mitchell', 'Roberts', 'Turner', 'Phillips'];

        $cities = [
            'New York, USA', 'Los Angeles, USA', 'Chicago, USA', 'Miami, USA', 'San Francisco, USA', 'Seattle, USA',
            'Boston, USA', 'Austin, USA', 'Denver, USA', 'Portland, USA', 'London, UK', 'Paris, France', 'Berlin, Germany',
            'Amsterdam, Netherlands', 'Barcelona, Spain', 'Rome, Italy', 'Madrid, Spain', 'Tokyo, Japan', 'Seoul, South Korea',
            'Sydney, Australia', 'Melbourne, Australia', 'Toronto, Canada', 'Vancouver, Canada', 'Singapore', 'Dubai, UAE',
            'Bangkok, Thailand', 'Hong Kong', 'Stockholm, Sweden', 'Copenhagen, Denmark', 'Oslo, Norway'
        ];

        $biosMale = [
            'Software engineer by day, chef by night',
            'Fitness enthusiast and dog lover',
            'Musician and coffee addict',
            'Entrepreneur and travel junkie',
            'Tech lead and gamer',
            'K-pop dancer and chef',
            'DJ and vinyl collector',
            'Photographer and adventure seeker',
            'Architect with a passion for design',
            'Marketing guru who loves sports',
            'Writer and book collector',
            'Personal trainer and nutrition coach',
            'Professional cyclist and foodie',
            'Video game developer and anime fan',
            'Financial analyst who travels',
            'Lawyer by day, musician by night',
            'Doctor with a love for hiking',
            'Teacher and comedy fan',
            'Chef and wine enthusiast',
            'Pilot who loves photography'
        ];

        $biosFemale = [
            'Love hiking, coffee, and adventure!',
            'Artist, traveler, foodie',
            'Beach lover, yoga instructor',
            'Designer, dancer, dreamer',
            'Model and photographer',
            'Anime fan and language teacher',
            'Fashion designer and wine enthusiast',
            'Photographer and cyclist',
            'Interior designer who loves travel',
            'Marketing manager and bookworm',
            'Nurse with a passion for fitness',
            'Teacher and cat lover',
            'Graphic designer and coffee addict',
            'Journalist and world traveler',
            'Chef and restaurant owner',
            'Social media influencer',
            'Accountant who loves painting',
            'Lawyer and marathon runner',
            'Dentist with a green thumb',
            'Architect who enjoys hiking'
        ];

        $people = [];

        // Generate 80 people (40 male, 40 female)
        for ($i = 0; $i < 80; $i++) {
            $gender = $i % 2 === 0 ? 'male' : 'female';
            $firstName = $firstNames[$gender][array_rand($firstNames[$gender])];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            
            // Generate random pictures
            $numPics = rand(2, 4);
            $pictures = [];
            $startImg = $gender === 'male' ? rand(11, 50) : rand(1, 40);
            for ($j = 0; $j < $numPics; $j++) {
                $pictures[] = "https://i.pravatar.cc/300?img=" . ($startImg + $j);
            }

            $people[] = [
                'name' => $name,
                'age' => rand(21, 35),
                'location' => $cities[array_rand($cities)],
                'bio' => $gender === 'male' ? $biosMale[array_rand($biosMale)] : $biosFemale[array_rand($biosFemale)],
                'gender' => $gender,
                'pictures' => $pictures
            ];
        }

        foreach ($people as $person) {
            Person::create($person);
        }
    }
}
