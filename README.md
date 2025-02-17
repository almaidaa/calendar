This project is built with Laravel 11 and PHP 8.2. It includes OneSignal and email notifications. A stable internet connection is required for CDN usage.

## Get Started

1. Clone the repository:
   ```bash
   git clone https://github.com/almaidaa/calendar almaida-calendar
   ```
   ```bash
   cd almaida-calendar
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Copy the environment example file:
   ```bash
   cp .env.example .env
   ```
4. Run database migrations:
   ```bash
   php artisan migrate
   ```
5. Seed the database:
   ```bash
   php artisan db:seed
   ```
6. Install Node.js dependencies:
   ```bash
   npm install
   ```

## Running the Application

Open three terminal windows and run the following commands:

1. Start the development server:
   ```bash
   npm run dev
   ```
   (for development only)

2. Run the Laravel service:
   ```bash
   php artisan serve
   ```

3. Start the cron jobs:
   ```bash
   php artisan schedule:watch
   ```

## Login Credentials

- **Username:** almaida
- **Password:** almaida

