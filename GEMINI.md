# HabitForge Project Overview

HabitForge is a web-based application built with the Laravel PHP framework, designed to revolutionize personal habit formation. It leverages behavioral psychology principles by implementing a unique system that combines reward incentives with penalty consequences to drive consistent habit adherence. The application aims to foster self-discipline through technology, acting as a digital accountability partner.

## Key Features:

*   **User Management & Authentication:** Secure user registration, login/logout, profile management, and role-based access using Laravel Breeze.
*   **Habit Management (CRUD):** Users can create, read, update, and delete personal habits with customizable parameters (frequency, target count, start/end dates).
*   **Daily Check-in System:** Allows users to mark habits as completed, missed, or skipped, tracking daily progress.
*   **Penalty Mechanism:** Automated penalty system (e.g., point deduction, streak resets) for missed habits, driven by events and listeners.
*   **Reward & Gamification System:** Accumulation of points for habit completion, badges, and progression tiers.
*   **Dashboard & Analytics:** Real-time progress visualization through a personalized dashboard displaying habit statistics, points balance, streaks, and penalties. Includes a basic analytics overview.
*   **Scalable MVC Architecture:** Adheres to Laravel's MVC pattern for maintainable and extensible code.
*   **Authorization:** Utilizes Laravel Policies (e.g., `HabitPolicy`) to ensure users can only manage their own data.

## Technologies Used:

*   **Backend:** PHP 8.2+, Laravel 12+
*   **Frontend:** Blade templates, Tailwind CSS, JavaScript (via Vite)
*   **Database:** MySQL (configured via `.env`)
*   **Authentication:** Laravel Breeze
*   **Development Tools:** Composer, NPM/Vite

## Building and Running the Project:

### Setup and Installation:

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd smartHabit
    ```
2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```
3.  **Configure Environment:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *   **Edit `.env`:** Update your database credentials to use MySQL and ensure the database exists in phpMyAdmin.
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=habitforge # Replace with your database name
        DB_USERNAME=root
        DB_PASSWORD=
        ```
4.  **Run Migrations:**
    ```bash
    php artisan migrate:fresh
    ```
5.  **Install Frontend Dependencies:**
    ```bash
    npm install
    ```
6.  **Build Frontend Assets:**
    ```bash
    npm run build
    ```

### Running the Application:

To run the application in development mode:

1.  **Start Laravel Development Server:**
    ```bash
    php artisan serve
    ```
2.  **Start Vite Development Server (for frontend assets):**
    ```bash
    npm run dev
    ```
    (Alternatively, you can use `composer dev` if configured in `composer.json` to run both simultaneously).

The application should now be accessible at `http://127.0.0.1:8000` (or the port displayed by `php artisan serve`).

### Running Tests:

```bash
php artisan test
```

## Development Conventions:

*   **Code Style:** Follows Laravel's official coding standards (PSR-2, PSR-4). `laravel/pint` is available for code style fixing.
*   **Architectural Pattern:** Model-View-Controller (MVC).
*   **Database Migrations:** All database schema changes are managed through Laravel migrations.
*   **Authorization:** Laravel Policies are used to manage user permissions and access control.
*   **Events & Listeners:** Logic decoupling for actions like penalty application and reward granting.
*   **Form Requests:** Used for request validation to keep controllers clean.
*   **UI/UX:** Designed to match the mockups provided in `Part 1 (Proposal).pdf`, using Tailwind CSS for styling.
