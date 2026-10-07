# PHP_Laravel12_Jetstream
=======



---

##  Introduction

This project is built with **Laravel 12** and uses **Laravel Jetstream** for authentication and application scaffolding.

###  Tech Stack

*   **Laravel 12** - Latest Laravel framework
*   **Laravel Jetstream** - Application scaffolding for login, registration, email verification, 2FA, and API management
*   **Livewire** - Full-page or component-based dynamic interfaces (Jetstream stack)
*   **Tailwind CSS** - Utility-first CSS framework
*   **Laravel Sanctum** - Lightweight API authentication
*   **SQLite** - Database (can be switched to MySQL/PostgreSQL)
*   **Vite** - Frontend asset bundling

###  Features Included

*   User Registration & Login
*   Email Verification
*   Password Reset
*   Two-Factor Authentication (2FA)
*   Profile Management
*   API Token Management
*   Browser Session Management
*   Account Deletion

---

##  Prerequisites

Ensure you have the following installed on your system:

*   **PHP** >= 8.2
*   **Composer** (PHP package manager)
*   **Node.js** & **npm** (for Vite)
*   **XAMPP** or any local server with PHP & MySQL/MySQLi
*   **Git** (optional, for cloning)

---

##  Step 1: Install Dependencies

```bash
composer install
composer require laravel/jetstream
php artisan jetstream:install livewire
npm install
```

---

##  Step 2: Environment Configuration

Open **.env** file and update your database settings:

```env
APP_NAME="PHP Laravel 12 Jetstream"
APP_ENV=local
APP_KEY=base64:
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_jetstream
DB_USERNAME=root
DB_PASSWORD=
```

---

##  Step 3: Generate Application Key

```bash
php artisan key:generate
```

---

##  Step 4: Run Migrations

```bash
php artisan migrate
```

This will create the following tables:
*   `users`
*   `password_reset_tokens`
*   `sessions`
*   `personal_access_tokens` (for Sanctum API tokens)

---

##  Step 5: Build Frontend Assets

```bash
npm run build
```

Or for development with hot reloading:

```bash
npm run dev
```

---

##  Step 6: Start the Development Server

```bash
php artisan serve
```

Your application will be available at: `http://localhost:8000`

---

##  Step 7: Run Queue Worker (Optional)

```bash
php artisan queue:work --tries=1
```

---

##  Step 8: Run Logs with Pail (Optional)

```bash
php artisan pail --timeout=0
```

---

##  Step 9: Run Tests (Optional)

```bash
php artisan test
```

---

##  Quick Start - All-in-One Command

To run everything at once (server, queue, logs, and Vite):

```bash
npm run dev
```

Or using Composer:

```bash
composer run dev
```

This will start:
*   Laravel Server: `http://localhost:8000`
*   Queue Worker
*   Pail (Log monitoring)
*   Vite (Asset bundling)

---

##  Project Structure

```
PHP_Laravel12_Jetstream
├── app/
│   ├── Actions/               # Jetstream action classes
│   ├── Http/
│   │   └── Controllers/       # Application controllers
│   ├── Models/                # Eloquent models (User, etc.)
│   ├── Providers/             # Service providers
│   └── View/                  # View composers
├── bootstrap/
│   └── providers.php          # Application service providers
├── config/
│   ├── app.php
│   ├── auth.php               # Authentication configuration
│   ├── jetstream.php          # Jetstream feature flags
│   ├── sanctum.php            # API token configuration
│   └── ...
├── database/
│   ├── factories/             # Model factories for testing
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── public/                    # Publicly accessible files
├── resources/
│   ├── css/                   # CSS source files
│   ├── js/                    # JavaScript source files
│   ├── views/                 # Blade templates
│   │   ├── auth/              # Authentication views (login, register, etc.)
│   │   ├── dashboard.blade.php
│   │   └── layouts/           # Layout components
│   └── markdown/              # Markdown content
├── routes/
│   ├── web.php                # Web routes
│   └── ...
├── storage/                   # Compiled assets, logs, cache
├── tests/                     # Application tests
├── .env                       # Environment configuration
├── .env.example               # Example environment file
├── artisan                    # Laravel CLI
├── composer.json              # PHP dependencies
├── package.json               # Node.js dependencies
├── tailwind.config.js         # Tailwind CSS configuration
├── vite.config.js             # Vite configuration
└── README.md                  # Project documentation
```

