# Cronjob Testing Guide

## Feature: Email Notification for Popular Person (50+ Likes)

This application includes a cronjob that automatically sends an email to the admin when a person receives more than 50 likes.

---

## 📋 Testing Preparation

### 1. Email Configuration (.env)

Edit the `.env` file and add/update email configuration:

#### Using Gmail SMTP (Production Ready)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_16_character_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"

# Admin email that will receive notifications
ADMIN_EMAIL=your_email@gmail.com
```

**How to get Gmail App Password:**
1. Go to Google Account Security: https://myaccount.google.com/security
2. Enable "2-Step Verification" (required)
3. Scroll down and click "App passwords" or visit: https://myaccount.google.com/apppasswords
4. Select app: "Mail"
5. Select device: "Other (Custom name)" → type "Laravel"
6. Click "Generate"
7. Copy the 16-character password (e.g., `abcd efgh ijkl mnop`)
8. Remove spaces and paste into MAIL_PASSWORD

**Important Notes for Gmail:**
- ⚠️ Use App Password, NOT your regular Gmail password
- ⚠️ Remove all spaces from the 16-character password
- ⚠️ 2-Step Verification MUST be enabled
- ✅ Emails will be sent to your real Gmail inbox
- ✅ Works in production environment
- ✅ Free for moderate usage

**Example Configuration:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=prodevku888@gmail.com
MAIL_PASSWORD=vrkzqvxxotxpoxtl
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="prodevku888@gmail.com"
MAIL_FROM_NAME="PHP Tinder app 20250929"
ADMIN_EMAIL=prodevku888@gmail.com
```

---

## 🧪 Testing Methods

### Method 1: Automated Testing Script (Recommended)

The project includes an automated testing script `test-cronjob.php`.

**Run the script:**
```bash
php test-cronjob.php
```

**What it does:**
1. ✅ Creates/uses a test person automatically
2. ✅ Generates 51 likes automatically
3. ✅ Tests email configuration
4. ✅ Executes the cronjob command
5. ✅ Displays detailed results and verification steps

**Expected Output:**
```
╔══════════════════════════════════════════════════════════════╗
║     CRONJOB TESTING - Popular People Email Notification      ║
╚══════════════════════════════════════════════════════════════╝

📧 Admin Email: your_email@gmail.com
📮 Mail Driver: smtp
🏠 Mail Host: smtp.gmail.com

STEP 1: Creating Test Data
✅ Using existing person: Test Popular Person (ID: 81)
✅ Already has enough likes!

FINAL LIKES COUNT: 51 likes

STEP 2: Testing Email Configuration
✅ Test email sent successfully!

STEP 3: Running Cronjob Command
Found popular person: Test Popular Person with 51 likes
Email sent to admin for Test Popular Person
✅ Command executed successfully!

TEST COMPLETE!
📧 Check your Gmail inbox for the notification email
```

---

### Method 2: Manual Database Testing

#### Step 1: Create test data with many likes
```bash
php artisan tinker
```

In tinker console:
```php
// Get the first person
$person = App\Models\Person::first();

// Create 51 dummy persons who like this person
for ($i = 1; $i <= 51; $i++) {
    $liker = App\Models\Person::create([
        'name' => "Liker $i",
        'age' => rand(20, 35),
        'gender' => ['male', 'female'][rand(0, 1)],
        'pictures' => ['https://i.pravatar.cc/300?img=' . rand(1, 70)],
        'location' => 'Test City, USA',
        'bio' => 'Test person who likes popular people'
    ]);
    
    // Like the person
    $liker->liked()->attach($person->id);
}

// Check how many likes
$person->likedBy()->count(); // Should be 51 or more

// Exit tinker
exit
```

#### Step 2: Test command manually
```bash
php artisan people:check-popular
```

**Expected Output:**
```
Found popular person: [Name] with [51+] likes
Email sent to admin for [Name]
```

#### Step 3: Check email
- Open Gmail inbox at the configured ADMIN_EMAIL
- Look for email with subject: "🔥 Popular Person Alert: [Name] has [X] likes!"

---

### Method 3: Testing via Swagger API

#### Step 1: Open Swagger Documentation
```
http://127.0.0.1:8000/api/documentation
```

#### Step 2: Find the Admin section
Scroll down to **"Admin"** section

#### Step 3: Test the endpoint
1. Click on `POST /api/people/check-popular`
2. Click **"Try it out"**
3. Click **"Execute"**

