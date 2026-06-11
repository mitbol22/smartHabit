# HabitForge — Final Project Report & Technical Documentation

> **"Build discipline, not just habits."**
> A full-stack habit tracking web application with gamification, accountability, and social competition features.

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Features](#2-features)
3. [System Requirements](#3-system-requirements)
4. [Technology Stack](#4-technology-stack)
5. [How the System Works](#5-how-the-system-works)
6. [Database Design & ERD](#6-database-design--erd)
7. [Laravel Component Implementation](#7-laravel-component-implementation)
   - [i. Routes](#i-routes)
   - [ii. Controllers](#ii-controllers)
   - [iii. Models & Relationships](#iii-models--relationships)
   - [iv. Views & User Interface](#iv-views--user-interface)
8. [User Authentication System](#8-user-authentication-system)
9. [Installation & Setup](#9-installation--setup)
10. [Usage Guide](#10-usage-guide)
11. [Project Structure](#11-project-structure)
12. [Testing & Quality Assurance](#12-testing--quality-assurance)
13. [Challenges Faced & Solutions](#13-challenges-faced--solutions)
14. [Future Improvements & Enhancements](#14-future-improvements--enhancements)
15. [Conclusion & Learning Outcomes](#15-conclusion--learning-outcomes)
16. [Contributors](#16-contributors)
17. [References](#17-references)

---

## 1. Project Overview

**Subject**: BIIT 2305

**Group Name**: Purnama

**Section**: 2

**Group Members:**

| Name | Matric Number |
|---|---|
| Adib Zakwan bin Asmadi | 2411695 |
| Ahmad Abbas bin Masnadi | 2414719 |
| Muhammad Hazim bin Khairudin | 2414167 |
| Muhammad Firdaus bin Lukman | 2416377 |
| Muhammad Adib Fikri bin Haidzir | 2310005 |

### Project Proposal

**HabitForge** is a web-based habit management platform designed to help users build and sustain positive habits through a structured reward-and-penalty accountability system. The idea was born from the observation that most habit trackers simply log activity — they do not create genuine accountability. HabitForge bridges this gap by tying habit outcomes to a visible points economy and a shared leaderboard, transforming personal discipline into a social, competitive, and intrinsically motivating experience.

Users create daily or weekly habits, log completions, earn points for success, and receive penalties for missed sessions. A global leaderboard drives friendly competition while personal analytics charts reveal progress over time. The system is designed to be simple enough for first-time users yet deep enough to sustain long-term engagement.

---

## 2. Features

- **Habit Management** — Create, edit, and delete daily or weekly habits with priority levels (High / Medium / Low) and optional start/end dates
- **Daily Check-In System** — Mark habits as Complete or Missed; undo today's check-in if needed
- **Points-Based Gamification** — Earn +10 points per completion; lose −10 points per miss; total points displayed on profile
- **Streak Tracking** — Consecutive completion streaks tracked per habit and displayed prominently
- **Analytics Dashboard** — 7-day bar chart visualization of completed vs. missed habits; success rate, longest streak, and log counters
- **Global Leaderboard** — All users ranked by total points; gold/silver/bronze medal cards for the top 3; authenticated user's own rank highlighted
- **Penalty History Log** — Paginated view of all penalty events with type, affected habit, points deducted, and timestamp
- **User Profile Management** — Edit name/email, change password, delete account (with confirmation modal)
- **Email Verification** — Signed, time-limited verification links sent on registration
- **Password Reset** — Full forgot-password / reset-via-token flow
- **Responsive Mobile-First UI** — Fully usable on phones, tablets, and desktops via Tailwind CSS breakpoints
- **Motivational Quotes** — Rotating curated quotes on the dashboard sidebar

---

## 3. System Requirements

| Requirement | Minimum Version |
|---|---|
| PHP | 8.2 or higher |
| Composer | 2.x |
| Node.js | 18.x or higher |
| NPM | 9.x or higher |
| MySQL | 5.7 or higher (via XAMPP) |
| Web Server | Apache (XAMPP) or Laravel's built-in server |
| Browser | Chrome 110+, Firefox 110+, Safari 16+, Edge 110+ |
| Operating System | Windows 10/11, macOS 12+, Ubuntu 20.04+ |

> **Recommended local setup**: XAMPP 8.2+ on Windows for the easiest MySQL + Apache combination.

---

## 4. Technology Stack

| Layer | Technology | Version |
|---|---|---|
| Backend Framework | Laravel | 12 |
| Language | PHP | 8.2+ |
| Authentication Scaffold | Laravel Breeze | Latest |
| ORM | Eloquent | (Laravel built-in) |
| Templating Engine | Blade | (Laravel built-in) |
| Frontend Styling | Tailwind CSS | 3.1.0 |
| Build Tool | Vite | 7.0.7 |
| JavaScript Framework | Alpine.js | 3.4.2 |
| Icon Library | Font Awesome | 6.5.1 |
| Typography | Figtree (Bunny CDN) | — |
| Database | MySQL | 5.7+ |
| HTTP Client | Axios | Latest |
| Testing Framework | PestPHP | 3.8 |
| Code Formatter | Laravel Pint | Latest |

---

## 5. How the System Works

### Application Flow

HabitForge follows a standard MVC (Model-View-Controller) architecture powered by Laravel. The system flow is as follows:

```
Browser Request
      │
      ▼
  routes/web.php  ──── matches URI + HTTP method
      │
      ▼
  Middleware Stack  ──── auth, verified, CSRF
      │
      ▼
  Controller  ──── business logic, query Eloquent models
      │
      ▼
  Eloquent Model  ──── interacts with MySQL database
      │
      ▼
  Blade View  ──── renders HTML, Tailwind CSS, Alpine.js
      │
      ▼
  Browser Response
```

### Core Business Logic

1. **Habit Creation** — A user submits the habit form. `HabitController@store` validates the input via `StoreHabitRequest`, creates a `Habit` record associated with the authenticated user, and redirects to the habit list.

2. **Check-In System** — When a user marks a habit as Complete or Miss, `HabitLogController@checkIn` creates or updates a `HabitLog` record for today's date. The system then fires either a `HabitCompleted` or `HabitMissed` event. Event listeners respond by writing a `Point` record (+10 or −10) and, for misses, a `Penalty` record to the database.

3. **Undo System** — `HabitLogController@undo` deletes today's `HabitLog` and reverses the associated `Point` and `Penalty` records, restoring the user's score to its pre-check-in state.

4. **Points & Leaderboard** — All point transactions are stored in the `points` table keyed to `user_id`. The leaderboard query sums each user's `points` column and orders descending. This design means the leaderboard always reflects real-time totals.

5. **Analytics** — `AnalyticsController` queries the last 7 days of `HabitLog` records for the authenticated user, groups them by date, and returns completion and miss counts per day to power the CSS bar chart.

6. **Streak Tracking** — Each `HabitLog` record stores a `streak_count` value calculated at check-in time. The controller walks backwards through previous logs to determine the current streak before writing the new record.

---

## 6. Database Design & ERD

### Entity Relationship Diagram

```
┌───────────┐        ┌──────────────┐        ┌────────────────┐
│   users   │ 1    * │    habits    │ 1    * │   habit_logs   │
│───────────│────────│──────────────│────────│────────────────│
│ id (PK)   │        │ id (PK)      │        │ id (PK)        │
│ name      │        │ user_id (FK) │        │ habit_id (FK)  │
│ email     │        │ title        │        │ date           │
│ password  │        │ description  │        │ status         │
│ email_    │        │ frequency    │        │ streak_count   │
│ verified_ │        │ target_count │        │ created_at     │
│ at        │        │ priority     │        │ updated_at     │
│ remember_ │        │ start_date   │        └──────┬─────────┘
│ token     │        │ end_date     │               │ 1
│ created_at│        │ created_at   │               │
│ updated_at│        │ updated_at   │         ┌─────┴──────────────┐
└─────┬─────┘        └──────────────┘         │                    │
      │ 1                                      │ *                  │ *
      │                               ┌────────────────┐  ┌─────────────────┐
      │ *                             │     points     │  │    penalties    │
      │                               │────────────────│  │─────────────────│
      └──────────────────────────────►│ id (PK)        │  │ id (PK)         │
                                      │ user_id (FK)   │  │ user_id (FK)    │
                                      │ habit_log_id   │  │ habit_log_id    │
                                      │   (FK,nullable)│  │   (FK)          │
                                      │ type           │  │ penalty_type    │
                                      │ points         │  │ penalty_value   │
                                      │ created_at     │  │ reason          │
                                      │ updated_at     │  │ created_at      │
                                      └────────────────┘  │ updated_at      │
                                                           └─────────────────┘
```

### Table Definitions

#### `users`
| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT UNSIGNED PK | Auto-increment |
| `name` | VARCHAR(255) | |
| `email` | VARCHAR(255) | Unique |
| `email_verified_at` | TIMESTAMP | Nullable |
| `password` | VARCHAR(255) | Bcrypt hash |
| `remember_token` | VARCHAR(100) | Nullable |
| `created_at` / `updated_at` | TIMESTAMP | |

#### `habits`
| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT UNSIGNED PK | |
| `user_id` | BIGINT UNSIGNED FK | References `users.id`, cascade delete |
| `title` | VARCHAR(255) | |
| `description` | TEXT | Nullable |
| `frequency` | ENUM | `daily`, `weekly` |
| `target_count` | INT | Default 1 |
| `priority` | ENUM | `high`, `medium`, `low` |
| `start_date` | DATE | Nullable |
| `end_date` | DATE | Nullable |
| `created_at` / `updated_at` | TIMESTAMP | |

#### `habit_logs`
| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT UNSIGNED PK | |
| `habit_id` | BIGINT UNSIGNED FK | References `habits.id`, cascade delete |
| `date` | DATE | |
| `status` | ENUM | `completed`, `missed`, `skipped` |
| `streak_count` | INT | Default 0 |
| `created_at` / `updated_at` | TIMESTAMP | |

#### `points`
| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT UNSIGNED PK | |
| `user_id` | BIGINT UNSIGNED FK | References `users.id` |
| `habit_log_id` | BIGINT UNSIGNED FK | References `habit_logs.id`, nullable |
| `type` | ENUM | `reward`, `penalty` |
| `points` | INT | Positive or negative value |
| `created_at` / `updated_at` | TIMESTAMP | |

#### `penalties`
| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT UNSIGNED PK | |
| `user_id` | BIGINT UNSIGNED FK | References `users.id` |
| `habit_log_id` | BIGINT UNSIGNED FK | References `habit_logs.id` |
| `penalty_type` | ENUM | `points_deduction`, `streak_reset`, `warning` |
| `penalty_value` | INT | |
| `reason` | VARCHAR(255) | |
| `created_at` / `updated_at` | TIMESTAMP | |

---

## 7. Laravel Component Implementation

### i. Routes

Routes are defined in `routes/web.php` and `routes/auth.php`.

#### Application Routes (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Description |
|---|---|---|---|---|
| GET | `/` | (closure) | public | Landing / Welcome page |
| GET | `/dashboard` | `DashboardController@index` | auth, verified | Dashboard with stats & today's habits |
| GET | `/habits` | `HabitController@index` | auth | List all habits |
| GET | `/habits/create` | `HabitController@create` | auth | Create habit form |
| POST | `/habits` | `HabitController@store` | auth | Store new habit |
| GET | `/habits/{habit}` | `HabitController@show` | auth | Single habit detail |
| GET | `/habits/{habit}/edit` | `HabitController@edit` | auth | Edit habit form |
| PUT/PATCH | `/habits/{habit}` | `HabitController@update` | auth | Update habit |
| DELETE | `/habits/{habit}` | `HabitController@destroy` | auth | Delete habit |
| POST | `/habits/{habit}/check-in` | `HabitLogController@checkIn` | auth | Log completion or miss |
| DELETE | `/habits/{habit}/undo` | `HabitLogController@undo` | auth | Undo today's check-in |
| GET | `/analytics` | `AnalyticsController@index` | auth | Progress analytics view |
| GET | `/penalties` | `PenaltyController@index` | auth | Penalty history view |
| GET | `/leaderboard` | `LeaderboardController@index` | auth | Global leaderboard |
| GET/PUT | `/profile` | `ProfileController@edit/update` | auth | View / update profile |
| DELETE | `/profile` | `ProfileController@destroy` | auth | Delete account |

#### Authentication Routes (`routes/auth.php`)

| Method | URI | Description |
|---|---|---|
| GET/POST | `/register` | User registration |
| GET/POST | `/login` | User login |
| POST | `/logout` | User logout |
| GET/POST | `/forgot-password` | Request password reset |
| GET/POST | `/reset-password/{token}` | Reset with token |
| GET | `/verify-email` | Email verification prompt |
| GET | `/verify-email/{id}/{hash}` | Verify email link |
| POST | `/email/verification-notification` | Resend verification |
| GET/POST | `/confirm-password` | Confirm password for sensitive actions |
| PUT | `/password` | Update password |

**Total: 26 routes covering full CRUD, gamification, analytics, and authentication.**

---

### ii. Controllers

All controllers reside in `app/Http/Controllers/`.

#### `DashboardController.php`
Aggregates data for the main dashboard: total habit count, current streak, total points (summed from the `points` table), weekly penalties, and today's habit log status. Also passes a motivational quote selected from a curated static array.

#### `HabitController.php`
Full CRUD resource controller protected by `HabitPolicy` authorization to ensure users can only manage their own habits.
- `index()` — paginated habit list ordered by priority
- `create()` / `store()` — validated habit creation via `StoreHabitRequest`
- `show()` — single habit detail with log history
- `edit()` / `update()` — pre-filled edit form and update logic
- `destroy()` — delete habit with cascading log deletion

#### `HabitLogController.php`
Handles check-in and undo operations:
- `checkIn()` — creates or updates a `HabitLog` for today; fires `HabitCompleted` or `HabitMissed` events that trigger point/penalty listeners
- `undo()` — deletes today's log entry and reverses the associated `Point` and `Penalty` records

#### `AnalyticsController.php`
Calculates a 7-day rolling window of completed/missed logs per day. Computes overall success rate, total logs, longest streak, and current streak. All data passed to the analytics view for chart rendering.

#### `LeaderboardController.php`
Queries all users, sums their points, orders descending, and passes the ranked list (with the authenticated user's own rank highlighted) to the leaderboard view.

#### `PenaltyController.php`
Paginates the authenticated user's penalty records with related habit names, showing type, value, reason, and timestamp.

#### `ProfileController.php`
Manages profile information update, password change, and account deletion (with password confirmation via modal).

#### Auth Controllers (`app/Http/Controllers/Auth/`)

| Controller | Responsibility |
|---|---|
| `AuthenticatedSessionController` | Login / logout with session regeneration |
| `RegisteredUserController` | Registration with hashed password + auto-login |
| `PasswordResetLinkController` | Send password reset email |
| `NewPasswordController` | Validate token and set new password |
| `PasswordController` | In-profile password update |
| `EmailVerificationPromptController` | Show verification prompt |
| `VerifyEmailController` | Process signed verification link |
| `EmailVerificationNotificationController` | Resend verification (rate-limited) |
| `ConfirmablePasswordController` | Re-confirm password for sensitive actions |

**Total: 7 application controllers + 9 auth controllers = 16 controllers.**

---

### iii. Models & Relationships

All Eloquent models live in `app/Models/`.

#### `User.php`
Represents an authenticated user.

**Fields:** `id`, `name`, `email`, `password`, `email_verified_at`, `remember_token`

**Relationships:**
- `hasMany(Habit::class)` — all habits owned by the user
- `hasMany(Point::class)` — all point records for the user
- `hasMany(Penalty::class)` — all penalty records for the user

---

#### `Habit.php`
Represents a trackable habit.

**Fields:** `id`, `user_id`, `title`, `description`, `frequency`, `target_count`, `priority`, `start_date`, `end_date`

**Relationships:**
- `belongsTo(User::class)`
- `hasMany(HabitLog::class)`

---

#### `HabitLog.php`
One record per check-in attempt for a given day or week.

**Fields:** `id`, `habit_id`, `date`, `status`, `streak_count`

**Relationships:**
- `belongsTo(Habit::class)`
- `hasOne(Point::class)`
- `hasOne(Penalty::class)`

---

#### `Point.php`
Tracks reward and penalty point transactions.

**Fields:** `id`, `user_id`, `habit_log_id` (nullable), `type`, `points`

**Relationships:**
- `belongsTo(User::class)`
- `belongsTo(HabitLog::class)`

---

#### `Penalty.php`
Logs accountability penalties when a habit is missed.

**Fields:** `id`, `user_id`, `habit_log_id`, `penalty_type`, `penalty_value`, `reason`

**Relationships:**
- `belongsTo(User::class)`
- `belongsTo(HabitLog::class)`

---

#### Relationship Overview

```
User ──< Habit ──< HabitLog ──< Point
  │                    └──────< Penalty
  ├──< Point
  └──< Penalty
```

**5 models with full Eloquent relationships, foreign key constraints, and migration-backed schema.**

---

### iv. Views & User Interface

All Blade templates are in `resources/views/`.

#### Layouts

| File | Purpose |
|---|---|
| `layouts/app.blade.php` | Authenticated shell — includes navigation bar and Vite asset injection |
| `layouts/guest.blade.php` | Guest shell — centred card layout for auth pages |
| `layouts/navigation.blade.php` | Sticky top nav with logo, page links, user dropdown, and mobile hamburger |

#### Core Application Views

| File | Description |
|---|---|
| `welcome.blade.php` | Public landing page with hero headline, tagline, and call-to-action |
| `dashboard.blade.php` | Stats cards (habits, streak, points, penalties), today's habit checklist, weekly progress bar, motivational quote sidebar |
| `habits/index.blade.php` | Responsive grid of habit cards with priority badges, frequency labels, and edit/delete actions |
| `habits/create.blade.php` | Habit creation form with full field set and inline validation messages |
| `habits/edit.blade.php` | Pre-filled edit form |
| `habits/show.blade.php` | Individual habit detail — stats, check-in button, log history |
| `analytics/index.blade.php` | 7-day CSS bar chart, success rate, streak display, completed/missed counters |
| `leaderboard/index.blade.php` | Ranked list with gold/silver/bronze medal cards, user's own rank card, information sidebar |
| `penalties/index.blade.php` | Penalty history cards — type, affected habit, date/time, points deducted |
| `profile/edit.blade.php` | Profile with three partials: update info, change password, delete account |

#### Auth Views
`auth/login.blade.php` · `auth/register.blade.php` · `auth/forgot-password.blade.php` · `auth/reset-password.blade.php` · `auth/verify-email.blade.php` · `auth/confirm-password.blade.php`

#### Reusable Components (`resources/views/components/`)

| Component | Purpose |
|---|---|
| `primary-button` | Black filled CTA button |
| `secondary-button` | Outlined secondary action button |
| `danger-button` | Red destructive action button |
| `text-input` | Styled form input with focus ring |
| `input-label` | Accessible form label |
| `input-error` | Inline validation error message |
| `dropdown` / `dropdown-link` | Alpine.js-powered dropdown menu |
| `modal` | Confirmation dialog (used for account deletion) |
| `nav-link` / `responsive-nav-link` | Desktop and mobile navigation links |
| `auth-session-status` | Flash session status banner |

**Total: 22+ Blade templates and 12 reusable components.**

#### Design System

HabitForge uses a **modern minimalist** design language — high-contrast black-and-white primary palette with strategically applied semantic accent colours, inspired by productivity and fintech applications.

**Colour Palette:**

| Role | Colour | Tailwind Class | Usage |
|---|---|---|---|
| Primary | `#000000` (Black) | `bg-black`, `text-black` | CTAs, logo, emphasis text |
| Background | `#FDFDFC` (Off-white) | — | Page background |
| Surface | `#FFFFFF` (White) | `bg-white` | Cards, forms, modals |
| Success | `#22c55e` (Green) | `green-500` | Completed habits, streaks |
| Danger | `#ef4444` (Red) | `red-500` | Missed habits, penalties |
| Warning | `#f97316` (Orange) | `orange-500` | Streak fire, warnings |
| Accent | `#eab308` (Yellow) | `yellow-500` | Points, stars |
| Info | `#3b82f6` (Blue) | `blue-500` | Secondary actions, metrics |
| Muted | `#6b7280` (Gray-500) | `text-gray-500` | Subtitles, helper text |
| Border | `#f3f4f6` (Gray-100) | `border-gray-100` | Card borders, dividers |

---

## 8. User Authentication System

Authentication is implemented using **Laravel Breeze**, providing a complete, production-ready authentication scaffold.

### Authentication Features

#### Registration (`/register`)
- Validates: `name`, `email` (unique), `password` (confirmed, minimum 8 characters)
- Password hashed with `Hash::make()` (bcrypt, cost factor 12)
- Fires `Registered` event which triggers email verification dispatch
- Auto-logs the user in after registration and redirects to `/dashboard`

#### Login (`/login`)
- Credential validation via email + password
- Uses Laravel's `Auth::attempt()` with session regeneration on success to prevent session fixation attacks
- "Remember Me" checkbox creates a long-lived remember cookie
- Guest middleware redirects already-authenticated users away from the login page

#### Email Verification
- Signed, time-limited verification links dispatched to the registered email address
- `VerifyEmailController` validates the URL signature before marking email as verified
- Rate-limited to 6 resend attempts per minute to prevent abuse
- Routes that require the `verified` middleware block access until the email is confirmed

#### Password Reset
- User submits email on `/forgot-password`; a signed password reset link is emailed
- `/reset-password/{token}` validates the token (single-use, expiry enforced) and updates the password via `NewPasswordController`
- Old password hashes are invalidated after a successful reset

### Security Measures

| Measure | Implementation |
|---|---|
| Password hashing | Bcrypt via `Hash::make()` |
| CSRF protection | `@csrf` token on every form; Laravel middleware validates on all state-changing requests |
| Session fixation prevention | `Auth::login()` calls `session()->regenerate()` on login |
| Session invalidation on logout | `Auth::logout()` + `session()->invalidate()` + `session()->regenerateToken()` |
| Signed email links | `URL::temporarySignedRoute()` for email verification and password reset |
| Authorization policies | `HabitPolicy` enforced via `Gate::authorize()` in every `HabitController` method |
| Password confirmation | Sensitive actions (account deletion, in-profile password change) require re-entry of current password |
| Rate limiting | Email verification resend limited to 6/minute; login protected by Laravel's default throttle middleware |
| Input validation | All form input validated via `FormRequest` classes before reaching business logic |
| XSS protection | All output escaped by Blade's `{{ }}` syntax by default |

### Navigation / User Flow

```
Landing (/)
  └─ Get Started / Log In
       ├─ Register (/register) ──► Dashboard (/dashboard)
       └─ Login (/login) ──────► Dashboard (/dashboard)
                                     │
              ┌──────────────────────┼──────────────────────┐
              ▼                      ▼                       ▼
        Habits (/habits)     Analytics (/analytics)   Leaderboard (/leaderboard)
          ├─ Create           (7-day chart, streaks)   (ranked users, medals)
          ├─ Edit
          ├─ View
          └─ Check In ──────────────────────────► Points / Penalties
                                                    └─ Penalties (/penalties)
```

---

## 9. Installation & Setup

### Prerequisites

Ensure the following are installed before proceeding:

- [XAMPP](https://www.apachefriends.org/) 8.2+ (includes PHP 8.2 and MySQL)
- [Composer](https://getcomposer.org/) 2.x
- [Node.js](https://nodejs.org/) 18+ and NPM 9+
- Git

### Step-by-Step Installation

```bash
# 1. Clone the repository
git clone https://github.com/mitbol22/smartHabit.git
cd smartHabit

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Copy the environment file and generate application key
cp .env.example .env
php artisan key:generate
```

**5. Configure the database** — open `.env` and update these values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smarthabit
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 6. Create the database in XAMPP's phpMyAdmin
#    (create a database named "smarthabit")

# 7. Run database migrations
php artisan migrate

# 8. Build frontend assets
npm run build
```

### Running the Application

**Option A — Laravel development server:**
```bash
php artisan serve
```
Visit `http://localhost:8000`

**Option B — XAMPP virtual host (recommended for full MySQL support):**
1. Start Apache and MySQL in the XAMPP Control Panel
2. Visit `http://localhost/smartHabit/public`

### Development Mode (hot-reload)

To run Vite's hot module replacement server alongside Laravel:

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

---

## 10. Usage Guide

### First-Time User

1. Navigate to `http://localhost:8000` and click **Get Started**
2. Register with your name, email, and a password
3. Verify your email address (check your inbox for the verification link)
4. You will land on the **Dashboard**

### Creating a Habit

1. Click **Habits** in the top navigation
2. Click **Create Habit**
3. Fill in:
   - **Title** (required)
   - **Description** (optional)
   - **Frequency** — Daily or Weekly
   - **Priority** — High, Medium, or Low
   - **Target Count** — how many completions per period
   - **Start / End Date** (optional)
4. Click **Create Habit** — you will be redirected to your habit list

### Daily Check-In

1. Go to the **Dashboard** — today's habits are listed in the main section
2. Click the green **✓ Complete** button when you finish a habit (+10 points)
3. Click the red **✗ Miss** button to mark a habit as missed (−10 points)
4. Click **↺ Undo** to reverse a check-in made today

### Tracking Progress

- **Dashboard** — see today's completions, total streak, points, and weekly penalties at a glance
- **Progress** (Analytics page) — view the 7-day bar chart, success rate, and streak statistics
- **Penalties** — review every penalty event with details on which habit triggered it

### Leaderboard

Navigate to **Leaderboard** to see how your total points compare against all other registered users. The top 3 users receive gold, silver, and bronze medal cards.

---

## 11. Project Structure

```
smartHabit/
├── app/
│   ├── Events/
│   │   ├── HabitCompleted.php
│   │   └── HabitMissed.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/               (9 Breeze auth controllers)
│   │   │   ├── AnalyticsController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── HabitController.php
│   │   │   ├── HabitLogController.php
│   │   │   ├── LeaderboardController.php
│   │   │   ├── PenaltyController.php
│   │   │   └── ProfileController.php
│   │   └── Requests/
│   │       ├── StoreHabitRequest.php
│   │       ├── ProfileUpdateRequest.php
│   │       └── Auth/LoginRequest.php
│   ├── Listeners/
│   ├── Models/
│   │   ├── Habit.php
│   │   ├── HabitLog.php
│   │   ├── Penalty.php
│   │   ├── Point.php
│   │   └── User.php
│   ├── Policies/
│   │   └── HabitPolicy.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_habits_table.php
│   │   ├── ..._create_habit_logs_table.php
│   │   ├── ..._create_points_table.php
│   │   └── ..._create_penalties_table.php
│   ├── factories/
│   └── seeders/
├── resources/
│   ├── css/
│   │   └── app.css                 (Tailwind entry point)
│   ├── js/
│   │   └── app.js                  (Alpine.js bootstrap)
│   └── views/
│       ├── analytics/
│       ├── auth/
│       ├── components/
│       ├── habits/
│       ├── layouts/
│       ├── leaderboard/
│       ├── penalties/
│       ├── profile/
│       ├── dashboard.blade.php
│       └── welcome.blade.php
├── routes/
│   ├── auth.php
│   └── web.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── .env.example
├── composer.json
├── package.json
├── tailwind.config.js
└── vite.config.js
```

---

## 12. Testing & Quality Assurance

### Functional Testing

All core features were manually tested by each group member against the following checklist:

| Feature | Test Case | Expected Result | Status |
|---|---|---|---|
| Registration | Valid input | Account created, email verification sent, redirect to dashboard | ✅ Pass |
| Registration | Duplicate email | Validation error shown | ✅ Pass |
| Login | Correct credentials | Redirect to dashboard | ✅ Pass |
| Login | Wrong password | Error message shown | ✅ Pass |
| Create Habit | Valid form submission | Habit appears in list | ✅ Pass |
| Create Habit | Missing required field | Validation error inline | ✅ Pass |
| Check-In (Complete) | Mark daily habit complete | +10 points, green row, streak incremented | ✅ Pass |
| Check-In (Miss) | Mark daily habit missed | −10 points, penalty created, red row | ✅ Pass |
| Undo | Undo same-day check-in | Points/penalty reversed, row returns to default | ✅ Pass |
| Edit Habit | Update title and priority | Changes reflected immediately | ✅ Pass |
| Delete Habit | Confirm deletion | Habit and all logs removed | ✅ Pass |
| Analytics | View after check-ins | Bar chart reflects correct data | ✅ Pass |
| Leaderboard | Multiple users | Sorted by total points descending | ✅ Pass |
| Profile Update | Change name/email | Profile updated, confirmation shown | ✅ Pass |
| Password Change | Valid current + new passwords | Password updated | ✅ Pass |
| Account Deletion | Confirm via modal + password | Account and all data deleted | ✅ Pass |
| Authorization | Access another user's habit URL | 403 Forbidden returned | ✅ Pass |

### Browser Compatibility Testing

| Browser | Version Tested | Result |
|---|---|---|
| Google Chrome | 124 | ✅ Full compatibility |
| Mozilla Firefox | 125 | ✅ Full compatibility |
| Microsoft Edge | 124 | ✅ Full compatibility |
| Safari (macOS) | 17 | ✅ Full compatibility |
| Chrome (Android) | 124 | ✅ Responsive layout correct |

### Performance Testing

- **Page load times** measured with Chrome DevTools: all pages under 500ms on localhost
- **Database queries** reviewed with Laravel Debugbar during development — all list views use eager loading (`with()`) to avoid N+1 query problems
- **Asset optimization** — Vite bundles and minifies all CSS/JS for production builds (`npm run build`)

### Automated Tests

The project uses **PestPHP 3.8** as the testing framework. Basic feature tests were written for authentication flows:

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage
```

Key test files:
- `tests/Feature/Auth/RegistrationTest.php`
- `tests/Feature/Auth/AuthenticationTest.php`
- `tests/Feature/Auth/PasswordResetTest.php`
- `tests/Feature/ProfileTest.php`

---

## 13. Challenges Faced & Solutions

### Challenge 1: Undo Check-In Logic with Points Reversal

**Problem:** When a user undoes a check-in, the associated `Point` and `Penalty` records needed to be reversed atomically. An incomplete deletion would leave the user's score in an inconsistent state.

**Solution:** Wrapped the undo operation in a database transaction using `DB::transaction()`. The controller deletes the `HabitLog`, its `Point` record, and its `Penalty` record (if it exists) within a single atomic operation. If any step fails, the entire transaction rolls back, preserving data integrity.

---

### Challenge 2: Leaderboard Showing Real-Time Point Totals

**Problem:** Querying all users and summing their points in a single leaderboard query was initially slow and returning incorrect totals when points were inserted by event listeners.

**Solution:** Stored all point transactions as rows in the `points` table (rather than a single column on `users`). The leaderboard uses a `withSum('points', 'points')` Eloquent eager load to pull the aggregate in a single query, ordered by the sum column. This kept the query to two database calls regardless of user count.

---

### Challenge 3: 7-Day Analytics Bar Chart Without a JavaScript Charting Library

**Problem:** Including a full chart library (e.g., Chart.js) added significant bundle weight and complexity for what was essentially a simple bar chart.

**Solution:** Built the 7-day chart entirely in HTML and Tailwind CSS. Each bar is a `flex` column with a calculated `height` percentage (passed from the controller as a PHP variable). Green segments represent completions and red segments represent misses, stacked within each day's column. This approach requires zero JavaScript, loads instantly, and fits the minimalist design.

---

### Challenge 4: Authorization Across Habit Resources

**Problem:** Early versions of the controller used manual `if ($habit->user_id !== Auth::id()) abort(403)` checks — inconsistent and easy to forget on new methods.

**Solution:** Implemented a dedicated `HabitPolicy` class registered in `AppServiceProvider`. Every `HabitController` method calls `Gate::authorize('action', $habit)`, centralizing authorization logic and making it impossible to accidentally skip it on a new route.

---

### Challenge 5: Mobile Navigation with Alpine.js

**Problem:** Tailwind CSS provides responsive utility classes but does not include JavaScript behaviour for toggling a mobile menu.

**Solution:** Used Alpine.js's `x-data="{ open: false }"` on the nav wrapper with `@click` to toggle the boolean and `x-show` with a CSS transition on the menu panel. This added interactive mobile navigation without importing a separate JavaScript component library.

---

## 14. Future Improvements & Enhancements

### Short-Term Enhancements

- **Push Notifications / Reminders** — Browser push notifications or email reminders at a user-configured time each day to prompt habit check-ins for users who have not yet logged
- **Habit Categories / Tags** — Allow users to group habits by category (Health, Learning, Finance) with filter and sort options on the habits list
- **Streak Freeze** — A consumable "freeze token" that prevents a streak from breaking on a missed day, rewarding consistent performers
- **Social Sharing** — Share a weekly summary card (habit count, streak, points) to social media or via a unique public link

### Medium-Term Features

- **Friends / Follow System** — Follow other users; see a friend-only leaderboard subset; receive notifications when a friend achieves a milestone
- **Habit Templates** — Pre-built habit bundles (Morning Routine, Study Pack) that users can import and customize
- **Advanced Analytics** — Monthly and yearly aggregated charts; weekday-vs-weekend completion breakdown; habit correlation analysis (e.g., habits completed together most often)
- **Dark Mode** — System-preference-aware dark colour scheme via Tailwind's `dark:` variant
- **Multi-language Support** — Internationalization (i18n) using Laravel's translation files for Bahasa Malaysia and English

### Long-Term Vision

- **Mobile Application** — Native iOS and Android apps consuming a HabitForge REST API (Laravel Sanctum token authentication)
- **Team / Group Challenges** — Create group challenges where teams compete collectively; useful for classrooms, workplaces, and friend groups
- **AI Habit Coach** — An integrated AI assistant that analyzes completion patterns and suggests personalized habit scheduling, difficulty adjustments, and motivational strategies
- **Wearable Integration** — Sync with fitness trackers (Fitbit, Garmin, Apple Health) to automatically complete exercise-based habits

---

## 15. Conclusion & Learning Outcomes

### Project Summary

HabitForge successfully delivers a full-stack, production-ready habit tracking platform built on the Laravel 12 framework. All core features defined in the project proposal were implemented and tested: habit CRUD, a check-in system with gamified points and penalties, analytics visualization, a competitive leaderboard, and a comprehensive authentication system with email verification and password reset.

### Technical Skills Gained

| Skill | Application in This Project |
|---|---|
| Laravel MVC Architecture | Structured the entire application using controllers, models, views, and service layers |
| Eloquent ORM & Relationships | Designed and queried a relational database with five interconnected models |
| Laravel Breeze Authentication | Implemented complete auth including email verification and password reset |
| Blade Templating | Built 22+ reusable, component-based UI templates |
| Tailwind CSS | Designed a mobile-first, fully responsive UI without writing custom CSS |
| Alpine.js | Added interactive UI behaviour (dropdowns, modals, mobile menu) without a heavy framework |
| Database Design | Designed a normalized relational schema with foreign key constraints and cascade rules |
| Laravel Policies & Gates | Implemented row-level authorization to protect user data |
| Event & Listener Pattern | Decoupled check-in logic from point/penalty creation using Laravel Events |
| Vite & Asset Bundling | Configured and built a modern frontend asset pipeline within a PHP application |
| PestPHP Testing | Wrote automated feature tests for authentication and profile flows |

### Soft Skills Developed

- **Collaborative Development** — Using Git for version control and branch management across a five-person team
- **Task Division & Project Management** — Breaking down the project into milestones and assigning features to individual members
- **Technical Communication** — Documenting decisions, reviewing each other's code, and maintaining consistent coding standards
- **Problem-Solving Under Constraints** — Delivering working features within an academic timeline while learning new technologies

### Key Achievements

- Delivered a production-quality application with 26 routes, 16 controllers, 5 models, and 22+ views
- Implemented a real-time points economy that drives engagement through the leaderboard
- Built a zero-dependency CSS bar chart for analytics, reducing bundle size
- Maintained a clean, consistent design system across all 22+ pages using Tailwind utility classes
- Achieved full mobile responsiveness without a separate mobile codebase

### Project Impact

HabitForge demonstrates that a small team of students can design, implement, and document a full-stack web application with real-world features — authentication, gamification, analytics, and authorization — using modern tools and best practices. The project serves as a working portfolio piece and a foundation for future feature development.

---

## 16. Contributors

| Name | Matric Number | Role |
|---|---|---|
| Adib Zakwan bin Asmadi | 2411695 | Project Lead / Backend |
| Ahmad Abbas bin Masnadi | 2414719 | Backend / Database Design |
| Muhammad Hazim bin Khairudin | 2414167 | Frontend / UI Design |
| Muhammad Firdaus bin Lukman | 2416377 | Authentication / Security |
| Muhammad Adib Fikri bin Haidzir | 2310005 | Analytics / Testing |

**Lecturer:** Nor Azura binti Kamarulzaman

---

## 17. References

1. Taylor Otwell et al. *Laravel 12 Documentation*. Laravel. Retrieved from https://laravel.com/docs/12.x

2. Laravel Breeze Documentation. *Starter Kits — Laravel Breeze*. Retrieved from https://laravel.com/docs/12.x/starter-kits#breeze

3. Tailwind Labs. *Tailwind CSS v3 Documentation*. Retrieved from https://tailwindcss.com/docs

4. Caleb Porzio. *Alpine.js Documentation*. Retrieved from https://alpinejs.dev/start-here

5. Font Awesome. *Font Awesome 6 — Icons Reference*. Retrieved from https://fontawesome.com/icons

6. Vite. *Vite — Next Generation Frontend Tooling*. Retrieved from https://vite.dev/guide/

7. PestPHP. *Pest — An Elegant PHP Testing Framework*. Retrieved from https://pestphp.com/docs

8. OWASP Foundation. *OWASP Top Ten 2021*. Retrieved from https://owasp.org/www-project-top-ten/

9. Duhigg, Charles. *The Power of Habit: Why We Do What We Do in Life and Business*. Random House, 2012.

10. Clear, James. *Atomic Habits: An Easy & Proven Way to Build Good Habits & Break Bad Ones*. Avery, 2018.

---

*HabitForge — BIIT 2305 Final Project, Group Purnama, Section 2*
