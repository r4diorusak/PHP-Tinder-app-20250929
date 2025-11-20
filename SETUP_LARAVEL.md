# Laravel Setup Instructions - XAMPP MySQL

## Langkah-langkah Setup Database

### 1. Install dan Start XAMPP

1. Download XAMPP dari https://www.apachefriends.org/
2. Install XAMPP di komputer Anda
3. Buka XAMPP Control Panel
4. Start **Apache** dan **MySQL**

### 2. Buat Database

1. Buka browser dan akses phpMyAdmin: `http://localhost/phpmyadmin`
2. Klik tab "**Database**" di menu atas
3. Masukkan nama database: `tinder_app`
4. Pilih collation: `utf8mb4_unicode_ci`
5. Klik tombol "**Create**"

### 3. Setup Laravel Environment

1. Buka folder project di terminal/PowerShell:
```powershell
cd "d:\Documents\GitHub\CV Project\PHP-Tinder-Laravel"
```

2. Copy file .env.example ke .env:
```powershell
Copy-Item .env.example .env
```

3. Edit file `.env` dan pastikan konfigurasi database sebagai berikut:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tinder_app
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan**: Password kosong adalah default XAMPP. Jika Anda mengubah password MySQL, masukkan password tersebut.

### 4. Install Dependencies

```powershell
composer install
```

Jika Composer belum terinstall:
- Download dari: https://getcomposer.org/download/
- Install dan restart terminal

### 5. Generate Application Key

```powershell
php artisan key:generate
```

### 6. Run Migrations

```powershell
php artisan migrate
```

Ini akan membuat 3 tabel:
- `people` - Menyimpan data profil pengguna
- `likes` - Menyimpan data like
- `dislikes` - Menyimpan data dislike

### 7. Seed Database dengan Data Sample

```powershell
php artisan db:seed
```

Ini akan menambahkan 15 profil sample ke database.

### 8. Start Laravel Development Server

```powershell
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

### 9. Test API

Buka browser atau Postman dan test endpoint:

**Get Recommended People:**
```
GET http://localhost:8000/api/people?user_id=1
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "name": "Sarah Johnson",
      "age": 25,
      "pictures": [...],
      "location": "New York, USA",
      "bio": "Love hiking, coffee, and adventure!",
      "gender": "female"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 14,
    "last_page": 2,
    "has_more": true
  }
}
```

## Troubleshooting

### Error: SQLSTATE[HY000] [1045] Access denied

**Solusi:**
1. Pastikan MySQL di XAMPP sudah running
2. Periksa username dan password di file `.env`
3. Default XAMPP: username=`root`, password=kosong

### Error: SQLSTATE[HY000] [1049] Unknown database 'tinder_app'

**Solusi:**
1. Buka phpMyAdmin: http://localhost/phpmyadmin
2. Buat database baru dengan nama `tinder_app`

### Error: Class 'App\Providers\AppServiceProvider' not found

**Solusi:**
```powershell
composer dump-autoload
```

### Error: Port 8000 already in use

**Solusi:**
Gunakan port lain:
```powershell
php artisan serve --port=8001
```

Jangan lupa update API URL di mobile app jika menggunakan port berbeda.

## Perintah Artisan Berguna

```powershell
# Refresh database (hapus semua data dan migrate ulang)
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# View routes
php artisan route:list

# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName
```

## Database Schema

### Table: people
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| name | varchar | Nama lengkap |
| age | integer | Umur |
| pictures | json | Array URL gambar |
| location | varchar | Lokasi |
| bio | text | Bio/deskripsi |
| gender | enum | male/female/other |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

### Table: likes
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| liker_id | bigint | Foreign key ke people (yang like) |
| liked_id | bigint | Foreign key ke people (yang dilike) |
| created_at | timestamp | Waktu like |
| updated_at | timestamp | Waktu diupdate |

### Table: dislikes
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| disliker_id | bigint | Foreign key ke people (yang dislike) |
| disliked_id | bigint | Foreign key ke people (yang didislike) |
| created_at | timestamp | Waktu dislike |
| updated_at | timestamp | Waktu diupdate |

## Test dengan Postman

### 1. Like a Person
```
POST http://localhost:8000/api/people/2/like
Content-Type: application/json

{
  "user_id": 1
}
```

### 2. Dislike a Person
```
POST http://localhost:8000/api/people/3/dislike
Content-Type: application/json

{
  "user_id": 1
}
```

### 3. Get Liked People List
```
GET http://localhost:8000/api/people/liked?user_id=1
```

### 4. Get Matches
```
GET http://localhost:8000/api/people/matches?user_id=1
```

## Tips Production

1. **Ubah APP_ENV ke production** di `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

2. **Setup CORS dengan benar** di `config/cors.php`

3. **Gunakan database production** dan update kredensial di `.env`

4. **Optimize Laravel**:
```powershell
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

---

Selamat coding! 🚀
