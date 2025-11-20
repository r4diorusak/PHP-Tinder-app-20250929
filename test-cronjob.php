#!/usr/bin/env php
<?php

/**
 * Quick Test Script for Popular People Cronjob
 * 
 * Usage: php test-cronjob.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     CRONJOB TESTING - Popular People Email Notification      ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Check if admin email is configured
$adminEmail = env('ADMIN_EMAIL', 'admin@tinderclone.com');
echo "📧 Admin Email: {$adminEmail}\n";
echo "📮 Mail Driver: " . env('MAIL_MAILER', 'smtp') . "\n";
echo "🏠 Mail Host: " . env('MAIL_HOST', 'mailpit') . "\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "STEP 1: Creating Test Data\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Get or create a test person
$person = App\Models\Person::where('name', 'Test Popular Person')->first();

if (!$person) {
    echo "Creating new test person...\n";
    $person = App\Models\Person::create([
        'name' => 'Test Popular Person',
        'age' => 25,
        'gender' => 'female',
        'pictures' => ['https://i.pravatar.cc/300?img=1'],
        'location' => 'New York, USA',
        'bio' => 'Test person created for cronjob testing'
    ]);
    echo "✅ Created person: {$person->name} (ID: {$person->id})\n\n";
} else {
    echo "✅ Using existing person: {$person->name} (ID: {$person->id})\n\n";
}

// Check current likes
$currentLikes = $person->likedBy()->count();
echo "Current likes: {$currentLikes}\n";

if ($currentLikes < 50) {
    $needed = 51 - $currentLikes;
    echo "Need to add {$needed} more likes to reach threshold...\n\n";
    
    echo "Creating {$needed} likers";
    
    for ($i = 1; $i <= $needed; $i++) {
        $liker = App\Models\Person::firstOrCreate(
            ['name' => "Auto Liker " . uniqid()],
            [
                'age' => rand(20, 35),
                'gender' => ['male', 'female'][rand(0, 1)],
                'pictures' => ['https://i.pravatar.cc/300?img=' . rand(1, 70)],
                'location' => 'Test City, USA',
                'bio' => 'Auto-generated test liker'
            ]
        );
        
        // Attach like if not already liked
        if (!$person->likedBy()->where('liker_id', $liker->id)->exists()) {
            $person->likedBy()->attach($liker->id);
        }
        
        if ($i % 10 == 0 || $i == $needed) {
            echo ".";
        }
    }
    echo " ✅\n\n";
} else {
    echo "✅ Already has enough likes!\n\n";
}

// Refresh count
$totalLikes = $person->fresh()->likedBy()->count();
echo "═══════════════════════════════════════════════════════════════\n";
echo "FINAL LIKES COUNT: {$totalLikes} likes\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

if ($totalLikes >= 50) {
    echo "✅ Person meets threshold (50+ likes)\n\n";
} else {
    echo "❌ Person does NOT meet threshold yet\n\n";
    exit(1);
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "STEP 2: Testing Email Configuration\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

try {
    echo "Testing email connection...\n";
    Illuminate\Support\Facades\Mail::raw(
        "This is a test email from PHP Tinder App cronjob testing script.",
        function ($message) use ($adminEmail) {
            $message->to($adminEmail)
                ->subject('🧪 Test Email - Cronjob Testing');
        }
    );
    echo "✅ Test email sent successfully!\n\n";
} catch (\Exception $e) {
    echo "❌ Email test failed: " . $e->getMessage() . "\n\n";
    echo "Please check your .env email configuration.\n";
    echo "Continuing with cronjob test anyway...\n\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "STEP 3: Running Cronjob Command\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "Executing: php artisan people:check-popular\n\n";
echo "───────────────────────────────────────────────────────────────\n";

// Run the command and capture output
$exitCode = Artisan::call('people:check-popular');
$output = Artisan::output();

echo $output;
echo "───────────────────────────────────────────────────────────────\n\n";

if ($exitCode === 0) {
    echo "✅ Command executed successfully!\n\n";
} else {
    echo "❌ Command failed with exit code: {$exitCode}\n\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "STEP 4: Verification\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "Please check your email inbox:\n";
echo "  📧 Email: {$adminEmail}\n\n";

if (env('MAIL_MAILER') === 'smtp' && env('MAIL_HOST') === 'sandbox.smtp.mailtrap.io') {
    echo "  🔗 Mailtrap: https://mailtrap.io/inboxes\n\n";
} elseif (env('MAIL_MAILER') === 'smtp' && env('MAIL_HOST') === '127.0.0.1') {
    echo "  🔗 Mailpit: http://localhost:8025\n\n";
}

echo "Expected email subject:\n";
echo "  🔥 Popular Person Alert: {$person->name} has {$totalLikes} likes!\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "TEST COMPLETE!\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "📋 Summary:\n";
echo "  • Person tested: {$person->name} (ID: {$person->id})\n";
echo "  • Total likes: {$totalLikes}\n";
echo "  • Admin email: {$adminEmail}\n";
echo "  • Command exit code: {$exitCode}\n\n";

echo "Next steps:\n";
echo "  1. Check your email inbox for the notification\n";
echo "  2. Verify email content is correct\n";
echo "  3. Setup Laravel scheduler for automatic execution\n";
echo "  4. Monitor logs: storage/logs/laravel.log\n\n";

echo "For more details, see: CRONJOB_TESTING.md\n\n";
