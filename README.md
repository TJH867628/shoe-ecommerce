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

### 9. Set Up Xdebug for VSCode

If you want to use VSCode breakpoints, install and enable Xdebug in your local PHP installation.

#### Install Xdebug

1. Confirm which PHP installation Laravel is using:

```bash
php -v
php --ini
```

2. Enable Xdebug in your `php.ini` file. Add or update these settings:

```ini
zend_extension=xdebug

[xdebug]
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=127.0.0.1
xdebug.client_port=9003
```

3. Restart the PHP server after saving `php.ini`:

```bash
php artisan serve
```

#### VSCode Setup

This project includes a debug configuration at:

- `.vscode/launch.json`

It listens on port `9003` with the name `Listen for Xdebug`.

If you need to create it manually, use this configuration:

```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type": "php",
      "request": "launch",
      "port": 9003
    }
  ]
}
```

#### How to Use Xdebug

1. Open the Laravel project in VSCode.
2. Open a PHP file and click the left gutter to set a breakpoint.
3. Start the `Listen for Xdebug` debugger in VSCode.
4. Visit the page or submit the form in your browser.
5. Execution will pause at the breakpoint when that code runs.

#### Useful Notes

- Make sure VSCode is listening before you reload the page.
- If breakpoints do not stop, confirm the PHP process running `php artisan serve` is the same PHP installation that has Xdebug enabled.
- A quick temporary alternative is `dd($variable)` or `dump($variable)` in Laravel code.

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
