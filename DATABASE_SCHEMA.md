# Database Schema - PHP Tinder app 20250929

**Developer:** Khairul Adha  
**Email:** r4dioz.88@gmail.com  
**GitHub:** r4diorusak  

This document describes the complete relational database schema for the PHP Tinder dating application.

---

## Overview

The application uses **MySQL** as the database management system with **3 main tables**:
- `people` - User profiles
- `likes` - Like relationships
- `dislikes` - Dislike relationships

---

## Entity Relationship Diagram (ERD)

### ERD - Detailed View

```
╔════════════════════════════════════════════════════════════════════╗
║                            PEOPLE                                  ║
╠════════════════════════════════════════════════════════════════════╣
║  PK  │ id            │ BIGINT UNSIGNED    │ AUTO_INCREMENT        ║
║      │ name          │ VARCHAR(255)       │ NOT NULL              ║
║      │ age           │ INTEGER            │ NOT NULL              ║
║      │ pictures      │ JSON               │ NOT NULL              ║
║      │ location      │ VARCHAR(255)       │ NOT NULL              ║
║      │ bio           │ TEXT               │ NULLABLE              ║
║      │ gender        │ ENUM               │ 'male','female','other'║
║      │ created_at    │ TIMESTAMP          │ NULLABLE              ║
║      │ updated_at    │ TIMESTAMP          │ NULLABLE              ║
╚════════════════════════════════════════════════════════════════════╝
           │                                    │
           │ 1                                1 │
           │                                    │
           │                                    │
       ┌───┴────┐                          ┌───┴────┐
       │        │                          │        │
       │ *      │ *                      * │        │ *
       ▼        ▼                          ▼        ▼
╔═══════════════════════╗            ╔═══════════════════════╗
║        LIKES          ║            ║      DISLIKES         ║
╠═══════════════════════╣            ╠═══════════════════════╣
║ PK │ id               ║            ║ PK │ id               ║
║ FK │ liker_id    ────╫────┐       ║ FK │ disliker_id ────╫────┐
║ FK │ liked_id    ────╫──┐ │       ║ FK │ disliked_id ────╫──┐ │
║    │ created_at       ║  │ │       ║    │ created_at       ║  │ │
║    │ updated_at       ║  │ │       ║    │ updated_at       ║  │ │
╠═══════════════════════╣  │ │       ╠═══════════════════════╣  │ │
║ UNIQUE KEY:           ║  │ │       ║ UNIQUE KEY:           ║  │ │
║ (liker_id, liked_id)  ║  │ │       ║ (disliker_id,         ║  │ │
║                       ║  │ │       ║  disliked_id)         ║  │ │
╚═══════════════════════╝  │ │       ╚═══════════════════════╝  │ │
           │               │ │                  │               │ │
           └───────────────┘ │                  └───────────────┘ │
                             │                                    │
                             └────────────────────────────────────┘
                     (All FK references point back to people.id)
```

### ERD - Simplified Crow's Foot Notation

```
                    ┌─────────────────┐
                    │     PEOPLE      │
                    ├─────────────────┤
                    │ PK: id          │
                    │     name        │
                    │     age         │
                    │     pictures    │
                    │     location    │
                    │     bio         │
                    │     gender      │
                    └─────────────────┘
                       │         │
                       │         │
           ┌───────────┤         ├───────────┐
           │           │         │           │
      liker_id     liked_id  disliker_id disliked_id
           │           │         │           │
        ┌──┴──┐     ┌──┴──┐  ┌──┴──┐     ┌──┴──┐
        │     │     │     │  │     │     │     │
    ╔═══▼═════▼═════╗  ╔═══▼═════▼═════╗
    ║     LIKES      ║  ║   DISLIKES    ║
    ╠════════════════╣  ╠════════════════╣
    ║ PK: id         ║  ║ PK: id         ║
    ║ FK: liker_id   ║  ║ FK: disliker_id║
    ║ FK: liked_id   ║  ║ FK: disliked_id║
    ║     created_at ║  ║     created_at ║
    ║     updated_at ║  ║     updated_at ║
    ╚════════════════╝  ╚════════════════╝
         (1:N)               (1:N)
     One person can       One person can
     like many people     dislike many people
```

### ERD - Relationship Cardinality

