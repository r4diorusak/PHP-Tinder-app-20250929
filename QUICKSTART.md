# 🚀 Quick Start Guide - PHP Tinder app 20250929

Panduan cepat untuk menjalankan aplikasi PHP Tinder app 20250929 dalam 10 menit!

## 📋 Prerequisites

- ✅ XAMPP (MySQL)
- ✅ PHP 8.1+
- ✅ Composer
- ✅ Web Browser (Chrome, Firefox, Safari, dll)

---

## 🎯 Backend Setup (5 menit)

### 1. Start XAMPP
```
1. Buka XAMPP Control Panel
2. Start Apache dan MySQL
```

### 2. Create Database
```
1. Buka http://localhost/phpmyadmin
2. Create database: "tinder_app"
```

### 3. Setup Laravel
```powershell
# Navigate to project
cd "d:\Documents\GitHub\CV Project\PHP-Tinder-Laravel"

# Install dependencies
composer install

# Copy environment file
Copy-Item .env.example .env

# Generate key
php artisan key:generate

# Configure email (optional for cronjob)
# Edit .env and set MAIL_* and ADMIN_EMAIL values

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start server
php artisan serve
```

✅ Backend running at: `http://localhost:8000`

---

## 📱 Mobile Web Interface (Tanpa Install!)

### 1. Buka Browser
```
http://127.0.0.1:8000/mobile-demo.html
```

### 2. Testing dari HP (Same Network)
1. Cari IP komputer:
   ```powershell
   ipconfig
   # Look for IPv4 Address (e.g., 192.168.1.100)
   ```

2. Buka dari HP:
   ```
   http://192.168.1.100:8000/mobile-demo.html
   ```

✅ Aplikasi siap digunakan!

# In new terminal, run Android
npm run android
```

**iOS (macOS only):**
```bash
# Install pods
cd ios && pod install && cd ..

# Start Metro bundler
npm start

# In new terminal, run iOS
npm run ios
```

✅ App should open on your device/emulator!

---

## ✅ Quick Test

### Test Backend API
```
http://localhost:8000/api/people?user_id=1
```
You should see JSON response with people data.

### Test Swagger Documentation
```
http://localhost:8000/api/documentation
```

### Test Mobile Web Interface
```
http://127.0.0.1:8000/mobile-demo.html
```

### Test All Features
1. ✅ **Recommended Menu** - Swipe cards dengan pagination
2. ✅ **Like Button** - 30% chance match notification muncul
3. ✅ **Liked Opponents Menu** - Lihat orang yang kamu like
4. ✅ **Disliked Menu** - Lihat orang yang kamu reject
5. ✅ **Match Menu** - Lihat mutual likes
6. ✅ **Profile Button** - Lihat detail profile
7. ✅ **Message Button** - Chat interface

### Test Cronjob (Optional)
```powershell
# Test email notification
php test-cronjob.php

