Laravel Dockerized app (scaffold)

What this does
- Builds a Laravel 10 app into the `app` image (composer creates project during build).
- Automatically copies the provided Laravel snippets (models, migrations, seeders, controllers, routes) during build.
- Runs `php-fpm` in `app` service, serves the app with `nginx` on port `8081`.
- Uses MySQL (port `3307` on the host) for the database.
- A `scheduler` service runs Laravel scheduled tasks every minute.

Quick start (from repository root)

1. Build and start containers:

```powershell
cd backend/laravel-app
docker-compose up --build -d
```

The build will automatically copy all snippets into the Laravel project.

2. Initialize the app (run migrations and seeders). You can run artisan inside the `app` container:

```powershell
# run a shell inside the app container
docker-compose exec app bash
# inside container
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
exit
```

3. Open the app in your browser:

- App: http://localhost:8081
- MySQL port (host): 3307

API Endpoints
- GET `http://localhost:8081/api/people` — list people (paginated)
- POST `http://localhost:8081/api/people/{id}/like` — like a person
- POST `http://localhost:8081/api/people/{id}/dislike` — dislike a person
- GET `http://localhost:8081/api/likes` — list liked people

Mail
- Mail is configured to use the `log` driver in `.env.example` so emails are written to logs by default.
- The `NotifyTopLiked` command runs via scheduler and logs to `storage/logs/laravel.log`.

Testing
- Use the Postman collection at `../postman/PHP-Tinder-App.postman_collection.json` to test endpoints.
- Import the collection and environment into Postman, then run requests against `http://localhost:8081`.

Cleanup
- To remove containers and volumes: `docker-compose down -v`

