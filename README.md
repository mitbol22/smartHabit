# HabitForge — Final Project Report

> **"Build discipline, not just habits."**
> A full-stack habit tracking web application with gamification, accountability, and social competition features.

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [Routes](#i-routes)
4. [Controllers](#ii-controllers)
5. [Views](#iii-views)
6. [Models](#iv-models)
7. [User Authentication](#v-user-authentication)
8. [Use of Media](#vi-use-of-media)
9. [Design / Colour Scheme / Layout](#vii-design--colour-scheme--layout)
10. [Navigations / Links](#viii-navigations--links)
11. [Installation & Setup](#installation--setup)

---

## Project Overview

**HabitForge** is a web-based habit management platform that helps users build and sustain positive habits through a reward-and-penalty accountability system. Users create daily or weekly habits, log completions, earn points for success, and receive penalties for missed sessions. A global leaderboard drives friendly competition while personal analytics charts show progress over time.

**Key Features:**
- Create, edit, and delete daily/weekly habits with priority levels
- Daily check-in system (Complete / Miss / Undo)
- Points-based gamification (+10 per completion, −10 per miss)
- Streak tracking across habits
- 7-day analytics bar chart visualization
- Global leaderboard with medal rankings
- Penalty history log
- User profile management with password change and account deletion
- Fully responsive mobile-first UI

---

## Technology Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 (PHP) |
| Authentication | Laravel Breeze |
| Frontend Styling | Tailwind CSS + Vite |
| JavaScript | Alpine.js |
| Icons | Font Awesome 6.5.1 |
| Typography | Figtree (Google Fonts via Bunny CDN) |
| Database | MySQL (via XAMPP) |
| ORM | Eloquent |
| Templating | Blade |

---

## i. Routes

Routes are defined in `routes/web.php` and `routes/auth.php`.

### Application Routes (`routes/web.php`)

| Method | URI | Description | Middleware |
|---|---|---|---|
| GET | `/` | Landing / Welcome page | public |
| GET | `/dashboard` | User dashboard with stats & today's habits | auth, verified |
| GET | `/habits` | List all habits | auth |
| GET | `/habits/create` | Create habit form | auth |
| POST | `/habits` | Store new habit | auth |
| GET | `/habits/{habit}` | View single habit detail | auth |
| GET | `/habits/{habit}/edit` | Edit habit form | auth |
| PUT/PATCH | `/habits/{habit}` | Update habit | auth |
| DELETE | `/habits/{habit}` | Delete habit | auth |
| POST | `/habits/{habit}/check-in` | Log completion or miss | auth |
| DELETE | `/habits/{habit}/undo` | Undo today's check-in | auth |
| GET | `/analytics` | Progress analytics view | auth |
| GET | `/penalties` | Penalty history view | auth |
| GET | `/leaderboard` | Global leaderboard | auth |
| GET/PUT | `/profile` | View / update profile | auth |
| DELETE | `/profile` | Delete account | auth |

### Authentication Routes (`routes/auth.php`)

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
| GET/POST | `/confirm-password` | Confirm password (sensitive actions) |
| PUT | `/password` | Update password |

**Route count: 26 routes covering full CRUD, gamification, analytics, and auth.**

---

## ii. Controllers

All controllers live in `app/Http/Controllers/`.

### Application Controllers

#### `DashboardController.php`
Aggregates data for the main dashboard view: total habit count, current streak, total points (from `points` table), weekly penalties, and today's habit log status. Passes a motivational quote from a curated array.

#### `HabitController.php`
Full CRUD resource controller protected by policy authorization (`HabitPolicy`) to ensure users can only manage their own habits.
- `index()` — paginated habit list
- `create()` / `store()` — validated habit creation (title, description, frequency, priority, target count, start/end date)
- `show()` — single habit with its logs
- `edit()` / `update()` — update habit fields
- `destroy()` — delete habit and cascade logs

#### `HabitLogController.php`
Handles check-in and undo operations:
- `checkIn()` — creates or updates a `HabitLog` record; fires `HabitCompleted` or `HabitMissed` events that trigger point/penalty listeners
- `undo()` — removes today's log entry and reverses the associated point/penalty record

#### `AnalyticsController.php`
Calculates a 7-day rolling window of completed/missed logs per day to power the bar chart. Computes overall success rate, total logs, and longest/current streak.

#### `LeaderboardController.php`
Queries all users and their summed points, orders descending, and passes the ranked list (with the authenticated user's position highlighted) to the view.

#### `PenaltyController.php`
Paginates the authenticated user's penalty records with the related habit name, showing type, value, reason, and timestamp.

#### `ProfileController.php`
Manages profile information update, password change, and account deletion (with password confirmation).

### Auth Controllers (Laravel Breeze — `app/Http/Controllers/Auth/`)

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

## iii. Views

All Blade templates are in `resources/views/`.

### Layouts

| File | Purpose |
|---|---|
| `layouts/app.blade.php` | Authenticated shell — includes navigation, Vite assets |
| `layouts/guest.blade.php` | Guest shell — centred card layout for auth pages |
| `layouts/navigation.blade.php` | Sticky top nav with logo, links, user dropdown, mobile hamburger |

### Core Application Views

| File | Description |
|---|---|
| `welcome.blade.php` | Public landing page with hero headline, tagline, and CTA |
| `dashboard.blade.php` | Stats cards (habits, streak, points, penalties), today's habit checklist, weekly progress bar, motivational quote sidebar |
| `habits/index.blade.php` | Responsive grid of habit cards with priority badges, frequency labels, edit/delete actions |
| `habits/create.blade.php` | Habit creation form with all fields and validation messages |
| `habits/edit.blade.php` | Pre-filled edit form |
| `habits/show.blade.php` | Individual habit detail — stats, completion button, log history |
| `analytics/index.blade.php` | 7-day bar chart, success rate, streak display, completed/missed counters |
| `leaderboard/index.blade.php` | Ranked list with gold/silver/bronze medals, user's own rank card, info sidebar |
| `penalties/index.blade.php` | Penalty history cards — type, affected habit, date/time, points deducted |
| `profile/edit.blade.php` | Profile with three partials: info, password, account deletion |

### Auth Views

`auth/login.blade.php` · `auth/register.blade.php` · `auth/forgot-password.blade.php` · `auth/reset-password.blade.php` · `auth/verify-email.blade.php` · `auth/confirm-password.blade.php`

### Reusable Components (`resources/views/components/`)

| Component | Purpose |
|---|---|
| `primary-button` | Black filled CTA button |
| `secondary-button` | Outlined secondary action |
| `danger-button` | Red destructive action |
| `text-input` | Styled form input with focus ring |
| `input-label` | Accessible label |
| `input-error` | Validation error message |
| `dropdown` / `dropdown-link` | Alpine.js-powered dropdown menu |
| `modal` | Confirmation dialog (used in account deletion) |
| `nav-link` / `responsive-nav-link` | Desktop and mobile nav links |
| `auth-session-status` | Flash session status banner |

**Total: 22+ Blade templates and 12 reusable components.**

---

## iv. Models

All Eloquent models live in `app/Models/`.

### `User.php`
Represents an authenticated user.

**Fields:** `id`, `name`, `email`, `password`, `email_verified_at`, `remember_token`

**Relationships:**
- `hasMany(Habit::class)` — all habits owned by user
- `hasMany(Point::class)` — all point records
- `hasMany(Penalty::class)` — all penalty records
- `hasMany(HabitLog::class, through Habit)` — indirectly via habits

---

### `Habit.php`
Represents a trackable habit.

**Fields:** `id`, `user_id` (FK), `title`, `description`, `frequency` (daily/weekly), `target_count`, `priority` (high/medium/low), `start_date`, `end_date`

**Relationships:**
- `belongsTo(User::class)`
- `hasMany(HabitLog::class)`

---

### `HabitLog.php`
One record per check-in attempt (daily or weekly period).

**Fields:** `id`, `habit_id` (FK), `date`, `status` (completed / missed / skipped), `streak_count`

**Relationships:**
- `belongsTo(Habit::class)`
- `hasOne(Point::class)`
- `hasOne(Penalty::class)`

---

### `Point.php`
Tracks reward and penalty point transactions.

**Fields:** `id`, `user_id` (FK), `habit_log_id` (FK, nullable), `type` (reward / penalty), `points`

**Relationships:**
- `belongsTo(User::class)`
- `belongsTo(HabitLog::class)`

---

### `Penalty.php`
Logs accountability penalties when a habit is missed.

**Fields:** `id`, `user_id` (FK), `habit_log_id` (FK), `penalty_type` (points_deduction / streak_reset / warning), `penalty_value`, `reason`

**Relationships:**
- `belongsTo(User::class)`
- `belongsTo(HabitLog::class)`

---

### Entity Relationship Overview

```
User ──< Habit ──< HabitLog ──< Point
                        └────< Penalty
User ──< Point
User ──< Penalty
```

**5 models with full Eloquent relationships and migration-backed schema.**

---

## v. User Authentication

Authentication is implemented using **Laravel Breeze**, providing a complete, production-ready auth scaffolding.

### Registration (`/register`)
- Validates: `name`, `email` (unique), `password` (confirmed, Laravel default rules)
- Password hashed with `Hash::make()` (bcrypt)
- Fires `Registered` event (triggers email verification if enabled)
- Auto-logs the user in after registration and redirects to `/dashboard`

### Login (`/login`)
- Credential validation: email + password
- Uses Laravel's `Auth::attempt()` with session regeneration on success (CSRF session fixation protection)
- "Remember Me" checkbox creates a long-lived session cookie
- Redirects authenticated users away from login (guest middleware)

### Session Management
- Middleware stack: `auth` (requires login), `verified` (requires email verification), `guest` (redirects logged-in users)
- All protected routes behind `auth` middleware
- CSRF tokens on every form (`@csrf`)
- Session invalidated and token regenerated on logout

### Email Verification
- Signed, time-limited verification links sent to registered email
- `VerifyEmailController` validates the signature before marking email as verified
- Rate-limited to 6 resend attempts per minute
- Routes requiring `verified` middleware block access until email is confirmed

### Password Reset
- User submits email on `/forgot-password`; signed reset link emailed via `PasswordResetLinkController`
- `/reset-password/{token}` validates the token and updates the password via `NewPasswordController`
- Old password hashes invalidated after reset

### Profile Security
- In-profile password change requires current password verification
- Account deletion requires password re-confirmation via `ConfirmablePasswordController`
- Modal dialog prevents accidental account deletion

### Authorization
- `HabitPolicy` ensures users can only view, edit, or delete their own habits
- `Gate::authorize()` called in every HabitController method

---

## vi. Use of Media

### Icons (Font Awesome 6.5.1)
Font Awesome is loaded via CDN (`cdnjs.cloudflare.com`) and used extensively throughout the application:

| Icon Class | Usage Location |
|---|---|
| `fa-circle-dot` | Brand logo (navbar & landing) |
| `fa-fire` | Streak counter (dashboard, analytics) |
| `fa-star` | Points display |
| `fa-layer-group` | Habits nav link |
| `fa-chart-line` | Analytics / Progress nav link |
| `fa-trophy` | Leaderboard nav link |
| `fa-triangle-exclamation` | Penalties nav link & warnings |
| `fa-check` | Complete habit button |
| `fa-xmark` | Miss habit button |
| `fa-rotate-left` | Undo check-in button |
| `fa-calendar-day` | Dashboard habit section header |
| `fa-circle-user` | User profile nav dropdown |
| `fa-shield-heart` | App integrity / motivation sidebar |
| `fa-medal` | Leaderboard top-3 medals |
| `fa-pen-to-square` | Edit habit action |
| `fa-trash` | Delete habit action |
| `fa-plus` | Create habit CTA |
| `fa-arrow-left` | Back navigation |

### Typography
- **Figtree** font family (weights 400, 500, 600, 800) loaded via Bunny CDN (privacy-friendly Google Fonts proxy)
- Large display type (8xl / 6xl) for landing hero — creates strong visual hierarchy
- Tracking-tighter utility for condensed headline feel

### Interactive Elements (Alpine.js)
- **Mobile hamburger menu** — `x-data="{ open: false }"` toggled with `@click`, `x-show` with smooth transition
- **User dropdown menu** — Alpine-powered with click-away handler
- **Account deletion modal** — JavaScript-triggered confirmation dialog prevents accidental data loss
- **Hover effects** — all buttons and cards use Tailwind `hover:` utilities with `transition` for tactile feedback
- **Form validation** — Laravel server-side validation with inline Blade error rendering; no page reload needed for error display

### Visual Data Representation
- **7-day bar chart** (Analytics page) — built with pure HTML/CSS using `flex` column bars with percentage heights representing completion vs. miss counts per day; color-coded green (completed) and red (missed)
- **Weekly progress bar** (Dashboard) — horizontal bar showing % of current-week habits completed
- **Priority badges** — color-coded pill labels (red = High, yellow = Medium, green = Low priority) on habit cards
- **Status indicators** — color-coded habit log states: green background for completed, red for missed, grey for pending

### Motivational Content
- Dynamic rotating motivational quotes displayed on the dashboard sidebar (e.g., "The secret of getting ahead is getting started.")
- Contextual empty-state illustrations/messages guiding users when no habits exist yet

---

## vii. Design / Colour Scheme / Layout

### Design Philosophy
HabitForge uses a **modern minimalist** design language — high contrast black-and-white primary palette with strategically applied semantic accent colours. Inspired by productivity tools and fintech apps for a clean, focused interface.

### Colour Palette

| Role | Colour | Tailwind Class | Usage |
|---|---|---|---|
| Primary | `#000000` (Black) | `bg-black`, `text-black` | CTAs, logo, nav, emphasis text |
| Background | `#FDFDFC` (Off-white) | — | Page background |
| Surface | `#FFFFFF` (White) | `bg-white` | Cards, forms, modals |
| Success | `#22c55e` (Green) | `green-500` / `green-50` | Completed habits, streaks |
| Danger | `#ef4444` (Red) | `red-500` / `red-50` | Missed habits, penalties, errors |
| Warning | `#f97316` (Orange) | `orange-500` / `orange-50` | Streak fire, warnings |
| Accent | `#eab308` (Yellow) | `yellow-500` / `yellow-50` | Points, stars, premium |
| Info | `#3b82f6` (Blue) | `blue-500` / `blue-50` | Secondary actions, metrics |
| Muted | `#6b7280` (Gray-500) | `text-gray-500` | Subtitles, helper text |
| Border | `#f3f4f6` (Gray-100) | `border-gray-100` | Card borders, dividers |

### Typography Scale

| Size | Usage |
|---|---|
| `text-8xl` / `text-6xl` | Landing hero headline |
| `text-4xl` / `text-3xl` | Page section titles |
| `text-2xl` / `text-xl` | Card headers, stat values |
| `text-base` / `text-sm` | Body text, labels |
| `text-xs` (uppercase + tracking-widest) | Badges, metadata tags |

### Layout System

- **Max-width containers**: `max-w-7xl` centred with responsive horizontal padding
- **Responsive grid**: Single column on mobile → 2-col on `md:` → 3–4 col on `lg:` breakpoints
- **Dashboard layout**: 3-col stats row + 2-col main content split (habits list + sidebar)
- **Card design**: White background, `rounded-2xl` / `rounded-3xl` corners, `shadow-sm` elevation, `border border-gray-100`
- **Spacing**: Consistent 4/6/8/12/16 unit scale for padding and margins

### Key UI Patterns

- **Sticky navigation** — top bar stays visible while scrolling
- **Status-colored habit rows** — green/red/white backgrounds on dashboard check-in rows
- **Medal cards** — leaderboard top 3 use gold/silver/bronze color-coded bordered cards
- **Priority pills** — `rounded-full` badges with semantic colour per priority level
- **Gradient-free** — flat colours with subtle shadows for a clean, modern aesthetic
- **Focus rings** — visible keyboard focus states on all interactive elements (accessibility)
- **Mobile-first** — all layouts stack vertically below `md:` breakpoint; tap targets ≥ 44px

---

## viii. Navigations / Links

### Primary Navigation Bar (`layouts/navigation.blade.php`)

Sticky top navigation visible on all authenticated pages:

```
[● HabitForge]   [Dashboard] [Habits] [Progress] [Penalties] [Leaderboard]   [User ▾]
```

| Link | Route | Icon |
|---|---|---|
| HabitForge (logo) | `/dashboard` | `fa-circle-dot` |
| Dashboard | `/dashboard` | `fa-house` |
| Habits | `/habits` | `fa-layer-group` |
| Progress | `/analytics` | `fa-chart-line` |
| Penalties | `/penalties` | `fa-triangle-exclamation` |
| Leaderboard | `/leaderboard` | `fa-trophy` |

**User Dropdown (top right):**
- Displays authenticated user's name and total points
- Profile → `/profile`
- Log Out → POST `/logout`

### Mobile Navigation (responsive hamburger)
- Hamburger icon (`☰`) toggles slide-down menu via Alpine.js
- Contains all primary links + user name/email + Profile + Logout
- Fully accessible with keyboard support

### In-Page Navigation & Links

**Dashboard:**
- "Add New Habit" button → `/habits/create`
- Empty-state "Create your first habit" link → `/habits/create`
- Each today's-habit row → habit show page

**Habits Index (`/habits`):**
- "Create Habit" button → `/habits/create`
- Edit icon on each card → `/habits/{id}/edit`
- Delete icon → DELETE `/habits/{id}` (with confirmation)
- Habit title → `/habits/{id}` (detail view)

**Habit Show (`/habits/{id}`):**
- "← Back to Habits" → `/habits`
- "Edit Habit" → `/habits/{id}/edit`
- Complete / Miss buttons → POST `/habits/{id}/check-in`
- "Delete Habit" → DELETE `/habits/{id}`

**Leaderboard sidebar:**
- "Keep Going" button → `/dashboard`

**Auth Page Links:**
- Login page: "Forgot your password?" → `/forgot-password`, "Register" → `/register`
- Register page: "Already registered?" → `/login`
- Forgot password: "Back to login" → `/login`

### User Journey / Navigation Flow

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

## Installation & Setup

```bash
# 1. Clone the repository
git clone https://github.com/mitbol22/smartHabit.git
cd smartHabit

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies and build assets
npm install && npm run build

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Set up database in .env (MySQL via XAMPP)
# DB_DATABASE=smarthabit
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Start development server
php artisan serve
```

Visit `http://localhost:8000` or configure XAMPP virtual host at `http://localhost/smartHabit/public`.

---

## Summary

| Component | Implementation |
|---|---|
| Routes | 26 routes (CRUD, gamification, auth, analytics, social) |
| Controllers | 16 controllers (7 app + 9 auth) |
| Views | 22+ Blade templates + 12 reusable components |
| Models | 5 Eloquent models with full relationships |
| Authentication | Laravel Breeze — register, login, email verify, password reset |
| Media | Font Awesome icons, Figtree typography, interactive Alpine.js, CSS bar charts |
| Design | Minimalist black/white + semantic accent palette, responsive Tailwind grid |
| Navigation | Sticky nav, mobile hamburger, dropdowns, in-page contextual links |

---