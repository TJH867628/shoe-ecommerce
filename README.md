# Shoe E-Commerce

Laravel-based shoe e-commerce application with a customer storefront and admin management pages.

## What You Need

- PHP `8.3`
- Composer
- Node.js and npm
- A database connection configured in `.env`

## Quick Setup

1. Install PHP dependencies.
   ```bash
   composer install
   ```

2. Create your environment file if it does not exist.
   ```bash
   copy .env.example .env
   ```

3. Generate an app key.
   ```bash
   php artisan key:generate
   ```

4. Configure your database in `.env`.
   - Update `DB_CONNECTION`
   - Update `DB_DATABASE`
   - Update `DB_USERNAME`
   - Update `DB_PASSWORD`

5. Run migrations.
   ```bash
   php artisan migrate
   ```

6. Install frontend dependencies.
   ```bash
   npm install
   ```

7. Build the frontend assets.
   ```bash
   npm run build
   ```

8. Start the app.
   ```bash
   php artisan serve
   ```

## Recommended One-Line Setup

If you want the fastest local setup, use the Composer script:

```bash
composer run setup
```

This will install dependencies, create `.env` if needed, generate the app key, run migrations, install npm packages, and build assets.

## Development Workflow

- Start the full local dev stack:
  ```bash
  composer run dev
  ```

- Run tests:
  ```bash
  composer run test
  ```

## Useful Notes For Teammates

- Product listing and product details live in the `resources/views/user/` folder.
- Admin product management views live in `resources/views/admin/`.
- If product images do not show up, make sure storage is linked:
  ```bash
  php artisan storage:link
  ```
- If you change environment values, clear config cache:
  ```bash
  php artisan config:clear
  ```

## Project Structure

- `app/Http/Controllers` - application controllers
- `resources/views` - Blade templates
- `database/migrations` - database schema
- `database/seeders` - seed data
- `routes/web.php` - web routes

## Notes

- The project uses Laravel 13.
- Frontend assets are built with Vite.