```
    PEOPLE (1) ──────< LIKES >────── (1) PEOPLE
       │                                   │
       │ One person can like many         │
       │ One person can be liked by many  │
       │                                   │
       └──< (liker_id)    (liked_id) >────┘
       
    
    PEOPLE (1) ──────< DISLIKES >────── (1) PEOPLE
       │                                     │
       │ One person can dislike many        │
       │ One person can be disliked by many │
       │                                     │
       └──< (disliker_id)  (disliked_id) >──┘
```

### ERD - Match Detection Flow

```
┌─────────────┐                           ┌─────────────┐
│  Person A   │                           │  Person B   │
│   (ID: 1)   │                           │   (ID: 2)   │
└──────┬──────┘                           └──────┬──────┘
       │                                         │
       │ 1. Person A likes Person B              │
       │    (liker_id=1, liked_id=2)            │
       ├────────────────────────────────────────►│
       │                                         │
       │                                         │
       │ 2. Person B likes Person A              │
       │    (liker_id=2, liked_id=1)            │
       │◄────────────────────────────────────────┤
       │                                         │
       │         3. MATCH DETECTED! 🎉           │
       │         Both records exist in           │
       │         LIKES table                     │
       │                                         │
       │◄────────────────────────────────────────►│
              Mutual Like = Match
       
       
       LIKES TABLE:
       ┌────┬──────────┬──────────┐
       │ id │ liker_id │ liked_id │
       ├────┼──────────┼──────────┤
       │  1 │    1     │    2     │ ← Person A → Person B
       │  2 │    2     │    1     │ ← Person B → Person A
       └────┴──────────┴──────────┘
                 ↑
              MATCH!
```

### ERD - Complete Data Flow Diagram

```
┌────────────────────────────────────────────────────────────────┐
│                    USER INTERACTION FLOW                       │
└────────────────────────────────────────────────────────────────┘

    ┌──────────┐
    │  User A  │
    │ (ID: 1)  │
    └─────┬────┘
          │
          ├─── Views Person B's Profile
          │
          ▼
    ┌─────────────┐
    │   Decision  │
    └─────┬───┬───┘
          │   │
    LIKE  │   │  DISLIKE
          │   │
          ▼   ▼
    ┌─────┴───┴─────┐
    │               │
┌───▼────┐    ┌────▼────┐
│ LIKES  │    │DISLIKES │
│ TABLE  │    │ TABLE   │
└───┬────┘    └────┬────┘
    │              │
    │              │
    ▼              ▼
┌───────────┐  ┌────────────┐
│ Check for │  │  Remove    │
│ Mutual    │  │  from      │
│ Like      │  │  Likes     │
│           │  │  (if any)  │
└─────┬─────┘  └────────────┘
      │
      ├─── YES ──► ┌──────────────┐
      │            │ MATCH! 🎉    │
      │            │ Notify User  │
      │            └──────────────┘
      │
      └─── NO ──► ┌──────────────┐
                   │ Like Saved   │
                   │ Wait for     │
                   │ Response     │
                   └──────────────┘


┌────────────────────────────────────────────────────────────────┐
│                  DATABASE INTERACTION FLOW                     │
└────────────────────────────────────────────────────────────────┘

    API Request
        │
        ▼
┌───────────────┐
│ PeopleController
│    like()     │
└───────┬───────┘
        │
        ├──► 1. Check if already liked
        │         └─► Query: likes WHERE liker_id=A AND liked_id=B
        │
        ├──► 2. Remove dislike (if any)
        │         └─► Delete: dislikes WHERE disliker_id=A AND disliked_id=B
        │
        ├──► 3. Insert like record
        │         └─► Insert: likes (liker_id=A, liked_id=B)
        │
        ├──► 4. Check mutual like
        │         └─► Query: likes WHERE liker_id=B AND liked_id=A
        │
        └──► 5. Return response
                  └─► JSON: {is_match: true/false}
```

### ERD - Database Schema with Sample Data Visualization