# Manual cronjob trigger
php artisan people:check-popular
```

---

## 🐛 Quick Troubleshooting

### Backend Issues

**Cannot connect to database?**
```powershell
# Check MySQL is running in XAMPP
# Check .env file has correct database settings
```

**Port 8000 in use?**
```powershell
php artisan serve --port=8001
# Update URL: http://localhost:8001/mobile-demo.html
```

**Email not sending?**
```powershell
# Check .env MAIL_* configuration
# For Gmail: use App Password, not regular password
# See CRONJOB_TESTING.md for setup
```

### Mobile Web Issues

**API not loading?**
```
✅ Check Laravel server is running (php artisan serve)
✅ Check http://localhost:8000/api/people?user_id=1 works
✅ Open browser console (F12) for error details
```

**Match not showing?**
```
✅ Match probability is 30%, try liking multiple people
✅ Check browser console for errors
```

---

## 📚 Documentation Files

1. ✅ **README.md** - Complete project overview
2. ✅ **API_DOCUMENTATION.md** - All 8 API endpoints with examples
3. ✅ **PROJECT_SUMMARY.md** - Project completion summary
4. ✅ **SETUP_LARAVEL.md** - Detailed backend setup
5. ✅ **CRONJOB_TESTING.md** - Complete cronjob & email guide
6. ✅ **CRONJOB_QUICKSTART.md** - Quick cronjob reference
7. ✅ **QUICKSTART.md** (this file) - 10-minute setup

---

## 🎨 App Features

### ✅ Implemented
- 🔥 Recommended people with pagination
- 💖 Like system with 30% match probability
- ❌ Dislike system
- 📋 Liked opponents list (people you liked)
- 📋 Disliked list (people you rejected)
- ✨ Match list (mutual likes)
- 🔔 Match notification popup with animations
- 👤 Profile detail modal
- 💬 Chat interface with auto-response
- 📧 Email notification for popular people (50+ likes)
- 📅 Cronjob scheduler (daily at 09:00)
- 📚 Swagger API documentation
- 🎨 Mobile-responsive design

### 🚧 Future Enhancements
- User authentication & registration
- Real chat messaging with WebSocket
- Photo upload functionality
- Advanced filters (age, distance, gender)
- Super like feature
- Push notifications
- Undo last swipe
- Report/Block users

---

## 📞 Need Help?

1. Check troubleshooting sections in this guide
2. Review API documentation: `API_DOCUMENTATION.md`
3. Check Laravel logs: `storage/logs/laravel.log`
4. For cronjob issues: `CRONJOB_TESTING.md`
5. Open browser console (F12) for frontend errors

---

## 🎉 You're All Set!

Project structure:
```
PHP-Tinder-Laravel/
├── app/
│   ├── Console/Commands/
│   │   └── CheckPopularPeople.php  # Cronjob command
│   ├── Http/Controllers/Api/
│   │   └── PeopleController.php    # 8 API endpoints
│   └── Models/                      # Person, Like, Dislike
├── config/
│   ├── mail.php                     # Email configuration
│   └── database.php                 # MySQL config
├── database/
│   ├── migrations/                  # Database schema
│   └── seeders/                     # Sample data
├── public/
│   └── mobile-demo.html             # Mobile web interface
├── routes/
│   ├── api.php                      # 8 API routes
│   └── web.php                      # Web routes
├── test-cronjob.php                 # Automated cronjob test
├── README.md                        # Main docs
├── API_DOCUMENTATION.md             # API reference
├── CRONJOB_TESTING.md               # Cronjob guide
└── QUICKSTART.md (this file)        # Quick setup
```

**Happy Swiping! ❤️🔥**

---

## 🔗 Useful Commands

### Laravel
```powershell
php artisan serve                    # Start server
php artisan migrate:fresh --seed     # Reset database with data
php artisan db:seed                  # Add sample data
php artisan route:list               # View all routes
php artisan cache:clear              # Clear cache
php artisan people:check-popular     # Run cronjob manually
php artisan l5-swagger:generate      # Regenerate Swagger docs
```

### Testing
```powershell
# Test API
curl http://localhost:8000/api/people?user_id=1

# Test cronjob
php test-cronjob.php

# Open web interface
start http://localhost:8000/mobile-demo.html

# Open Swagger docs
start http://localhost:8000/api/documentation
```

### Database Queries
```sql
-- View all people
SELECT * FROM people;

-- View likes count per person
SELECT p.name, COUNT(l.id) as likes_count
FROM people p
LEFT JOIN likes l ON p.id = l.liked_id
GROUP BY p.id, p.name
ORDER BY likes_count DESC;

-- View matches for user 1
SELECT p.* FROM people p
JOIN likes l1 ON p.id = l1.liked_id
JOIN likes l2 ON p.id = l2.liker_id
WHERE l1.liker_id = 1 AND l2.liked_id = 1;

-- Find popular people (50+ likes)
SELECT p.name, COUNT(l.id) as likes_count
FROM people p
JOIN likes l ON p.id = l.liked_id
GROUP BY p.id, p.name
HAVING likes_count >= 50;
```

---

## 📧 API Endpoints Quick Reference

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/people` | Recommended people (paginated) |
| POST | `/api/people/{id}/like` | Like person (30% match) |
| POST | `/api/people/{id}/dislike` | Dislike person |
| GET | `/api/people/liked` | People you liked |
| GET | `/api/people/disliked` | People you disliked |
| GET | `/api/people/liked-opponents` | People who liked you |
| GET | `/api/people/matches` | Mutual likes |
| POST | `/api/people/check-popular` | Admin cronjob trigger |

Full documentation: `API_DOCUMENTATION.md`

---

Created with ❤️ by Khairul Adha  
Email: r4dioz.88@gmail.com  
GitHub: r4diorusak
