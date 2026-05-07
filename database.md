# 📊 Smart Habit Builder with Penalty System (SHBPS)

## Database Design Documentation

---

## 🧠 Overview

The database is designed to support a **behavioral enforcement system** that tracks user habits, evaluates daily performance, and applies **rewards or penalties** accordingly.

This structure enables:

* Habit tracking
* Streak calculation
* Penalty enforcement
* Reward system (points)
* Behavioral analytics

---

## 🗂️ Entity Relationship Diagram (ERD - Summary)

```
users (1) ───────< habits (1) ───────< habit_logs
   │                    │
   │                    └──────< penalties
   │
   └──────────────< points
```

---

## 👤 1. USERS TABLE

Stores user account information.

| Field      | Type      | Description       |
| ---------- | --------- | ----------------- |
| id         | BIGINT PK | User ID           |
| name       | VARCHAR   | User name         |
| email      | VARCHAR   | User email        |
| password   | VARCHAR   | Hashed password   |
| created_at | TIMESTAMP | Created timestamp |
| updated_at | TIMESTAMP | Updated timestamp |

---

## 🎯 2. HABITS TABLE

Stores habits created by users.

| Field        | Type      | Description                        |
| ------------ | --------- | ---------------------------------- |
| id           | BIGINT PK | Habit ID                           |
| user_id      | BIGINT FK | References users.id                |
| title        | VARCHAR   | Habit title                        |
| description  | TEXT      | Habit description                  |
| frequency    | ENUM      | daily / weekly                     |
| target_count | INT       | Target value (e.g. hours or count) |
| start_date   | DATE      | Start date                         |
| end_date     | DATE NULL | Optional end date                  |
| created_at   | TIMESTAMP | Created timestamp                  |
| updated_at   | TIMESTAMP | Updated timestamp                  |

---

## 📅 3. HABIT_LOGS TABLE

Tracks daily habit performance.

| Field        | Type      | Description                         |
| ------------ | --------- | ----------------------------------- |
| id           | BIGINT PK | Log ID                              |
| habit_id     | BIGINT FK | References habits.id                |
| date         | DATE      | Log date                            |
| status       | ENUM      | completed / missed / skipped        |
| value        | INT NULL  | Optional value (e.g. hours studied) |
| streak_count | INT       | Current streak at that day          |
| created_at   | TIMESTAMP | Created timestamp                   |
| updated_at   | TIMESTAMP | Updated timestamp                   |

---

## ⚖️ 4. PENALTIES TABLE

Stores penalties applied when habits are missed.

| Field         | Type      | Description                     |
| ------------- | --------- | ------------------------------- |
| id            | BIGINT PK | Penalty ID                      |
| user_id       | BIGINT FK | References users.id             |
| habit_log_id  | BIGINT FK | References habit_logs.id        |
| penalty_type  | ENUM      | points_deduction / streak_reset |
| penalty_value | INT       | Penalty value (e.g. -10 points) |
| reason        | TEXT      | Reason for penalty              |
| created_at    | TIMESTAMP | Created timestamp               |

---

## 🪙 5. POINTS TABLE

Tracks reward and penalty points.

| Field        | Type      | Description                         |
| ------------ | --------- | ----------------------------------- |
| id           | BIGINT PK | Point record ID                     |
| user_id      | BIGINT FK | References users.id                 |
| habit_log_id | BIGINT FK | References habit_logs.id (nullable) |
| type         | ENUM      | reward / penalty                    |
| points       | INT       | Points value (+ or -)               |
| description  | TEXT      | Description of transaction          |
| created_at   | TIMESTAMP | Created timestamp                   |

---

## 🔗 Relationships

* A **User** has many **Habits**
* A **Habit** belongs to a **User**
* A **Habit** has many **Habit Logs**
* A **Habit Log** belongs to a **Habit**
* A **Habit Log** may have one **Penalty**
* A **User** has many **Points**
* A **User** has many **Penalties**

---

## 🔁 System Behavior Logic

### ✅ When Habit is Completed

* Insert record into `habit_logs` (status = completed)
* Increase streak
* Insert reward into `points` (+points)

### ❌ When Habit is Missed

* Insert record into `habit_logs` (status = missed)
* Reset streak
* Insert record into `penalties`
* Insert penalty into `points` (-points)

---

## 🧮 Streak Calculation

Streak is determined based on consecutive `completed` entries in `habit_logs`.

Example:

```
Day 1 → completed → streak = 1
Day 2 → completed → streak = 2
Day 3 → missed    → streak = 0
```

---

## 🚀 Optional Extensions

### 🏆 BADGES TABLE

Tracks achievements.

| Field           | Type      |
| --------------- | --------- |
| id              | BIGINT PK |
| name            | VARCHAR   |
| description     | TEXT      |
| points_required | INT       |

### 🏅 USER_BADGES TABLE

| Field      | Type      |
| ---------- | --------- |
| user_id    | BIGINT FK |
| badge_id   | BIGINT FK |
| awarded_at | TIMESTAMP |

---

## 📌 Conclusion

This database design supports a **behavior-driven system** that enforces habit consistency through structured logging, rewards, and penalties. The relational structure ensures scalability, traceability, and analytical capability.

---