```
┌─────────────────────────────────────────────────────────────────┐
│                        PEOPLE TABLE                             │
├────┬─────────────┬─────┬──────────────┬──────────────┬─────────┤
│ ID │    Name     │ Age │   Location   │    Gender    │   Bio   │
├────┼─────────────┼─────┼──────────────┼──────────────┼─────────┤
│ 1  │ Sarah J.    │ 25  │ New York     │ female       │ Hiking  │
│ 2  │ Mike A.     │ 28  │ LA           │ male         │ Sports  │
│ 3  │ Emma W.     │ 23  │ Chicago      │ female       │ Artist  │
│ 4  │ John D.     │ 30  │ Miami        │ male         │ Travel  │
│ 5  │ Lisa M.     │ 27  │ Seattle      │ female       │ Books   │
└────┴─────────────┴─────┴──────────────┴──────────────┴─────────┘
     │                              │
     │                              │
     ├──────────────┬───────────────┼──────────────┐
     │              │               │              │
     ▼              ▼               ▼              ▼
┌──────────────────────────┐   ┌──────────────────────────┐
│      LIKES TABLE         │   │    DISLIKES TABLE        │
├────┬──────────┬──────────┤   ├────┬──────────┬──────────┤
│ ID │ Liker_ID │ Liked_ID │   │ ID │Disliker_ID│Disliked_ID│
├────┼──────────┼──────────┤   ├────┼──────────┼──────────┤
│ 1  │    1     │    2     │   │ 1  │    1     │    4     │
│ 2  │    2     │    1     │   │ 2  │    2     │    5     │
│ 3  │    1     │    3     │   │ 3  │    3     │    4     │
│ 4  │    3     │    2     │   │ 4  │    4     │    1     │
│ 5  │    2     │    5     │   └────┴──────────┴──────────┘
└────┴──────────┴──────────┘
      │      │       │
      │      │       └──────► Person 1 likes Person 3
      │      │
      │      └──────────────► Person 2 likes Person 1
      │                       Person 1 likes Person 2
      └─────────────────────► MATCH! (Rows 1 & 2)


MATCH DETECTION:
Person 1 ←→ Person 2  = MATCH ✓
Person 1 →  Person 3  = NO MATCH (one-way)
Person 2 →  Person 5  = NO MATCH (one-way)
Person 3 →  Person 2  = NO MATCH (one-way)
```

---

## Table: `people`

Stores user profiles and personal information.

### Schema Definition

| Column      | Type                        | Null | Key | Default | Extra          | Description                    |
|-------------|-----------------------------| ---- |-----|---------|----------------|--------------------------------|
| id          | BIGINT UNSIGNED             | NO   | PK  | -       | AUTO_INCREMENT | Primary key                    |
| name        | VARCHAR(255)                | NO   |     | -       |                | Full name of person            |
| age         | INTEGER                     | NO   |     | -       |                | Age in years                   |
| pictures    | JSON                        | NO   |     | -       |                | Array of image URLs            |
| location    | VARCHAR(255)                | NO   |     | -       |                | City, Country                  |
| bio         | TEXT                        | YES  |     | NULL    |                | Biography/description          |
| gender      | ENUM('male','female','other')| NO   |     | -       |                | Gender identification          |
| created_at  | TIMESTAMP                   | YES  |     | NULL    |                | Record creation timestamp      |
| updated_at  | TIMESTAMP                   | YES  |     | NULL    |                | Last update timestamp          |

### Indexes

- **PRIMARY KEY:** `id`
- **INDEX:** `created_at` (for ordering)

### Example Data

```sql
INSERT INTO people (name, age, pictures, location, bio, gender, created_at, updated_at) 
VALUES (
    'Sarah Johnson',
    25,
    '["https://i.pravatar.cc/300?img=1", "https://i.pravatar.cc/300?img=2"]',
    'New York, USA',
    'Love hiking, coffee, and adventure!',
    'female',
    NOW(),
    NOW()
);
```

### Sample Records

```
+----+------------------+-----+--------------------------------------------+------------------+--------------------------------+--------+---------------------+---------------------+
| id | name             | age | pictures                                   | location         | bio                            | gender | created_at          | updated_at          |
+----+------------------+-----+--------------------------------------------+------------------+--------------------------------+--------+---------------------+---------------------+
|  1 | Sarah Johnson    |  25 | ["https://i.pravatar.cc/300?img=1"]        | New York, USA    | Love hiking, coffee, adventure!| female | 2024-01-01 00:00:00 | 2024-01-01 00:00:00 |
|  2 | Mike Anderson    |  28 | ["https://i.pravatar.cc/300?img=2"]        | Los Angeles, USA | Sports enthusiast and foodie   | male   | 2024-01-01 00:00:00 | 2024-01-01 00:00:00 |
|  3 | Emma Wilson      |  23 | ["https://i.pravatar.cc/300?img=3"]        | Chicago, USA     | Artist and coffee addict       | female | 2024-01-01 00:00:00 | 2024-01-01 00:00:00 |
+----+------------------+-----+--------------------------------------------+------------------+--------------------------------+--------+---------------------+---------------------+
```