**Expected Response:**
```json
{
  "success": true,
  "message": "Found 1 popular people and sent notifications",
  "data": {
    "count": 1,
    "people": [
      {
        "id": 81,
        "name": "Test Popular Person",
        "likes_count": 51
      }
    ],
    "admin_email": "your_email@gmail.com"
  }
}
```

#### Step 4: Check Gmail inbox
Check your Gmail for the notification email

---

### Method 4: Testing with Mobile Web Interface

#### Step 1: Open Mobile Demo
```
http://127.0.0.1:8000/mobile-demo.html
```

#### Step 2: Like a person multiple times
- Use the like button to like the same person
- The mobile interface has 30% match probability
- Repeat with different users until one person has 50+ likes

#### Step 3: Trigger the check
```bash
php artisan people:check-popular
```

Or via Swagger API at:
```
http://127.0.0.1:8000/api/documentation
```

---

## ⏰ Setup Automated Cronjob (Production)

### Laravel Scheduler (Already Configured)

The command is already configured to run automatically every day at 09:00 AM.

**File:** `app/Console/Kernel.php`
```php
protected function schedule(Schedule $schedule): void
{
    $schedule->command('people:check-popular')->dailyAt('09:00');
}
```

### Activate Scheduler

To make the scheduler work, you need to add one cron entry:

**Linux/Mac:**
```bash
# Edit crontab
crontab -e

# Add this single line:
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Replace `/path-to-your-project` with your actual project path, e.g.:
```bash
* * * * * cd /var/www/tinder-app && php artisan schedule:run >> /dev/null 2>&1
```

**Windows Task Scheduler:**

1. Open **Task Scheduler**
2. Click **"Create Basic Task"**
3. Name: `Laravel Scheduler`
4. Description: `Run Laravel scheduled commands`
5. Trigger: **Daily**
6. Start time: **Any time** (the scheduler itself will handle timing)
7. Repeat task every: **1 minute** (important!)
8. Action: **Start a program**
   - Program/script: `C:\xampp\php\php.exe` (adjust to your PHP path)
   - Add arguments: `artisan schedule:run`
   - Start in: `D:\Documents\GitHub\CV Project\PHP-Tinder-Laravel`

**Alternative Windows PowerShell Script:**

Create file `run-scheduler.ps1`:
```powershell
cd "D:\Documents\GitHub\CV Project\PHP-Tinder-Laravel"
php artisan schedule:run
```

Then schedule this script to run every minute.

### Verify Scheduler Configuration

```bash
# List all scheduled commands
php artisan schedule:list

# Should show:
# 0 9 * * * php artisan people:check-popular ............. Next Due: [Date/Time]
```

### Test Scheduler Manually

```bash
# Run scheduler once (executes all due commands)
php artisan schedule:run

# Run specific command directly
php artisan people:check-popular
```

---

## 🔍 Verification Checklist

### ✅ Email Configuration Test
```bash
php artisan tinker

# Test Gmail connection
Mail::raw('Test email from PHP Tinder App', function($msg) {
    $msg->to(env('ADMIN_EMAIL'))
        ->subject('Test Email - Cronjob Setup');
});

# Press Ctrl+D to exit tinker
```

Check your Gmail inbox for the test email.

### ✅ Command Registration Check
```bash
# List all artisan commands
php artisan list | grep people

# Should show:
# people:check-popular    Check for popular people (50+ likes) and notify admin
```

### ✅ Database Verification
```bash
php artisan tinker

# Check person with most likes
$popular = App\Models\Person::withCount('likedBy')
    ->orderBy('liked_by_count', 'desc')
    ->first();
    
echo "Most popular: {$popular->name} with {$popular->liked_by_count} likes";

exit
```

### ✅ Scheduler Verification
```bash
# List scheduled tasks
php artisan schedule:list

# Test scheduler (runs all due tasks)
php artisan schedule:test
```

---

## 📧 Expected Email Content

**Subject:**
```
🔥 Popular Person Alert: [Name] has [X] likes!
```

**Body:**
```
Person [Name] (ID: [ID]) has received [X] likes!

Details:
- Age: [Age]
- Location: [Location]
- Bio: [Bio]

This person is very popular on the platform!
```

---

## 🐛 Troubleshooting

### Issue: Email not sending

**Symptoms:**
- Command runs without errors
- No email received in Gmail inbox

**Solutions:**

1. **Clear configuration cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

2. **Test Gmail connection:**
```bash
php artisan tinker

# Send test email
Mail::raw('Test from Laravel', function($msg) {
    $msg->to(env('ADMIN_EMAIL'))->subject('Gmail Test');
});

