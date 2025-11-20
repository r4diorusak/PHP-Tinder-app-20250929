# 🚀 Quick Start - Testing Cronjob

## TL;DR - Cara Tercepat Testing

### 1️⃣ Setup Email - Gmail SMTP

**Gmail SMTP Configuration (Production Ready)**

**Step 1: Generate App Password**
1. Go to Google Account Security: https://myaccount.google.com/security
2. Enable "2-Step Verification" (if not already enabled)
3. Scroll down and click "App passwords" or visit: https://myaccount.google.com/apppasswords
4. Select app: "Mail"
5. Select device: "Other (Custom name)" → type "Laravel"
6. Click "Generate"
7. Copy the 16-character password (e.g., `abcd efgh ijkl mnop`)

**Step 2: Update `.env`**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_16_character_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
ADMIN_EMAIL=your_email@gmail.com
```

**Example:**
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

**Important Notes:**
- ⚠️ Use App Password, NOT your regular Gmail password
- ⚠️ Remove spaces from the 16-character password
- ⚠️ 2-Step Verification must be enabled
- ✅ Emails will be sent to your real Gmail inbox
- ✅ Works in production environment

---

### 2️⃣ Run Test Script

```bash
# Run automated testing script
php test-cronjob.php
```

This script will:
- ✅ Create/use test person automatically
- ✅ Add 51 likes automatically
- ✅ Test email configuration
- ✅ Execute cronjob command
- ✅ Display summary results

---

### 3️⃣ Check Email Inbox

Check your Gmail inbox at: **your_email@gmail.com**

Expected email:
- **Subject:** `🔥 Popular Person Alert: [Name] has [X] likes!`
- **Content:** Person details with ID, name, location, age, and total likes
- **From:** Your configured MAIL_FROM_ADDRESS

---

## Manual Testing (Alternative)

### Method 1: Direct Command Test

```bash
# 1. Add dummy data
php artisan tinker

# In tinker console:
$person = App\Models\Person::first();
for ($i = 1; $i <= 51; $i++) {
    $liker = App\Models\Person::create([
        'name' => "Liker $i",
        'age' => 25,
        'gender' => 'male',
        'pictures' => ['https://i.pravatar.cc/300'],
        'location' => 'Test City',
        'bio' => 'Test'
    ]);
    $liker->liked()->attach($person->id);
}
exit

# 2. Run cronjob command
php artisan people:check-popular
```

### Method 2: Via Mobile Demo

1. Open `http://127.0.0.1:8000/mobile-demo.html`
2. Like the same person multiple times (with different users)
3. After 50+ likes, run: `php artisan people:check-popular`

### Method 3: Via Swagger API

1. Open `http://127.0.0.1:8000/api/documentation`
2. Scroll to "Admin" section
3. Click `POST /api/people/check-popular`
4. Click "Try it out" → "Execute"
5. Check response and your Gmail inbox

---

## Setup Automated Cronjob (Production)

### Laravel Scheduler (Already Configured)

The command runs automatically every day at 09:00 AM.

To activate the scheduler, add to system cron:

**Linux/Mac:**
```bash
crontab -e

# Add this line:
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Windows Task Scheduler:**
1. Open Task Scheduler
2. Create Basic Task → Name: "Laravel Scheduler"
3. Trigger: Daily at 9:00 AM  
4. Action: Start Program
   - Program: `C:\path\to\php.exe`
   - Arguments: `artisan schedule:run`
   - Start in: `D:\Documents\GitHub\CV Project\PHP-Tinder-Laravel`

**Verify Scheduler:**
```bash
# List all scheduled tasks
php artisan schedule:list

# Should show:
# 0 9 * * * php artisan people:check-popular
```

---

## Troubleshooting

### ❌ Email not sending?

**Check Gmail configuration:**
```bash
# Clear config cache
php artisan config:clear

# Test email manually
php artisan tinker
Mail::raw('Test email from Laravel', function($msg) {
    $msg->to(env('ADMIN_EMAIL'))->subject('Test Email');
});
# Press Ctrl+D to exit tinker
```

**Common issues:**
- ⚠️ Using regular password instead of App Password
- ⚠️ 2-Step Verification not enabled
- ⚠️ Wrong Gmail address
- ⚠️ Firewall blocking port 587

### ❌ Command not found?

```bash
# List all artisan commands
php artisan list | grep popular

# Should show: people:check-popular
```

If not found:
```bash
# Clear cached commands
php artisan clear-compiled
composer dump-autoload
```

### ❌ No person with 50+ likes?

```bash
# Run automated test script
php test-cronjob.php

# Or create manually via tinker
php artisan tinker
$person = App\Models\Person::first();
for ($i = 1; $i <= 51; $i++) {
    $user = App\Models\Person::find($i) ?? App\Models\Person::create([
        'name' => "User $i", 'age' => 25, 'gender' => 'male',
        'pictures' => ['https://i.pravatar.cc/300'], 'location' => 'Test'
    ]);
    $user->liked()->attach($person->id);
}
```

### ❌ Gmail blocking login?

1. Check "Less secure app access" (if available)
2. Verify App Password is correct (16 characters, no spaces)
3. Check Gmail "Security" tab for blocked sign-in attempts
4. Try generating a new App Password

---

## ✅ Success Checklist

- [ ] Gmail SMTP configured in `.env` with App Password
- [ ] 2-Step Verification enabled on Google Account
- [ ] Config cache cleared: `php artisan config:clear`
- [ ] Command exists: `php artisan people:check-popular`
- [ ] Test script runs successfully: `php test-cronjob.php`
- [ ] Email received in Gmail inbox
- [ ] Email subject: "🔥 Popular Person Alert: [Name] has [X] likes!"
- [ ] Email contains person details (ID, name, likes count)
- [ ] Laravel scheduler configured for production (optional)

---

## 📚 Complete Documentation

See `CRONJOB_TESTING.md` for:
- Detailed Gmail setup with screenshots
- Alternative email providers (Mailtrap, Mailpit)
- Complete troubleshooting guide
- Testing scenarios and edge cases
- Production deployment guide
- Monitoring and logging setup

---

## 🎯 Quick Testing Checklist

Fast verification steps:

```bash
# 1. Verify command exists
php artisan people:check-popular

# 2. Check scheduler configuration
php artisan schedule:list

# 3. Test Gmail connection
php artisan tinker
Mail::raw('Test', fn($m) => $m->to(env('ADMIN_EMAIL'))->subject('Test'));
# Ctrl+D to exit

# 4. Run full automated test
php test-cronjob.php

# 5. Check Gmail inbox
# Login to Gmail and look for email with subject:
# "🔥 Popular Person Alert: [Name] has [X] likes!"
```

**Expected Output from `php test-cronjob.php`:**
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
✅ Command executed successfully!

TEST COMPLETE!
```

Done! 🎉

---

**Created by:** Khairul Adha  
**Email:** r4dioz.88@gmail.com  
**GitHub:** r4diorusak