---

## Table: `likes`

Stores like relationships between people. Represents when one person likes another.

### Schema Definition

| Column      | Type            | Null | Key    | Default | Extra          | Description                           |
|-------------|-----------------|------|--------|---------|----------------|---------------------------------------|
| id          | BIGINT UNSIGNED | NO   | PK     | -       | AUTO_INCREMENT | Primary key                           |
| liker_id    | BIGINT UNSIGNED | NO   | FK,IDX | -       |                | Person who liked (foreign key)        |
| liked_id    | BIGINT UNSIGNED | NO   | FK,IDX | -       |                | Person who was liked (foreign key)    |
| created_at  | TIMESTAMP       | YES  |        | NULL    |                | When the like occurred                |
| updated_at  | TIMESTAMP       | YES  |        | NULL    |                | Last update timestamp                 |

### Constraints

- **PRIMARY KEY:** `id`
- **FOREIGN KEY:** `liker_id` REFERENCES `people(id)` ON DELETE CASCADE
- **FOREIGN KEY:** `liked_id` REFERENCES `people(id)` ON DELETE CASCADE
- **UNIQUE KEY:** `(liker_id, liked_id)` - Prevents duplicate likes
- **INDEX:** `liker_id` - For querying who someone liked
- **INDEX:** `liked_id` - For querying who liked someone

### Business Rules

1. A person cannot like themselves (`liker_id ≠ liked_id`)
2. Each like is unique (UNIQUE constraint on liker_id + liked_id)
3. If both Person A likes Person B AND Person B likes Person A, it's a **MATCH**
4. When a person is deleted, all their likes are deleted (CASCADE)

### Example Data

```sql
-- Person 1 likes Person 2
INSERT INTO likes (liker_id, liked_id, created_at, updated_at) 
VALUES (1, 2, NOW(), NOW());

-- Person 2 likes Person 1 (creates a MATCH)
INSERT INTO likes (liker_id, liked_id, created_at, updated_at) 
VALUES (2, 1, NOW(), NOW());

-- Person 1 likes Person 3
INSERT INTO likes (liker_id, liked_id, created_at, updated_at) 
VALUES (1, 3, NOW(), NOW());
```

### Sample Records

```
+----+----------+----------+---------------------+---------------------+
| id | liker_id | liked_id | created_at          | updated_at          |
+----+----------+----------+---------------------+---------------------+
|  1 |        1 |        2 | 2024-01-01 10:00:00 | 2024-01-01 10:00:00 |
|  2 |        2 |        1 | 2024-01-01 10:05:00 | 2024-01-01 10:05:00 | ← MATCH!
|  3 |        1 |        3 | 2024-01-01 10:10:00 | 2024-01-01 10:10:00 |
|  4 |        3 |        2 | 2024-01-01 10:15:00 | 2024-01-01 10:15:00 |
+----+----------+----------+---------------------+---------------------+
```

### Query Examples

**Get all people that User 1 liked:**
```sql
SELECT p.* 
FROM people p
JOIN likes l ON p.id = l.liked_id
WHERE l.liker_id = 1;
```

**Get all people who liked User 1:**
```sql
SELECT p.* 
FROM people p
JOIN likes l ON p.id = l.liker_id
WHERE l.liked_id = 1;
```

**Find matches for User 1 (mutual likes):**
```sql
SELECT p.* 
FROM people p
JOIN likes l1 ON p.id = l1.liked_id
JOIN likes l2 ON p.id = l2.liker_id
WHERE l1.liker_id = 1 
  AND l2.liked_id = 1;
```

**Count how many likes a person received:**
```sql
SELECT p.name, COUNT(l.id) as likes_count
FROM people p
LEFT JOIN likes l ON p.id = l.liked_id
GROUP BY p.id, p.name
ORDER BY likes_count DESC;
```

