# PHP Tinder App 20250929

## 📋 Overview | Gambaran Umum

This repository contains a complete scaffolding for a Tinder-like mobile app (React Native) and a PHP (Laravel) backend API.

Repositori ini berisi scaffolding lengkap untuk aplikasi Tinder-like (React Native) dan API backend PHP (Laravel).

### Includes / Mencakup:
- ✅ OpenAPI (Swagger) spec untuk testing API
- ✅ SQL schema (MySQL) dengan tabel `people`, `pictures`, `likes`, `users`
- ✅ Laravel models, migrations, controllers, dan scheduled commands
- ✅ React Native skeleton dengan card swiper component
- ✅ Postman collection untuk testing API
- ✅ Docker setup untuk easy deployment

---

## 🚀 Cara Menjalankan Aplikasi | How to Run the App

### Prerequisites | Persyaratan:
- **Docker** dan **Docker Compose** terinstall
- **Postman** (optional, untuk testing API)
- **Node.js & npm** (untuk React Native frontend)

---

## 1️⃣ Setup Backend (Laravel API) | Setup Backend (Laravel API)

### Step 1: Navigate ke folder laravel-app
```powershell
cd backend/laravel-app
```

### Step 2: Build dan start containers
```powershell
docker-compose up --build -d
```
Proses ini akan:
- Build Laravel image dengan semua snippets (models, migrations, controllers)
- Start PHP-FPM, Nginx, MySQL, dan Scheduler containers
- Membuat database `tinder_app`

Tunggu beberapa menit hingga build selesai.

### Step 3: Initialize database dan seeders
```powershell
docker-compose exec app bash
```

Di dalam container, jalankan:
```bash
# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Jalankan migrations (create tables)
php artisan migrate

# Seed test data (insert Alice dan Bob)
php artisan db:seed

# Exit dari container
exit
```

### Step 4: Verify backend berjalan
Buka di browser:
- **API base**: http://localhost:8081/api
- **Test endpoint**: http://localhost:8081/api/people

Seharusnya akan menampilkan list people (Alice dan Bob).

---

## 2️⃣ Testing API dengan Postman | Test API with Postman

### Import Collection:
1. Buka Postman
2. **File** → **Import**
3. Pilih file: `backend/postman/PHP-Tinder-App.postman_collection.json`
4. Import juga environment: `backend/postman/PHP-Tinder-App.postman_environment.json`

### Jalankan Requests:
- **List People**: GET `{{base_url}}/api/people`
- **Like Person**: POST `{{base_url}}/api/people/1/like`
- **Dislike Person**: POST `{{base_url}}/api/people/1/dislike`
- **Get Liked List**: GET `{{base_url}}/api/likes`

Klik **Send** untuk setiap request. Response akan ditampilkan di bawah.

---

## 3️⃣ Testing API dengan cURL | Test API with cURL

### List semua people:
```powershell
curl http://localhost:8081/api/people
```

### Like person ID 1:
```powershell
curl -X POST http://localhost:8081/api/people/1/like
```

### Dislike person ID 2:
```powershell
curl -X POST http://localhost:8081/api/people/2/dislike
```

### Get liked people:
```powershell
curl http://localhost:8081/api/likes
```

---

## 4️⃣ View API Spec dengan Swagger UI | View API Spec with Swagger UI

### Dari repository root:
```powershell
docker-compose up -d
```

### Buka di browser:
- **Swagger UI**: http://localhost:8080

Di sini Anda bisa melihat semua endpoints dan mencoba requests langsung dari browser.

---

## 5️⃣ Setup Frontend (React Native) | Setup Frontend (React Native)

### Step 1: Create new React Native project
```powershell
npx react-native init TinderApp
cd TinderApp
```

### Step 2: Install dependencies
```powershell
npm install @tanstack/react-query recoil react-native-deck-swiper
```

### Step 3: Copy frontend components
Salin file dari `frontend/src/` ke project Anda:
- `frontend/src/components/CardStack.js` → `TinderApp/src/components/`
- `frontend/src/index.js` → `TinderApp/src/index.js`

### Step 4: Connect ke backend API
Update API base URL di components sesuai dengan IP/port backend Anda.

