# PHP Tinder app 20250929

**Developer:** Khairul Adha  
**Email:** r4dioz.88@gmail.com  
**GitHub:** r4diorusak  

A full-stack dating application with Laravel backend API and responsive web interface featuring swipe cards, likes/dislikes, and match functionality.

> *This project was developed as a technical assignment for the interview process.*

## 🚀 Features

### Backend (Laravel)
- ✅ RESTful API endpoints (8 endpoints)
- ✅ Person profiles with name, age, pictures, location, bio
- ✅ Like/Dislike system with simulate_match
- ✅ Match detection (30% probability on like)
- ✅ Paginated recommendations
- ✅ Liked/Disliked lists with pagination
- ✅ Liked opponents (who liked you)
- ✅ Cronjob for popular people notifications (50+ likes)
- ✅ Email notifications (Gmail SMTP)
- ✅ Swagger API documentation
- ✅ MySQL database with XAMPP

### Mobile Web Interface
- ✅ Mobile-responsive design with 5-menu bottom navigation
- ✅ Card-based swipe UI
- ✅ Like/Dislike buttons with 30% match chance
- ✅ Pagination for recommended people
- ✅ Liked Opponents menu (people you liked)
- ✅ Disliked menu (people you rejected)
- ✅ Match menu (mutual likes)
- ✅ Profile modal with full details
- ✅ Chat interface with auto-response
- ✅ Match notifications with bounce animations
- ✅ Works on any device with browser
- ✅ No installation required

## 📋 Prerequisites

### Backend
- PHP >= 8.1
- Composer
- XAMPP (MySQL)
- Laravel 10

## 🛠️ Installation

### Backend Setup

1. **Navigate to project directory**
```bash
cd "d:\Documents\GitHub\CV Project\PHP-Tinder-Laravel"
```

2. **Install Composer dependencies**
```bash
composer install
```

3. **Setup environment file**
```bash
Copy-Item .env.example .env
```

4. **Generate application key**
```bash
php artisan key:generate
```

5. **Setup XAMPP MySQL Database**
   - Start XAMPP Control Panel
   - Start Apache and MySQL services
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `tinder_app`

6. **Configure database in .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tinder_app
DB_USERNAME=root
DB_PASSWORD=
```

7. **Run migrations**
```bash
php artisan migrate
```

8. **Seed the database**
```bash
php artisan db:seed
```

9. **Start Laravel development server**
```bash
php artisan serve
```

The API will be available at: `http://localhost:8000`

### Accessing the Mobile Web Interface

**On your computer:**
Open browser and navigate to: `http://127.0.0.1:8000/mobile-demo.html`

**On your smartphone (same network):**
1. Find your computer's IP address:
```bash
ipconfig
```
2. Open browser on phone: `http://[YOUR_IP]:8000/mobile-demo.html`
   Example: `http://192.168.1.100:8000/mobile-demo.html`

3. **Update API URL** (if needed)
Edit `src/services/api.ts` and update the `API_BASE_URL` if your Laravel server is running on a different address.

For Android emulator, use:
```typescript
const API_BASE_URL = 'http://10.0.2.2:8000/api';
```

For iOS simulator, use:
```typescript
const API_BASE_URL = 'http://localhost:8000/api';
```

For physical device, use your computer's IP:
```typescript
const API_BASE_URL = 'http://192.168.x.x:8000/api';
```

4. **Run the app**

For Android:
```bash
npm run android
```

For iOS:
```bash
cd ios
pod install
cd ..
npm run ios
```

## 📡 API Endpoints

### Get Recommended People
```
GET /api/people?user_id=1&page=1&per_page=10
```

### Like a Person (30% Match Probability)
```
POST /api/people/{id}/like
Body: { "user_id": 1, "simulate_match": false }
```

### Dislike a Person
```
POST /api/people/{id}/dislike
Body: { "user_id": 1 }
```

### Get Liked People List
```
GET /api/people/liked?user_id=1&page=1&per_page=10
```

### Get Disliked People List
```
GET /api/people/disliked?user_id=1&page=1&per_page=10
```

