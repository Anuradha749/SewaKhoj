# SewaKhoj

Local service directory built with Laravel 12, PHP 8.2+, Blade HTML, Tailwind CSS 3, Alpine.js, Vite and MySQL. Node 22.12+ (or a supported newer version), Composer 2 and MySQL 8 are recommended.

## Apply this repair to your existing Mac project

1. Keep a backup of your original project and database.
2. Extract this ZIP into a separate folder. Copy the files from the extracted SewaKhoj folder into your existing project, replacing matching files. Do not replace your existing `.env`, `.git`, or database. Finder may ask to replace whole directories: merge their contents rather than deleting the original project. Alternatively, use the new extracted folder and copy only your original `.env` into it.
3. In VS Code, open the project folder you intend to run. Use Terminal → New Terminal there. Stop any old `php artisan serve` process with Control+C so it does not keep serving another copy.
4. Run:

```bash
composer install
npm ci
php artisan config:clear
php artisan view:clear
php artisan migrate
php artisan db:seed --class=CategorySeeder
npm run build
php artisan serve
```

Open http://127.0.0.1:8000. Vite dev mode is optional: the included production build is rebuilt by `npm run build`.

Do not run `migrate:fresh` on your existing database. The repaired creation migration was pending in the migration status you shared; `migrate` creates the missing table and runs the remaining migrations without resetting your existing users or categories.

If that migration now shows **Ran** but your table is absent or incomplete, stop and share `php artisan migrate:status` and the new error. Editing an already-run migration does not change the database. This package does not guess at or overwrite an unknown database state.

## First-time setup on a new database

Create a database in your MySQL client:

```sql
CREATE DATABASE sewakhoj CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Then, from this project folder:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` to use your local MySQL credentials:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sewakhoj
DB_USERNAME=your_mysql_user
DB_PASSWORD=your_mysql_password
```

The example defaults to `laravel` to match the database in your error. Use `sewakhoj` only for the new database above. Keep your existing database name and APP_KEY when repairing your existing installation. Never copy a sample password over a working credential.

Then run:

```bash
php artisan config:clear
php artisan migrate
php artisan db:seed
npm ci
npm run build
php artisan serve
```

The default seeder adds six service categories and is safe to rerun. It does not create a default account or public sample providers. Register your own account as a **Service Provider**, open **My listing**, and save the form to publish your listing. Customer accounts can browse and contact providers.

## Optional demo records (local development only)

```bash
php artisan db:seed --class=ServiceProviderSeeder
```

These are fictional examples with example email addresses and sample phone numbers, not real verified professionals. This seeder also creates the required categories. Do not publish these demo listings as real services.

## What was corrected

- Replaced the broken ALTER migration with a real `service_providers` creation migration.
- Kept `user_id` in its separate migration and made it unique for one listing per account.
- Added the missing provider controller import and simplified authenticated routes.
- Implemented `/services` with search, location and category filters, pagination, and empty states.
- Rebuilt the homepage with responsive Tailwind/Blade, login/register navigation, and contact links.
- Removed the unused Tailwind 4 Vite plugin; the project consistently uses Tailwind 3 with PostCSS.
- Added active-category validation, phone validation and database uniqueness.
- Provider updates are scoped to the signed-in account and cannot reactivate a disabled listing or self-verify.
- Provider edits reset verification to pending. No verification/admin workflow is implemented in this project.
- Account deletion also deletes the associated public provider listing.
- Made default seeding repeatable without a known-password test account.
- Fixed registration role retention, form labels, provider navigation and stale dashboard messaging.
- Removed leading whitespace before PHP opening tags and uploaded compiled caches from the deliverable.
- Added directory/profile regression tests and updated the existing registration/homepage tests.

## Checks

```bash
php artisan test
php artisan route:list --except-vendor
php artisan view:cache
npm run build
```

At delivery: PHP 8.4.24; 34 PHPUnit tests / 105 assertions passed using isolated SQLite databases. Fresh migrations and seeders completed. Routes resolved and Blade templates compiled. Vite production build passed. Browser visual/interaction testing was attempted but the browser could not launch in this environment; the responsive layout has not been visually verified. MySQL 8 and PHP 8.5 were not available for live integration testing; run the migration command on your Mac to verify your actual MySQL setup. Test configuration uses an in-memory SQLite database so tests do not erase your MySQL data; PHP needs the SQLite extension to run this suite.

## Operational notes

- Password-reset email uses the `log` mail driver by default. Configure a real mail provider before expecting emails to arrive.
- Provider contact details are public; the profile form explains this before saving.
- This is a directory, not a booking/payment platform. There is no implemented admin verification screen.
- For production, configure HTTPS, APP_DEBUG=false, real mail credentials, backups and your hosting environment before going live.
- Private `.env` files, Git history, dependencies, logs, sessions and local database files are intentionally excluded from the ZIP. Install dependencies with Composer/npm as above.