### Step 5: Run the app
```powershell
# For Android
npx react-native run-android

# For iOS
npx react-native run-ios
```

---

## 📱 API Endpoints Documentation | Dokumentasi Endpoints

| Method | Endpoint | Description | Deskripsi |
|--------|----------|-------------|-----------|
| GET | `/api/people` | List people (paginated) | Daftar people (paginated) |
| POST | `/api/people/{id}/like` | Like a person | Sukai seseorang |
| POST | `/api/people/{id}/dislike` | Dislike a person | Tolak seseorang |
| GET | `/api/likes` | Get liked people | Dapatkan daftar yang disukai |

### Example Response - GET /api/people:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Alice",
      "age": 25,
      "location": "Tokyo",
      "pictures": [
        {
          "id": 1,
          "url": "https://placekitten.com/400/400"
        }
      ]
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 10,
    "per_page": 10
  }
}
```

---

## 🔧 Troubleshooting | Pemecahan Masalah

### Backend containers tidak start:
```powershell
# Check logs
docker-compose logs -f app

# Rebuild containers
docker-compose down -v
docker-compose up --build -d
```

### Database error:
```powershell
# Re-run migrations
docker-compose exec app php artisan migrate:refresh --seed
```

### Port already in use:
- Edit `docker-compose.yml` dan ubah port mapping
- Atau stop service lain yang menggunakan port tersebut

### API tidak merespons:
```powershell
# Check if containers are running
docker-compose ps

# Check PHP-FPM is working
docker-compose exec app php --version
```

---

## 📊 Database Schema | Skema Database

- **users**: menyimpan user/customer
- **people**: menyimpan profile orang (name, age, location)
- **pictures**: menyimpan foto untuk setiap person
- **likes**: menyimpan like/dislike records

Lihat `backend/sql/schema.sql` untuk detail lengkap.

---

## 📁 Folder Structure | Struktur Folder

```
PHP-Tinder-app-20250929/
├── backend/
│   ├── laravel-app/          # Dockerized Laravel app
│   │   ├── Dockerfile
│   │   ├── docker-compose.yml
│   │   └── nginx/
│   ├── laravel-snippets/     # Models, controllers, migrations
│   ├── openapi.yaml          # Swagger spec
│   ├── sql/
│   │   └── schema.sql
│   └── postman/              # Postman collection & environment
├── frontend/
│   ├── src/
│   │   ├── components/       # React Native components
│   │   └── index.js
│   └── package.json
├── docker-compose.yml        # Root docker-compose (Swagger UI)
└── README.md
```

---

## ✅ Checklist Keberhasilan | Success Checklist

- [ ] Backend containers running (`docker-compose ps`)
- [ ] Database migrations selesai (`php artisan migrate`)
- [ ] Data seeded (`php artisan db:seed`)
- [ ] API merespons di http://localhost:8081/api/people
- [ ] Postman requests berjalan dengan status 200
- [ ] Swagger UI terbuka di http://localhost:8080
- [ ] React Native project created dan dependencies installed
- [ ] Frontend card component dapat menampilkan data dari API

---

## 🎯 Fitur yang Diimplementasikan | Features Implemented

✅ **Backend (Laravel)**:
- List people dengan pagination
- Like/dislike functionality
- Get liked people list
- Scheduled command untuk email notif (>50 likes)
- MySQL database
- Swagger API spec

✅ **Frontend (React Native)**:
- Splash screen placeholder
- Card stack swiper component
- Atomic design folder structure
- React Query ready
- Recoil state management ready

---

## 📧 Support | Bantuan

Jika ada error atau pertanyaan, periksa:
1. Docker version (`docker --version`)
2. Container logs (`docker-compose logs`)
3. Database connection (`docker-compose exec app php artisan tinker`)

---

## 📝 Notes | Catatan

- Authentication: Backend saat ini fallback ke first user (untuk testing). Gunakan Sanctum/Passport untuk production.
- Email: Configured ke `log` driver. Ubah di `.env` untuk real SMTP.
- Scheduler: Berjalan setiap menit di container `scheduler`.

Selamat! Aplikasi Tinder Anda siap dijalankan! 🎉