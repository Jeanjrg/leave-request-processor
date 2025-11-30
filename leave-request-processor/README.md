# Leave Request Processor

A simple internal leave request system built with Laravel. The application supports four roles: **Admin**, **Division Leader**, **HRD**, and **Employee**, with a two-step approval flow (Division Leader → HRD).

## Prerequisites
- PHP 8.1+ (PHP 8.2 recommended)
- Composer
- Node.js & npm or yarn (for Vite/Tailwind frontend)
- MySQL (XAMPP can be used for local development)

## Quick Setup (Development)
1. Copy the example environment file and update database credentials in `.env`:

```powershell
copy .env.example .env
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install frontend dependencies and start Vite for development:

```bash
npm install
npm run dev
```

4. Generate application key:

```bash
php artisan key:generate
```

5. Run migrations and seeders (make sure your database exists and `.env` DB settings are correct):

```bash
php artisan migrate --seed
```

6. (Optional) Create storage symlink for attachments:

```bash
php artisan storage:link
```

7. Run the local development server:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

## Seeded Test Accounts
The default seeder creates a few test users you can use to log in:

- Admin: `admin@email.com` / `password`
- HRD: `hrd@email.com` / `password`
- Division Leader: `leader@email.com` / `password`

Use these accounts to access the different role-specific dashboards.

## Important Routes
- `GET /dashboard` — redirects to the appropriate dashboard based on role
- `Resource /leaves` — employee leave management (`leaves.index`, `leaves.create`, etc.)
- Admin routes are prefixed with `/admin` (user & division management)
- Leader routes are prefixed with `/leader` (initial approvals)
- HRD routes are prefixed with `/hrd` (final approvals)

## Useful Commands
- Clear compiled views: `php artisan view:clear`
- Clear cache: `php artisan cache:clear`
- Rebuild config cache: `php artisan config:cache`
- Re-run migrations and seeders (development only): `php artisan migrate:fresh --seed`

## Testing
If tests are available, run PHPUnit with:

```bash
./vendor/bin/phpunit
```

## Troubleshooting
- Blade error `Undefined variable $slot`: layout `resources/views/layouts/app.blade.php` has been updated to support both component slots and traditional `@extends`/`@section` views using `@yield('content', $slot ?? '')`.
- Missing buttons or UI elements: run `php artisan view:clear`, then perform a hard reload in your browser (Ctrl+F5). Check DevTools → Elements to ensure the elements exist in the DOM.
- Uploaded attachments not visible: run `php artisan storage:link` and ensure `storage` is writable by the web server.

## Project Structure (Key Files)
- Models: `app/Models/User.php`, `app/Models/Division.php`, `app/Models/LeaveApplication.php`
- Controllers: `app/Http/Controllers/DashboardController.php`, `LeaveApplicationController.php`, `LeaderApprovalController.php`, `HRDApprovalController.php`, and admin controllers under `app/Http/Controllers/Admin/`
- Views: `resources/views/dashboard/*`, `resources/views/leaves/*`, `resources/views/admin/*`
- Routes: `routes/web.php`