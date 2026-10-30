# AGENTS.md

KasirQ is a single-app Laravel 13 / PHP 8.3 point-of-sale system: Blade + Tailwind CSS v4 + vanilla JS. All routes are web routes (no API routes) and there is no JS framework. Keep user-facing copy, receipt text, and validation messages in Indonesian; code identifiers and comments stay in English.

## Setup

- Run `composer install` and `npm install` first; `vendor/` is not committed, so every Artisan/Boost command fails until Composer is installed.
- `.env.example` defaults to SQLite, but `database/database.sqlite` is gitignored and absent. Either create it (`New-Item database/database.sqlite`) or configure MySQL yourself. The README's MySQL requirement reflects the original setup, not the shipped env.
- `composer run setup` installs, copies `.env`, generates a key, migrates, and builds assets, but it does **not** seed. Follow it with `php artisan migrate:fresh --seed` for demo data and login users.
- Run `php artisan storage:link` for product images; they live on the `public` disk.

## Commands

- Tests: `php artisan test --compact`; narrow with `--filter=TestName` or a test file path. `composer test` clears config before running.
- Formatting: `php vendor/bin/pint --dirty --format agent` after PHP edits. Pint is the only formatter; `.editorconfig` uses 4 spaces and LF.
- Frontend: `npm run dev` for Vite HMR, `npm run build` before shipping. There are no JS/CSS lint or test scripts, and `resources/js/app.js` is effectively empty — page JS is inline in Blade views.
- Routes: `php artisan route:list --except-vendor`.
- Tests are PHPUnit (not Pest): create with `php artisan make:test --phpunit SomeFeatureTest`.

## Architecture

- `routes/web.php` is the app route surface (there is no `routes/api.php`; JSON endpoints such as `/pos/payment` live under web middleware). All routes except the login pair and `POST /logout` require `auth` (the `/up` health check is public). The `admin` alias (`EnsureIsAdmin`) guards categories, products, users, and settings. Non-admins are redirected to the dashboard, not sent a 403.
- Business logic lives in controllers; there are no services, form requests, or policies. Match the existing style: inline `$request->validate(...)`, explicit return types, Eloquent models with `$fillable`/`$casts` and relationships only.
- Views are grouped in `resources/views/<resource>`. The shared shell is `layouts/app.blade.php`, which owns the sidebar, class-based dark mode, and inline helpers. Existing pages use Blade + inline vanilla JS (POS calls JSON endpoints with `fetch`), not Blade components, Alpine, or Livewire.
- `PosController::processPayment` is the critical path: it validates, wraps work in `DB::transaction`, `lockForUpdate()`s products, rejects insufficient stock, decrements stock, and builds `INV-Ymd-NNNN` from today's transaction count. Preserve the locking and invoice scheme.
- Settings is a key/value table (`receipt_header`, `receipt_footer`, `qris_image`) read via `Setting::pluck('value', 'key')`; there is no config file for store settings.
- SKU generation (`products/generate-sku`) maps category names to prefixes (Makanan→MKN, Minuman→MNM, Snack & Camilan→SNK), falls back to the first three letters, then pads a sequential suffix.

## Gotchas

- Seeded logins are `admin@kasirq.com` / `password` and `kasir1@kasirq.com` / `kasir2@kasirq.com` (also `password`). The README's `admin@kasirq.local` is stale.
- `DatabaseSeeder` does not call `SettingSeeder`, so a fresh `--seed` leaves settings empty. The default `images/qris.jpg` also does not exist.
- QRIS uploads are moved directly into `public/images` (not the storage disk), and that directory is untracked; create it before testing the upload.
- Product images are re-encoded to WebP with Intervention Image and stored under `products/` on the public disk; update/destroy deletes the previous file.
- Only scaffold `ExampleTest`s exist, and the feature one calls `/` unauthenticated even though `/` requires `auth`. Do not treat the current suite as meaningful coverage. `phpunit.xml` runs on SQLite `:memory:`; add `RefreshDatabase` to DB-touching tests.
- The Laravel Boost MCP server is intentionally not configured in `opencode.json`; add it back only after `composer install` if you need its tools. `.ai/rules` does not exist.
- `CLAUDE.md` is a byte-identical copy of the old generated Laravel Boost guidelines; this file is the repo-specific source of truth.