### Get Liked Opponents (People Who Liked You)
```
GET /api/people/liked-opponents?user_id=1
```

### Get Matches (Mutual Likes)
```
GET /api/people/matches?user_id=1
```

### Check Popular People (Admin/Cronjob)
```
POST /api/people/check-popular
```

## 📁 Project Structure

### Backend Structure
```
PHP-Tinder-Laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           └── PeopleController.php
│   └── Models/
│       ├── Person.php
│       ├── Like.php
│       └── Dislike.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_people_table.php
│   │   ├── 2024_01_01_000002_create_likes_table.php
│   │   └── 2024_01_01_000003_create_dislikes_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── PersonSeeder.php
├── routes/
│   └── api.php
└── config/
    └── database.php
```

### Mobile Structure (Atomic Design)
```
mobile/
└── src/
    ├── components/
    │   ├── atoms/           # Smallest components
    │   │   ├── Button.tsx
    │   │   ├── CustomText.tsx
    │   │   └── IconButton.tsx
    │   ├── molecules/       # Combination of atoms
    │   │   ├── ActionButtons.tsx
    │   │   ├── LikedPersonItem.tsx
    │   │   └── PersonCard.tsx
    │   └── organisms/       # Complex components
    │       └── SwipeCards.tsx
    ├── screens/
    │   ├── SplashScreen.tsx
    │   ├── MainScreen.tsx
    │   └── LikedListScreen.tsx
    ├── services/
    │   └── api.ts
    ├── hooks/
    │   └── useApi.ts
    ├── state/
    │   └── atoms.ts
    └── App.tsx
```

## 🎨 Technology Stack

### Backend
- **Framework**: Laravel 10
- **Database**: MySQL (XAMPP)
- **API**: RESTful JSON API

### Mobile
- **Framework**: React Native 0.73
- **State Management**: Recoil
- **Data Fetching**: TanStack React Query
- **Navigation**: React Navigation
- **Gestures**: React Native Gesture Handler
- **Architecture**: Atomic Design Pattern

## 🔧 Configuration

### CORS Configuration
The backend is configured to allow all origins for development. For production, update `config/cors.php`:

```php
'allowed_origins' => ['http://your-app-domain.com'],
```

### Database Seeder
The project includes a seeder with 15 sample profiles. To add more data, edit `database/seeders/PersonSeeder.php`.

## 📱 App Features

1. **Splash Screen**: Beautiful intro screen with app logo
2. **Main Screen**: 
   - Swipeable card stack showing person profiles
   - Swipe right to like, left to dislike
   - Manual like/dislike buttons
   - Match notifications when mutual like occurs
3. **Liked List Screen**: View all people you've liked

## 🐛 Troubleshooting

### Backend Issues

**Database connection error:**
- Ensure XAMPP MySQL is running
- Check database credentials in `.env`
- Verify database `tinder_app` exists

**API returns 404:**
- Clear Laravel cache: `php artisan cache:clear`
- Clear config cache: `php artisan config:clear`

### Mobile Issues

**Cannot connect to API:**
- Check API_BASE_URL in `src/services/api.ts`
- For Android emulator, use `10.0.2.2` instead of `localhost`
- Ensure Laravel server is running

**Build errors:**
- Clean build: `cd android && ./gradlew clean`
- Clear Metro cache: `npm start -- --reset-cache`

## 📝 Notes

- User authentication is simplified (uses user_id parameter). For production, implement proper authentication with Laravel Sanctum or JWT.
- Images use placeholder service (pravatar.cc). Replace with actual image upload functionality for production.
- The current implementation assumes a single user (user_id=1). Extend for multi-user support with authentication.

## 🚧 Future Enhancements

- [ ] User authentication & registration
- [ ] Chat messaging between matches
- [ ] Profile editing
- [ ] Photo upload functionality
- [ ] Advanced filtering (age, location, gender)
- [ ] Super like feature
- [ ] Push notifications
- [ ] Real-time match alerts

## 📄 License

MIT License - feel free to use this project for learning or commercial purposes.

## 👨‍💻 Author

Created as a demonstration of full-stack development with Laravel and React Native.

---

**Happy Swiping! ❤️**