---

##  Available Routes

After installation and migration, the following routes are available:

| Route | Method | Description |
|-------|--------|-------------|
| `/` | GET | Welcome page |
| `/register` | GET | Registration form |
| `/login` | GET | Login form |
| `/forgot-password` | GET | Forgot password form |
| `/reset-password/{token}` | GET | Reset password form |
| `/dashboard` | GET | User dashboard (requires auth) |
| `/user/profile` | GET | Edit profile form |
| `/user/profile` | PUT | Update profile |
| `/user/profile` | DELETE | Delete account |
| `/user/two-factor-challenge` | GET | 2FA verification |
| `/user/two-factor-authentication` | GET/POST | Enable/disable 2FA |
| `/user/two-factor-recovery-codes` | GET | View recovery codes |
| `/api/user` | GET | Current authenticated user |
| `/api/tokens` | GET | List API tokens |
| `/api/tokens` | POST | Create API token |
| `/api/tokens/{token}` | DELETE | Delete API token |

---

##  Jetstream Features

The following features are enabled by default in `config/jetstream.php`:

*   **Account Deletion** - Users can delete their accounts

To enable additional features, modify `config/jetstream.php`:

```php
'features' => [
    Features::termsAndPrivacyPolicy(),
    Features::profilePhotos(),
    Features::api(),
    Features::teams(['invitations' => true]),
    Features::accountDeletion(),
],
```

---



---

##  Available Composer Scripts

```bash
composer install           # Install PHP dependencies
composer update            # Update PHP dependencies
composer dump-autoload      # Regenerate autoload files
composer run dev           # Run all dev services (server, queue, logs, vite)
composer run post-autoload-dump  # Post-autoload script
```

---

##  Available NPM Scripts

```bash
npm install                # Install Node.js dependencies
npm run dev                # Start Vite dev server with HMR
npm run build              # Build assets for production
```

---

##  Configuration Reference

### Database Configuration

Edit `config/database.php` and `.env` to switch between SQLite, MySQL, or PostgreSQL.

### Authentication Configuration

Edit `config/auth.php` to change default guards and password brokers.

### Jetstream Configuration

Edit `config/jetstream.php` to enable/disable features and change stack (Livewire/Inertia).

### Fortify Configuration

Edit `config/fortify.php` to customize authentication features (registration, password reset, etc.).

---

##  Common Issues & Solutions

### 1. Storage Permission Errors
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### 2. Composer Memory Limit
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

### 3. Node.js Version Issues
Ensure you have Node.js >= 18.x installed.

### 4. Vite HMR Not Working
Clear cache and restart:
```bash
php artisan view:clear
npm run dev
```

---

##  Output

After successful setup, you can access:

*   **Application:** `http://localhost:8000`
*   **Login:** `http://localhost:8000/login`
*   **Register:** `http://localhost:8000/register`
*   **Dashboard:** `http://localhost:8000/dashboard` (after login)
*   **Profile:** `http://localhost:8000/user/profile` (after login)

---

<<<<<<< HEAD
=======

>>>>>>> development
<img width="1916" height="895" alt="Screenshot 2026-07-24 181152" src="https://github.com/user-attachments/assets/f27e9999-a123-417a-8a9d-e377c44221bc" />
<img width="1915" height="905" alt="Screenshot 2026-07-24 181142" src="https://github.com/user-attachments/assets/3d9c66cc-5869-41c1-a129-360b2f6739b5" />
<img width="1900" height="902" alt="Screenshot 2026-07-24 181228" src="https://github.com/user-attachments/assets/6b82756b-0704-44fa-881a-e4c8aadded7a" />
<img width="1897" height="901" alt="Screenshot 2026-07-24 181337" src="https://github.com/user-attachments/assets/cca74c34-9861-456f-8646-1e69dfffb367" />
<img width="1901" height="912" alt="Screenshot 2026-07-24 181356" src="https://github.com/user-attachments/assets/382788db-ab02-41c2-a31f-ed06338720d9" />
<img width="1902" height="911" alt="Screenshot 2026-07-24 181347" src="https://github.com/user-attachments/assets/bb97bc2c-0c80-4f5e-afab-6e5417941938" />
<<<<<<< HEAD





=======
>>>>>>> development
