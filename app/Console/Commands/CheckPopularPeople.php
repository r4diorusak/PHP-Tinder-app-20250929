<?php

namespace App\Console\Commands;

use App\Models\Person;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckPopularPeople extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'people:check-popular';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for people with more than 50 likes and send email notification to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get people with more than 50 likes
        $popularPeople = Person::has('likedBy', '>=', 50)->get();

        if ($popularPeople->isEmpty()) {
            $this->info('No people with 50+ likes found.');
            return Command::SUCCESS;
        }

        foreach ($popularPeople as $person) {
            $likesCount = $person->likedBy()->count();
            
            $this->info("Found popular person: {$person->name} with {$likesCount} likes");
            
            // Send email to admin
            try {
                Mail::raw(
                    "Person {$person->name} (ID: {$person->id}) has received {$likesCount} likes!\n\n" .
                    "Details:\n" .
                    "- Age: {$person->age}\n" .
                    "- Location: {$person->location}\n" .
                    "- Bio: {$person->bio}\n\n" .
                    "This person is very popular on the platform!",
                    function ($message) use ($person, $likesCount) {
                        $message->to(env('ADMIN_EMAIL', 'admin@tinderclone.com'))
                            ->subject("🔥 Popular Person Alert: {$person->name} has {$likesCount} likes!");
                    }
                );
                
                $this->info("Email sent to admin for {$person->name}");
            } catch (\Exception $e) {
                $this->error("Failed to send email: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