---

## Table: `dislikes`

Stores dislike relationships between people. Represents when one person passes/rejects another.

### Schema Definition

| Column       | Type            | Null | Key    | Default | Extra          | Description                           |
|--------------|-----------------|------|--------|---------|----------------|---------------------------------------|
| id           | BIGINT UNSIGNED | NO   | PK     | -       | AUTO_INCREMENT | Primary key                           |
| disliker_id  | BIGINT UNSIGNED | NO   | FK,IDX | -       |                | Person who disliked (foreign key)     |
| disliked_id  | BIGINT UNSIGNED | NO   | FK,IDX | -       |                | Person who was disliked (foreign key) |
| created_at   | TIMESTAMP       | YES  |        | NULL    |                | When the dislike occurred             |
| updated_at   | TIMESTAMP       | YES  |        | NULL    |                | Last update timestamp                 |

### Constraints

- **PRIMARY KEY:** `id`
- **FOREIGN KEY:** `disliker_id` REFERENCES `people(id)` ON DELETE CASCADE
- **FOREIGN KEY:** `disliked_id` REFERENCES `people(id)` ON DELETE CASCADE
- **UNIQUE KEY:** `(disliker_id, disliked_id)` - Prevents duplicate dislikes
- **INDEX:** `disliker_id` - For querying who someone disliked
- **INDEX:** `disliked_id` - For querying who disliked someone

### Business Rules

1. A person cannot dislike themselves (`disliker_id ≠ disliked_id`)
2. Each dislike is unique (UNIQUE constraint on disliker_id + disliked_id)
3. If a person already liked someone, the like is removed when they dislike them
4. When a person is deleted, all their dislikes are deleted (CASCADE)

### Example Data

```sql
-- Person 1 dislikes Person 4
INSERT INTO dislikes (disliker_id, disliked_id, created_at, updated_at) 
VALUES (1, 4, NOW(), NOW());

-- Person 2 dislikes Person 5
INSERT INTO dislikes (disliker_id, disliked_id, created_at, updated_at) 
VALUES (2, 5, NOW(), NOW());
```

### Sample Records

```
+----+-------------+-------------+---------------------+---------------------+
| id | disliker_id | disliked_id | created_at          | updated_at          |
+----+-------------+-------------+---------------------+---------------------+
|  1 |           1 |           4 | 2024-01-01 11:00:00 | 2024-01-01 11:00:00 |
|  2 |           2 |           5 | 2024-01-01 11:05:00 | 2024-01-01 11:05:00 |
|  3 |           1 |           6 | 2024-01-01 11:10:00 | 2024-01-01 11:10:00 |
+----+-------------+-------------+---------------------+---------------------+
```

### Query Examples

**Get all people that User 1 disliked:**
```sql
SELECT p.* 
FROM people p
JOIN dislikes d ON p.id = d.disliked_id
WHERE d.disliker_id = 1;
```

**Get recommended people (excluding liked and disliked):**
```sql
SELECT p.* 
FROM people p
WHERE p.id != 1  -- Not current user
  AND p.id NOT IN (
      SELECT liked_id FROM likes WHERE liker_id = 1
  )
  AND p.id NOT IN (
      SELECT disliked_id FROM dislikes WHERE disliker_id = 1
  )
ORDER BY p.created_at DESC;
```

---

## Relationships

### One-to-Many Relationships

Each relationship uses foreign keys with CASCADE delete:

1. **people → likes (as liker)**
   - One person can like many people
   - `likes.liker_id` → `people.id`

2. **people → likes (as liked)**
   - One person can be liked by many people
   - `likes.liked_id` → `people.id`

3. **people → dislikes (as disliker)**
   - One person can dislike many people
   - `dislikes.disliker_id` → `people.id`

4. **people → dislikes (as disliked)**
   - One person can be disliked by many people
   - `dislikes.disliked_id` → `people.id`

### Many-to-Many Relationships

1. **people ↔ people (through likes)**
   - Many people can like many people
   - Junction table: `likes`

2. **people ↔ people (through dislikes)**
   - Many people can dislike many people
   - Junction table: `dislikes`

### Match Detection Logic