# Check for errors in output
exit
```

3. **Verify Gmail App Password:**
   - Make sure you're using App Password, not regular password
   - Password should be 16 characters without spaces
   - 2-Step Verification must be enabled
   - Try generating a new App Password

4. **Check Gmail Security:**
   - Visit: https://myaccount.google.com/security
   - Check for "Critical security alert" notifications
   - Check "Recent security activity" for blocked sign-ins
   - Allow "Less secure app access" if prompted (some accounts)

5. **Check Laravel logs:**
```bash
# View last 50 lines
tail -n 50 storage/logs/laravel.log

# Or on Windows PowerShell:
Get-Content storage/logs/laravel.log -Tail 50
```

6. **Verify .env configuration:**
```bash
# Display current mail config
php artisan tinker
config('mail')
exit
```

### Issue: Command not found

**Symptoms:**
```bash
php artisan people:check-popular
# Command "people:check-popular" is not defined.
```

**Solutions:**

1. **Clear compiled files:**
```bash
php artisan clear-compiled
composer dump-autoload
php artisan cache:clear
```

2. **Verify command file exists:**
Check that `app/Console/Commands/CheckPopularPeople.php` exists

3. **Verify Kernel registration:**
Check `app/Console/Kernel.php` has the command loaded

### Issue: Gmail "Less secure app" error

**Solution:**
- This error occurs when using regular password instead of App Password
- Always use App Password generated from Google Account Security settings
- Regular passwords are not supported for Laravel mail

### Issue: Port 587 blocked

**Symptoms:**
- Connection timeout
- "Failed to connect to smtp.gmail.com"

**Solutions:**
1. Check firewall settings
2. Try alternative port 465 with SSL:
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

### Issue: No popular people found

**Symptoms:**
```bash
php artisan people:check-popular
# No output, no email sent
```

**Solution:**
Create test data using the automated script:
```bash
php test-cronjob.php
```

Or manually via tinker:
```bash
php artisan tinker

$person = App\Models\Person::first();
for ($i = 1; $i <= 51; $i++) {
    $user = App\Models\Person::create([
        'name' => "User $i",
        'age' => 25,
        'gender' => 'male',
        'pictures' => ['https://i.pravatar.cc/300'],
        'location' => 'Test City'
    ]);
    $user->liked()->attach($person->id);
}

exit
```

### Issue: Scheduler not running

**Symptoms:**
- Cronjob doesn't execute at scheduled time
- Manual command works, but automation doesn't

**Solutions:**

1. **Verify cron/Task Scheduler is set up:**
```bash
# Linux/Mac: Check crontab
crontab -l

# Should show:
# * * * * * cd /path-to-project && php artisan schedule:run
```

2. **Test scheduler manually:**
```bash
php artisan schedule:run
```

3. **Check scheduler list:**
```bash
php artisan schedule:list
```

4. **Enable verbose logging:**
Add to `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

---

## 📊 Production Monitoring

### Setup Logging

Edit `app/Console/Commands/CheckPopularPeople.php` to add detailed logging:

```php
use Illuminate\Support\Facades\Log;

public function handle()
{
    Log::info('CheckPopularPeople: Starting check...');
    
    $popularPeople = Person::has('likedBy', '>=', 50)
        ->withCount('likedBy')
        ->get();

    Log::info("CheckPopularPeople: Found {$popularPeople->count()} popular people");

    foreach ($popularPeople as $person) {
        // Send email...
        Log::info("CheckPopularPeople: Email sent for {$person->name} (ID: {$person->id})");
    }
    
    Log::info('CheckPopularPeople: Check completed');
}
```

### Monitor Scheduler Logs

**View scheduler execution logs:**
```bash
# Real-time log monitoring
tail -f storage/logs/laravel.log | grep "CheckPopularPeople"

# On Windows PowerShell:
Get-Content storage/logs/laravel.log -Wait | Select-String "CheckPopularPeople"
```

### Email Delivery Tracking

**Check if emails are being sent:**
```sql
-- Count likes per person (find popular people)
SELECT 
    p.id,
    p.name,
    COUNT(l.id) as likes_count
FROM people p
LEFT JOIN likes l ON p.id = l.liked_id
GROUP BY p.id, p.name
HAVING likes_count >= 50
ORDER BY likes_count DESC;
```

### Setup Alerts

Create a monitoring script `monitor-cronjob.sh`:

