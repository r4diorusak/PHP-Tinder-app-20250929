This folder contains example Laravel snippets you can paste into a fresh Laravel project.

Suggested steps to integrate:

1. Create a new Laravel project: `composer create-project laravel/laravel backend-app`.
2. Copy the migrations from `migrations/` into `backend-app/database/migrations/`.
3. Copy the controllers into `backend-app/app/Http/Controllers/Api/` and update namespaces as needed.
4. Add routes (see `routes-api-sample.php`) to `backend-app/routes/api.php`.
5. Run `php artisan migrate` to apply the schema.
6. Wire mail settings in `.env` for the scheduled command to send emails.

The files here are samples — adjust types, validation, and auth to match your app.