A match occurs when:
```sql
EXISTS (
    SELECT 1 FROM likes 
    WHERE liker_id = A AND liked_id = B
)
AND EXISTS (
    SELECT 1 FROM likes 
    WHERE liker_id = B AND liked_id = A
)
```

---

## Database Indexes

### Performance Optimization

| Table     | Index Name                    | Columns                  | Type   | Purpose                          |
|-----------|-------------------------------|--------------------------|--------|----------------------------------|
| people    | PRIMARY                       | id                       | BTREE  | Primary key lookup               |
| people    | idx_created_at                | created_at               | BTREE  | Order by creation date           |
| likes     | PRIMARY                       | id                       | BTREE  | Primary key lookup               |
| likes     | idx_liker_id                  | liker_id                 | BTREE  | Find who someone liked           |
| likes     | idx_liked_id                  | liked_id                 | BTREE  | Find who liked someone           |
| likes     | unique_like                   | (liker_id, liked_id)     | UNIQUE | Prevent duplicate likes          |
| dislikes  | PRIMARY                       | id                       | BTREE  | Primary key lookup               |
| dislikes  | idx_disliker_id               | disliker_id              | BTREE  | Find who someone disliked        |
| dislikes  | idx_disliked_id               | disliked_id              | BTREE  | Find who disliked someone        |
| dislikes  | unique_dislike                | (disliker_id, disliked_id)| UNIQUE | Prevent duplicate dislikes       |

---

## Migration Files

The database schema is created using Laravel migrations:

### 1. Create People Table
**File:** `database/migrations/2024_01_01_000001_create_people_table.php`

```php
Schema::create('people', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->integer('age');
    $table->json('pictures');
    $table->string('location');
    $table->text('bio')->nullable();
    $table->enum('gender', ['male', 'female', 'other']);
    $table->timestamps();
});
```

### 2. Create Likes Table
**File:** `database/migrations/2024_01_01_000002_create_likes_table.php`

```php
Schema::create('likes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('liker_id')->constrained('people')->onDelete('cascade');
    $table->foreignId('liked_id')->constrained('people')->onDelete('cascade');
    $table->timestamps();
    
    // Prevent duplicate likes
    $table->unique(['liker_id', 'liked_id']);
});
```

### 3. Create Dislikes Table
**File:** `database/migrations/2024_01_01_000003_create_dislikes_table.php`

```php
Schema::create('dislikes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('disliker_id')->constrained('people')->onDelete('cascade');
    $table->foreignId('disliked_id')->constrained('people')->onDelete('cascade');
    $table->timestamps();
    
    // Prevent duplicate dislikes
    $table->unique(['disliker_id', 'disliked_id']);
});
```

---

## Database Statistics

### Sample Database Size

With 15 sample people and various likes/dislikes:

| Table     | Rows | Avg Row Size | Total Size |
|-----------|------|--------------|------------|
| people    | 15   | ~500 bytes   | ~7.5 KB    |
| likes     | 25   | ~100 bytes   | ~2.5 KB    |
| dislikes  | 18   | ~100 bytes   | ~1.8 KB    |
| **Total** | 58   |              | ~12 KB     |

### Estimated Production Scale

For 10,000 users:

| Table     | Est. Rows | Est. Size | Notes                                |
|-----------|-----------|-----------|--------------------------------------|
| people    | 10,000    | ~5 MB     | Average 500 bytes per profile        |
| likes     | 50,000    | ~5 MB     | Average 5 likes per user             |
| dislikes  | 30,000    | ~3 MB     | Average 3 dislikes per user          |
| **Total** | 90,000    | ~13 MB    | Excluding indexes (~20 MB with indexes) |

---

## SQL Queries Reference

### Common Queries

**1. Get recommended people for user:**
```sql
SELECT p.* 
FROM people p
WHERE p.id != ?  -- Current user ID
  AND NOT EXISTS (
      SELECT 1 FROM likes WHERE liker_id = ? AND liked_id = p.id
  )
  AND NOT EXISTS (
      SELECT 1 FROM dislikes WHERE disliker_id = ? AND disliked_id = p.id
  )
ORDER BY p.created_at DESC
LIMIT 10;
```

**2. Get all matches for user:**
```sql
SELECT p.* 
FROM people p
INNER JOIN likes l1 ON p.id = l1.liked_id
INNER JOIN likes l2 ON p.id = l2.liker_id
WHERE l1.liker_id = ?  -- Current user
  AND l2.liked_id = ?  -- Current user
ORDER BY l1.created_at DESC;
```