```bash
#!/bin/bash
LOG_FILE="storage/logs/laravel.log"
ERROR_EMAIL="admin@yourdomain.com"

# Check if cronjob ran in last 25 hours
LAST_RUN=$(grep "CheckPopularPeople" "$LOG_FILE" | tail -1)

if [ -z "$LAST_RUN" ]; then
    echo "WARNING: Cronjob hasn't run!" | mail -s "Cronjob Alert" "$ERROR_EMAIL"
fi
```

### Health Check Endpoint

Add to `routes/api.php`:
```php
Route::get('/health/cronjob', function() {
    $lastLog = \Illuminate\Support\Facades\Storage::disk('local')
        ->get('logs/laravel.log');
    
    $hasRunToday = str_contains($lastLog, 'CheckPopularPeople');
    
    return response()->json([
        'status' => $hasRunToday ? 'ok' : 'warning',
        'last_run' => $hasRunToday ? 'today' : 'not today',
        'timestamp' => now()
    ]);
});
```

Access: `http://yourdomain.com/api/health/cronjob`

---

## 🎯 Testing Scenarios

### Scenario 1: Single Popular Person
**Setup:**
- Create 1 person with exactly 51 likes

**Expected Result:**
- 1 email sent to admin
- Email subject: "🔥 Popular Person Alert: [Name] has 51 likes!"
- Command output: "Found popular person: [Name] with 51 likes"

**Test:**
```bash
php test-cronjob.php
```

---

### Scenario 2: Multiple Popular People
**Setup:**
- Create 3 persons, each with 50+ likes

**Expected Result:**
- 3 separate emails sent
- Each email contains different person's details

**Test:**
```bash
php artisan tinker

# Create 3 popular people
for ($j = 1; $j <= 3; $j++) {
    $person = App\Models\Person::create([
        'name' => "Popular Person $j",
        'age' => 25,
        'gender' => 'female',
        'pictures' => ['https://i.pravatar.cc/300'],
        'location' => 'New York'
    ]);
    
    for ($i = 1; $i <= 51; $i++) {
        $liker = App\Models\Person::create([
            'name' => "Liker $i for Person $j",
            'age' => 25,
            'gender' => 'male',
            'pictures' => ['https://i.pravatar.cc/300'],
            'location' => 'Test'
        ]);
        $liker->liked()->attach($person->id);
    }
}

exit

# Run command
php artisan people:check-popular
```

---

### Scenario 3: No Popular People
**Setup:**
- All persons have < 50 likes

**Expected Result:**
- No email sent
- Command runs successfully with no output
- No errors in logs

**Test:**
```bash
# Clear all likes first
php artisan tinker
DB::table('likes')->truncate();
exit

# Run command
php artisan people:check-popular
# Should complete silently with no emails
```

---

### Scenario 4: Edge Case (Exactly 50 likes)
**Setup:**
- Create person with exactly 50 likes

**Expected Result:**
- 1 email sent (because logic is >= 50)
- Email shows "50 likes"

**Test:**
```bash
php artisan tinker

$person = App\Models\Person::first();
$person->likedBy()->detach(); // Clear existing likes

for ($i = 1; $i <= 50; $i++) {
    $liker = App\Models\Person::find($i);
    if (!$liker) {
        $liker = App\Models\Person::create([
            'name' => "Liker $i",
            'age' => 25,
            'gender' => 'male',
            'pictures' => ['https://i.pravatar.cc/300'],
            'location' => 'Test'
        ]);
    }
    $liker->liked()->attach($person->id);
}

echo "Created exactly " . $person->likedBy()->count() . " likes";
exit

php artisan people:check-popular
```

---

### Scenario 5: Performance Test
**Setup:**
- Create 1000 people
- 10 people with 50+ likes
- Rest with varying likes (0-49)

**Expected Result:**
- Command completes in < 5 seconds
- Only 10 emails sent
- No memory issues

**Test:**
```bash
php artisan tinker

// This will take a few minutes
for ($i = 1; $i <= 1000; $i++) {
    $person = App\Models\Person::create([
        'name' => "Person $i",
        'age' => rand(20, 35),
        'gender' => ['male', 'female'][rand(0, 1)],
        'pictures' => ['https://i.pravatar.cc/300'],
        'location' => 'Test City'
    ]);
    
    // First 10 get 51+ likes
    $likesCount = ($i <= 10) ? 51 : rand(0, 49);
    
    for ($j = 1; $j <= $likesCount; $j++) {
        $liker = App\Models\Person::find(1000 + $j) ?? App\Models\Person::create([
            'name' => "Liker $j",
            'age' => 25,
            'gender' => 'male',
            'pictures' => ['https://i.pravatar.cc/300'],
            'location' => 'Test'
        ]);
        $liker->liked()->attach($person->id);
    }
    
    if ($i % 100 == 0) echo "Created $i people...\n";
}

exit

# Time the command
php artisan people:check-popular
```

