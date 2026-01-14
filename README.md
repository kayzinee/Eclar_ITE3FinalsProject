# Inventory Management System

A modern claymorphic inventory management application built with Laravel.

## Requirements

- PHP 8.1+
- Composer
- Node.js & npm
- MySQL/MariaDB

## Installation

1. **Clone the repository**
   ```bash
   git clone "link"
   cd InventorySystem_test
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Setup environment file**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Configure database** - Edit `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_system
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Start development server**
   ```bash
   php artisan serve
   ```

10. **Start Vite dev server** (in another terminal)
    ```bash
    npm run dev
    ```

Visit `http://localhost:8000` in your browser.

## Features

-  Dashboard with inventory overview
-  Product management (CRUD)
-  Category management (CRUD)
-  Search functionality
-  Modern claymorphic UI design
-  User authentication

## Default Admin Credentials

Create a user account by registering through the app.

## Project Structure

- `app/Http/Controllers/` - Application controllers
- `resources/views/` - Blade templates
- `routes/web.php` - Web routes
- `database/migrations/` - Database migrations

## License

MIT