**3. Check if it's a match:**
```sql
SELECT 
    CASE 
        WHEN COUNT(*) = 2 THEN 1  -- Match
        ELSE 0                     -- Not a match
    END as is_match
FROM likes
WHERE (liker_id = ? AND liked_id = ?)
   OR (liker_id = ? AND liked_id = ?);
```

**4. Get popular people (50+ likes):**
```sql
SELECT p.*, COUNT(l.id) as likes_count
FROM people p
INNER JOIN likes l ON p.id = l.liked_id
GROUP BY p.id
HAVING likes_count >= 50
ORDER BY likes_count DESC;
```

**5. Get user statistics:**
```sql
SELECT 
    p.name,
    (SELECT COUNT(*) FROM likes WHERE liker_id = p.id) as total_likes_given,
    (SELECT COUNT(*) FROM likes WHERE liked_id = p.id) as total_likes_received,
    (SELECT COUNT(*) FROM dislikes WHERE disliker_id = p.id) as total_dislikes_given,
    (
        SELECT COUNT(*) 
        FROM likes l1
        WHERE l1.liker_id = p.id
        AND EXISTS (
            SELECT 1 FROM likes l2 
            WHERE l2.liked_id = p.id AND l2.liker_id = l1.liked_id
        )
    ) as total_matches
FROM people p
WHERE p.id = ?;
```

---

## Database Maintenance

### Backup

```bash
# Backup database
mysqldump -u root -p tinder_app > backup_$(date +%Y%m%d).sql

# Restore database
mysql -u root -p tinder_app < backup_20240101.sql
```

### Optimization

```sql
-- Analyze tables
ANALYZE TABLE people, likes, dislikes;

-- Optimize tables
OPTIMIZE TABLE people, likes, dislikes;

-- Check table status
SHOW TABLE STATUS WHERE Name IN ('people', 'likes', 'dislikes');
```

### Clean Up Old Data

```sql
-- Delete people with no activity (no likes given/received, no dislikes)
DELETE p FROM people p
WHERE NOT EXISTS (SELECT 1 FROM likes WHERE liker_id = p.id OR liked_id = p.id)
  AND NOT EXISTS (SELECT 1 FROM dislikes WHERE disliker_id = p.id OR disliked_id = p.id)
  AND p.created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
```

---

## Security Considerations

### 1. Data Protection
- Passwords stored with bcrypt hashing (if authentication added)
- Sensitive data encrypted at rest
- SSL/TLS for database connections in production

### 2. SQL Injection Prevention
- All queries use prepared statements
- Laravel's Eloquent ORM provides automatic escaping
- Never concatenate user input into SQL queries

### 3. Access Control
- Database user has minimal required privileges
- Read-only user for reporting/analytics
- Separate credentials for production/development

### 4. Privacy
- User data deletion cascades to likes/dislikes
- GDPR compliance: users can request data deletion
- Anonymous analytics queries

---

## Future Schema Enhancements

Potential additions for future versions:

### 1. Authentication Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    person_id BIGINT UNSIGNED UNIQUE,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (person_id) REFERENCES people(id) ON DELETE CASCADE
);
```

### 2. Messages Table
```sql
CREATE TABLE messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender_id BIGINT UNSIGNED NOT NULL,
    receiver_id BIGINT UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES people(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES people(id) ON DELETE CASCADE,
    INDEX idx_conversation (sender_id, receiver_id)
);
```

### 3. Reports Table
```sql
CREATE TABLE reports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reporter_id BIGINT UNSIGNED NOT NULL,
    reported_id BIGINT UNSIGNED NOT NULL,
    reason ENUM('inappropriate', 'fake', 'harassment', 'other') NOT NULL,
    description TEXT NULL,
    status ENUM('pending', 'reviewed', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (reporter_id) REFERENCES people(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_id) REFERENCES people(id) ON DELETE CASCADE
);
```

---

**Last Updated:** November 20, 2025  
**Database Version:** 1.0  
**Laravel Version:** 10.x  
**MySQL Version:** 8.0+  

For questions or suggestions, contact:  
**Khairul Adha** - r4dioz.88@gmail.com
