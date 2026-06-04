# Shoe E-Commerce

Laravel-based shoe e-commerce application with customer and admin features.

## Setup Guide

### 1. Prerequisites

Make sure you have:

- PHP 8.3
- Composer
- Node.js and npm
- MySQL or another supported database

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Configure Environment

Copy the example environment file if needed:

```bash
copy .env.example .env
```

Then update the database settings in `.env`:

- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Create Database Tables and Seed Data

```bash
php artisan migrate:fresh --seed
```

This will reset the database and load the sample data.

### 6. Link Storage

```bash
php artisan storage:link
```

### 7. Build Frontend Assets

For production build:

```bash
npm run build
```

For development:

```bash
npm run dev
```

### 8. Run the App

Start the Laravel server:

```bash
php artisan serve
```

## Default Login Accounts

- Customer
  - Email: `test@example.com`
  - Password: `password`

- Admin
  - Email: `admin@example.com`
  - Password: `password`

## Helpful Commands

- Reset and reseed the database:
  ```bash
  php artisan migrate:fresh --seed
  ```

- Clear config cache:
  ```bash
  php artisan config:clear
  ```

- Clear application cache:
  ```bash
  php artisan cache:clear
  ```

## Project Structure

- `app/Http/Controllers` - controllers
- `app/Models` - Eloquent models
- `resources/views` - Blade views
- `database/migrations` - database schema
- `database/seeders` - seed data
- `routes/web.php` - web routes

## Notes

- The project uses Vite for frontend assets.
- Product images may come from local storage or external URLs, depending on the data source.