---

## ✨ Success Criteria

### ✅ Configuration Validation
- [ ] `.env` file has correct Gmail SMTP settings
- [ ] Gmail App Password generated and configured (16 characters, no spaces)
- [ ] 2-Step Verification enabled on Google Account
- [ ] ADMIN_EMAIL is set to a valid Gmail address
- [ ] Config cache cleared: `php artisan config:clear`

### ✅ Command Validation
- [ ] Command registered: `php artisan list | grep people:check-popular`
- [ ] Command runs without errors: `php artisan people:check-popular`
- [ ] Command finds popular people (if test data exists)
- [ ] Command output shows "Email sent to admin for [Name]"

### ✅ Email Delivery
- [ ] Test email sent successfully via tinker
- [ ] Email received in Gmail inbox (check spam folder too)
- [ ] Email subject: "🔥 Popular Person Alert: [Name] has [X] likes!"
- [ ] Email body contains person details (ID, name, likes count, location, age)
- [ ] Email sent from configured MAIL_FROM_ADDRESS

### ✅ Automated Testing
- [ ] `test-cronjob.php` script runs successfully
- [ ] Script creates test person with 51+ likes
- [ ] Script tests email configuration
- [ ] Script executes command
- [ ] Script displays success confirmation

### ✅ Scheduler Configuration
- [ ] Scheduler command listed: `php artisan schedule:list`
- [ ] Shows: `0 9 * * * php artisan people:check-popular`
- [ ] Cron/Task Scheduler configured for production
- [ ] Scheduler runs manually: `php artisan schedule:run`

### ✅ Database Validation
- [ ] Test person exists with 50+ likes
- [ ] Query returns popular people: `Person::has('likedBy', '>=', 50)->get()`
- [ ] Likes relationship working correctly
- [ ] No duplicate likes in database

### ✅ Production Readiness
- [ ] Logs are being written to `storage/logs/laravel.log`
- [ ] No errors in logs after command execution
- [ ] Email credentials secured (not in version control)
- [ ] Backup email address configured
- [ ] Monitoring setup (optional but recommended)

---

## 📞 Support & Troubleshooting

If you encounter issues:

1. **Check Laravel Logs:**
```bash
tail -f storage/logs/laravel.log
```

2. **Verify Configuration:**
```bash
php artisan config:clear
php artisan tinker
dd(config('mail'));
```

3. **Test Email Directly:**
```bash
php artisan tinker
Mail::raw('Test', fn($m) => $m->to(env('ADMIN_EMAIL'))->subject('Test'));
```

4. **Check Database:**
```bash
php artisan tinker
App\Models\Person::has('likedBy', '>=', 50)->count();
```

5. **Review Documentation:**
   - Laravel Mail: https://laravel.com/docs/10.x/mail
   - Laravel Scheduler: https://laravel.com/docs/10.x/scheduling
   - Gmail App Passwords: https://support.google.com/accounts/answer/185833

---

## 📚 Additional Resources

### Quick Commands Reference
```bash
# Test cronjob
php test-cronjob.php

# Run command manually
php artisan people:check-popular

# List scheduled tasks
php artisan schedule:list

# Run scheduler
php artisan schedule:run

# Test email
php artisan tinker
Mail::raw('Test', fn($m) => $m->to(env('ADMIN_EMAIL'))->subject('Test'));

# Check popular people
php artisan tinker
Person::has('likedBy', '>=', 50)->with('likedBy')->get();

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### File Locations
- **Command:** `app/Console/Commands/CheckPopularPeople.php`
- **Kernel:** `app/Console/Kernel.php`
- **Config:** `config/mail.php`
- **Test Script:** `test-cronjob.php`
- **Logs:** `storage/logs/laravel.log`
- **Environment:** `.env`

### API Testing
- **Swagger UI:** `http://127.0.0.1:8000/api/documentation`
- **Endpoint:** `POST /api/people/check-popular`
- **Mobile Demo:** `http://127.0.0.1:8000/mobile-demo.html`

---

**Created by:** Khairul Adha  
**Email:** r4dioz.88@gmail.com  
**GitHub:** r4diorusak  
**Project:** PHP Tinder app 20250929
