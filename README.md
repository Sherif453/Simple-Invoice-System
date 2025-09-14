# SimpleInvoice (Laravel 12)

Plain HTML/CSS/JS UI. Features:
- Customers & Products CRUD
- Create invoices with client name
- Add items (qty, price) with automatic totals
- Export invoice to PDF

## Setup
1. Copy `.env.example` to `.env` and set DB creds.
2. `composer install`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `php artisan serve`

## Tech
- Laravel 12, PHP 8.2, MySQL
- barryvdh/laravel-dompdf
