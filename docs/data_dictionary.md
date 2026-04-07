# UniShare — Data Dictionary

**Project:** UniShare  
**Database:** MySQL  
**Framework:** Laravel 11  
**Total Tables:** 11  

---

## Table of Contents

1. [users](#1-users)
2. [items](#2-items)
3. [item_photos](#3-item_photos)
4. [borrow_requests](#4-borrow_requests)
5. [penalties](#5-penalties)
6. [point_transactions](#6-point_transactions)
7. [reviews](#7-reviews)
8. [ratings](#8-ratings)
9. [conversations](#9-conversations)
10. [messages](#10-messages)
11. [user_item_likes](#11-user_item_likes)

---

## 1. `users`

Stores all registered users of the UniShare system, including students and administrators.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each user | 1 |
| 2 | name | VARCHAR(255) | NOT NULL | — | Full name of the user | Ahmad Hariz |
| 3 | email | VARCHAR(255) | NOT NULL, UNIQUE | — | Email address used for login | hariz@student.um.edu.my |
| 4 | phone_number | VARCHAR(20) | NULLABLE, UNIQUE | NULL | Phone number as alternative login credential | 0123456789 |
| 5 | email_verified_at | TIMESTAMP | NULLABLE | NULL | Timestamp when the email was verified | 2026-01-15 09:00:00 |
| 6 | password | VARCHAR(255) | NOT NULL | — | Bcrypt-hashed account password | $2y$12$abc... |
| 7 | points_balance | INT UNSIGNED | NOT NULL | 100 | Current point wallet balance; new users start with 100 | 350 |
| 8 | is_admin | TINYINT(1) | NOT NULL | 0 | Whether the user has administrator privileges (1 = yes) | 0 |
| 9 | is_suspended | TINYINT(1) | NOT NULL | 0 | Whether the account is currently suspended (1 = yes) | 0 |
| 10 | suspended_at | TIMESTAMP | NULLABLE | NULL | Timestamp of when the account was suspended | 2026-03-10 10:00:00 |
| 11 | suspension_reason | VARCHAR(255) | NULLABLE | NULL | Reason provided by admin for the suspension | Repeated late returns |
| 12 | bio | TEXT | NULLABLE | NULL | Short personal biography shown on user profile | CS student, happy to share! |
| 13 | avatar | VARCHAR(255) | NULLABLE | NULL | File path to the user's uploaded profile picture | avatars/hariz.jpg |
| 14 | location | VARCHAR(255) | NULLABLE | NULL | User's campus location | Kolej Kediaman 5 |
| 15 | remember_token | VARCHAR(100) | NULLABLE | NULL | Token for persistent "remember me" login sessions | xA3kZ9... |
| 16 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-01-10 08:00:00 |
| 17 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-03-20 11:45:00 |

---

## 2. `items`

Stores all items posted by users for lending within the UniShare community.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each item | 5 |
| 2 | owner_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the user who posted this item | 1 |
| 3 | title | VARCHAR(255) | NOT NULL | — | Name or title of the item | Canon EOS Rebel T7 Camera |
| 4 | category | VARCHAR(255) | NULLABLE | NULL | Category the item belongs to | Electronics |
| 5 | description | TEXT | NULLABLE | NULL | Detailed description of the item | Great camera with lens and bag |
| 6 | condition | VARCHAR(50) | NULLABLE | NULL | Physical condition of the item | Good |
| 7 | points_per_day | INT UNSIGNED | NOT NULL | 0 | Points charged to the borrower per day | 15 |
| 8 | max_days | INT UNSIGNED | NOT NULL | 7 | Maximum number of days item can be borrowed | 14 |
| 9 | is_active | TINYINT(1) | NOT NULL | 1 | Whether the item is currently listed and available (1 = yes) | 1 |
| 10 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-01-20 10:00:00 |
| 11 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-02-05 14:00:00 |

---

## 3. `item_photos`

Stores uploaded photos for each item listing. Each item can have up to 5 photos.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each photo record | 12 |
| 2 | item_id | BIGINT UNSIGNED | NOT NULL, FK → items.id, CASCADE | — | ID of the item this photo belongs to | 5 |
| 3 | photo_path | VARCHAR(255) | NOT NULL | — | Relative file path to the image in public storage | item_photos/camera_front.jpg |
| 4 | is_primary | TINYINT(1) | NOT NULL | 0 | Whether this is the main display photo (1 = yes) | 1 |
| 5 | order | INT | NOT NULL | 0 | Display order in the item photo gallery (0 = first) | 0 |
| 6 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-01-20 10:05:00 |
| 7 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-01-20 10:05:00 |

---

## 4. `borrow_requests`

Records all borrow transactions between borrower and lender, tracking the full lifecycle from request to return.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each borrow request | 23 |
| 2 | item_id | BIGINT UNSIGNED | NOT NULL, FK → items.id, CASCADE | — | ID of the item being requested | 5 |
| 3 | borrower_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the user borrowing the item | 2 |
| 4 | lender_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the item owner lending it out | 1 |
| 5 | start_date | DATE | NOT NULL | — | The date the borrowing period begins | 2026-03-01 |
| 6 | end_date | DATE | NOT NULL | — | The date the item must be returned | 2026-03-05 |
| 7 | points_per_day | INT UNSIGNED | NOT NULL | — | Daily point rate locked in at approval time | 15 |
| 8 | total_points | INT UNSIGNED | NULLABLE | NULL | Total points charged (days × rate), set upon approval | 75 |
| 9 | penalty_points | INT | NOT NULL | 0 | Total penalty points accumulated on this request | 10 |
| 10 | overdue_days | INT | NOT NULL | 0 | Number of days the item was returned past the due date | 2 |
| 11 | damage_description | TEXT | NULLABLE | NULL | Description of damage reported by the lender | Screen cracked on bottom left |
| 12 | status | VARCHAR(255) | NOT NULL | pending | Current lifecycle status of the request | returned |
| 13 | note | TEXT | NULLABLE | NULL | Optional message from borrower on submission | Need for assignment |
| 14 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-02-25 09:00:00 |
| 15 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-03-06 08:30:00 |

**Status Values:** `pending` · `approved` · `rejected` · `borrowed` · `returned` · `cancelled` · `overdue` · `missing`

---

## 5. `penalties`

Tracks penalty records issued to borrowers for late returns, item damage, or missing items. Damage and missing types require admin approval before points are deducted.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each penalty record | 7 |
| 2 | borrow_request_id | BIGINT UNSIGNED | NOT NULL, FK → borrow_requests.id, CASCADE | — | ID of the borrow request that triggered this penalty | 23 |
| 3 | borrower_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the user who incurred the penalty | 2 |
| 4 | reported_by | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the user (lender) who filed the penalty report | 1 |
| 5 | type | VARCHAR(255) | NOT NULL | — | Type of penalty issued | late_return |
| 6 | penalty_points | INT | NOT NULL | 0 | Number of points to be deducted from the borrower | 10 |
| 7 | reason | TEXT | NULLABLE | NULL | Description or explanation of the penalty | Returned 2 days late |
| 8 | evidence_photo | VARCHAR(255) | NULLABLE | NULL | File path to proof photo (required for damaged/missing) | penalty-evidence/photo.jpg |
| 9 | status | VARCHAR(255) | NOT NULL | active | Current approval state of the penalty | active |
| 10 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-03-07 10:00:00 |
| 11 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-03-08 09:15:00 |

**Type Values:** `late_return` (auto, 5 pts/day) · `damaged` (admin approval, 50 pts) · `missing` (admin approval, based on borrow total)  
**Status Values:** `pending` · `active` · `rejected`

---

## 6. `point_transactions`

A complete audit log of every point movement for every user, ensuring full traceability of the points economy.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each transaction record | 41 |
| 2 | user_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the user whose points changed | 2 |
| 3 | borrow_request_id | BIGINT UNSIGNED | NULLABLE, FK → borrow_requests.id, SET NULL | NULL | ID of the related borrow request, if applicable | 23 |
| 4 | type | VARCHAR(20) | NOT NULL | — | Category of the point transaction | borrow_spend |
| 5 | amount | INT | NOT NULL | — | Points changed; positive = credit, negative = debit | -75 |
| 6 | description | VARCHAR(255) | NULLABLE | NULL | Human-readable explanation of the transaction | Borrow item ID 5 |
| 7 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-03-01 09:00:00 |
| 8 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-03-01 09:00:00 |

**Type Values:** `borrow_spend` · `lend_earn` · `borrow_refund` · `lend_reversal` · `penalty_late` · `penalty_damaged` · `penalty_missing` · `damage_compensation` · `missing_compensation` · `adjustment`

---

## 7. `reviews`

Stores written reviews and star ratings left by borrowers on items after a completed transaction.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each review | 9 |
| 2 | reviewer_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the user who wrote the review | 2 |
| 3 | item_id | BIGINT UNSIGNED | NOT NULL, FK → items.id, CASCADE | — | ID of the item being reviewed | 5 |
| 4 | borrow_request_id | BIGINT UNSIGNED | NOT NULL, FK → borrow_requests.id, CASCADE | — | ID of the borrow transaction this review is for | 23 |
| 5 | rating | TINYINT UNSIGNED | NOT NULL | — | Star rating from 1 (Poor) to 5 (Excellent) | 5 |
| 6 | comment | TEXT | NULLABLE | NULL | Optional written review comment from the borrower | Great camera, perfect condition! |
| 7 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-03-07 12:00:00 |
| 8 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-03-07 12:00:00 |

**Unique Constraint:** `(reviewer_id, borrow_request_id)` — each user can only leave one review per borrow transaction.

---

## 8. `ratings`

Stores user-to-user trust ratings submitted after a borrowing transaction, used to calculate each user's overall reputation score.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each rating entry | 15 |
| 2 | rater_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the user submitting the rating | 2 |
| 3 | rated_user_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the user being rated | 1 |
| 4 | borrow_request_id | BIGINT UNSIGNED | NOT NULL, FK → borrow_requests.id, CASCADE | — | ID of the borrow transaction linked to this rating | 23 |
| 5 | rating | TINYINT UNSIGNED | NOT NULL | — | Numeric score from 1 (poor) to 5 (excellent) | 4 |
| 6 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-03-07 13:00:00 |
| 7 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-03-07 13:00:00 |

**Unique Constraint:** `(rater_id, borrow_request_id)` — each user can only rate once per borrow transaction.

---

## 9. `conversations`

Represents a unique private messaging thread between exactly two users.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each conversation thread | 6 |
| 2 | user_one_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the first participant in the conversation | 1 |
| 3 | user_two_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the second participant in the conversation | 2 |
| 4 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-02-20 14:00:00 |
| 5 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-03-06 11:00:00 |

**Unique Constraint:** `(user_one_id, user_two_id)` — only one conversation thread can exist between two users.

---

## 10. `messages`

Stores individual messages sent within a conversation thread.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each message | 88 |
| 2 | conversation_id | BIGINT UNSIGNED | NOT NULL, FK → conversations.id, CASCADE | — | ID of the conversation this message belongs to | 6 |
| 3 | sender_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the user who sent the message | 2 |
| 4 | message | TEXT | NOT NULL | — | The text content of the message | Hi! I'd like to borrow your camera. |
| 5 | read_at | TIMESTAMP | NULLABLE | NULL | Timestamp when the recipient read the message; NULL = unread | 2026-02-20 16:10:00 |
| 6 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-02-20 16:05:00 |
| 7 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-02-20 16:05:00 |

---

## 11. `user_item_likes`

Pivot table implementing the many-to-many favouriting relationship between users and items.

| No | Field Name | Data Type | Constraints | Default | Description | Example |
|---|---|---|---|---|---|---|
| 1 | id | BIGINT UNSIGNED | PRIMARY KEY, AUTO INCREMENT | — | Unique identifier for each like record | 34 |
| 2 | user_id | BIGINT UNSIGNED | NOT NULL, FK → users.id, CASCADE | — | ID of the user who liked the item | 2 |
| 3 | item_id | BIGINT UNSIGNED | NOT NULL, FK → items.id, CASCADE | — | ID of the item that was liked or favourited | 5 |
| 4 | created_at | TIMESTAMP | NULLABLE | NULL | Record creation timestamp | 2026-02-18 09:00:00 |
| 5 | updated_at | TIMESTAMP | NULLABLE | NULL | Record last updated timestamp | 2026-02-18 09:00:00 |

**Unique Constraint:** `(user_id, item_id)` — a user can only like each item once.
