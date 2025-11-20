# API Documentation - PHP Tinder app 20250929

**Developer:** Khairul Adha  
**Email:** r4dioz.88@gmail.com  
**GitHub:** r4diorusak  

Base URL: `http://localhost:8000/api`

## Authentication

Currently, the API uses a simple `user_id` parameter for authentication. In production, implement proper authentication using Laravel Sanctum or JWT.

## Endpoints

### 1. Get Recommended People

Get a paginated list of recommended people (excluding already liked/disliked profiles).

**Endpoint:** `GET /people`

**Query Parameters:**
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| user_id | integer | Yes | - | Current user's ID |
| page | integer | No | 1 | Page number for pagination |
| per_page | integer | No | 10 | Number of results per page |

**Example Request:**
```http
GET http://localhost:8000/api/people?user_id=1&page=1&per_page=10
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "name": "Sarah Johnson",
      "age": 25,
      "pictures": [
        "https://i.pravatar.cc/300?img=1",
        "https://i.pravatar.cc/300?img=2"
      ],
      "location": "New York, USA",
      "bio": "Love hiking, coffee, and adventure!",
      "gender": "female",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 14,
    "last_page": 2,
    "has_more": true
  }
}
```

---

### 2. Like a Person

Like a person's profile. If mutual like exists, returns match information.

**Endpoint:** `POST /people/{id}/like`

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | ID of person to like |

**Request Body:**
```json
{
  "user_id": 1
}
```

**Example Request:**
```http
POST http://localhost:8000/api/people/2/like
Content-Type: application/json

{
  "user_id": 1
}
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Person liked successfully",
  "is_match": false,
  "data": {
    "liked_person": {
      "id": 2,
      "name": "Sarah Johnson",
      "age": 25,
      "pictures": ["..."],
      "location": "New York, USA",
      "bio": "Love hiking, coffee, and adventure!",
      "gender": "female"
    }
  }
}
```

**Success Response with Match (200 OK):**
```json
{
  "success": true,
  "message": "Person liked successfully",
  "is_match": true,
  "data": {
    "liked_person": {
      "id": 2,
      "name": "Sarah Johnson",
      ...
    }
  }
}
```

**Error Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "You have already liked this person"
}
```

**Error Response (404 Not Found):**
```json
{
  "message": "No query results for model [App\\Models\\Person]."
}
```

---

### 3. Dislike a Person

Dislike a person's profile.

**Endpoint:** `POST /people/{id}/dislike`

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | ID of person to dislike |

**Request Body:**
```json
{
  "user_id": 1
}
```

**Example Request:**
```http
POST http://localhost:8000/api/people/3/dislike
Content-Type: application/json

{
  "user_id": 1
}
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Person disliked successfully"
}
```

**Error Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "You have already disliked this person"
}
```

---

### 4. Get Liked People List

Get a paginated list of people the user has liked.

**Endpoint:** `GET /people/liked`

**Query Parameters:**
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| user_id | integer | Yes | - | Current user's ID |
| page | integer | No | 1 | Page number for pagination |
| per_page | integer | No | 10 | Number of results per page |

**Example Request:**
```http
GET http://localhost:8000/api/people/liked?user_id=1&page=1&per_page=10
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "name": "Sarah Johnson",
      "age": 25,
      "pictures": ["..."],
      "location": "New York, USA",
      "bio": "Love hiking, coffee, and adventure!",
      "gender": "female",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 5,
    "last_page": 1,
    "has_more": false
  }
}
```

---

### 5. Get Disliked People List

Get a paginated list of people the user has disliked.

**Endpoint:** `GET /people/disliked`

**Query Parameters:**
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| user_id | integer | Yes | - | Current user's ID |
| page | integer | No | 1 | Page number for pagination |
| per_page | integer | No | 10 | Number of results per page |

**Example Request:**
```http
GET http://localhost:8000/api/people/disliked?user_id=1&page=1&per_page=10
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 3,
      "name": "John Doe",
      "age": 28,
      "pictures": ["..."],
      "location": "Chicago, USA",
      "bio": "Sports enthusiast",
      "gender": "male"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 8,
    "last_page": 1,
    "has_more": false
  }
}
```

---

### 6. Get Liked Opponents

Get a list of people who have liked the current user.

**Endpoint:** `GET /people/liked-opponents`

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | Yes | Current user's ID |

**Example Request:**
```http
GET http://localhost:8000/api/people/liked-opponents?user_id=1
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 4,
      "name": "Lisa Anderson",
      "age": 23,
      "pictures": ["..."],
      "location": "Miami, USA",
      "bio": "Beach lover",
      "gender": "female",
      "is_match": false
    },
    {
      "id": 7,
      "name": "Anna Brown",
      "age": 26,
      "pictures": ["..."],
      "location": "Seattle, USA",
      "bio": "Coffee and books",
      "gender": "female",
      "is_match": true
    }
  ],
  "count": 2
}
```

**Note:** `is_match` field indicates if it's a mutual like (both liked each other).

---

### 7. Get Matches

Get a list of mutual likes (matches).

**Endpoint:** `GET /people/matches`

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | Yes | Current user's ID |

**Example Request:**
```http
GET http://localhost:8000/api/people/matches?user_id=1
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "name": "Sarah Johnson",
      "age": 25,
      "pictures": ["..."],
      "location": "New York, USA",
      "bio": "Love hiking, coffee, and adventure!",
      "gender": "female"
    },
    {
      "id": 5,
      "name": "Emma Williams",
      "age": 24,
      "pictures": ["..."],
      "location": "London, UK",
      "bio": "Artist, traveler, foodie",
      "gender": "female"
    }
  ],
  "count": 2
}
```

---

### 8. Check Popular People (Admin/Cronjob)

Manually trigger checking for people with 50+ likes and send email notification to admin.

**Endpoint:** `POST /people/check-popular`

**Request Body:** None required

**Example Request:**
```http
POST http://localhost:8000/api/people/check-popular
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Found 2 popular people and sent notifications",
  "data": {
    "count": 2,
    "people": [
      {
        "id": 81,
        "name": "Test Popular Person",
        "likes_count": 51
      },
      {
        "id": 15,
        "name": "Emma Johnson",
        "likes_count": 63
      }
    ],
    "admin_email": "admin@example.com"
  }
}
```

**Note:** This endpoint is typically used by cronjob scheduled to run daily. Email notifications are sent to the admin email configured in `.env` (ADMIN_EMAIL).

---

## Data Models

### Person Model

```typescript
{
  id: number;
  name: string;
  age: number;
  pictures: string[];      // Array of image URLs
  location: string;
  bio: string | null;
  gender: 'male' | 'female' | 'other';
  created_at: string;      // ISO 8601 timestamp
  updated_at: string;      // ISO 8601 timestamp
}
```

### Pagination Model

```typescript
{
  current_page: number;
  per_page: number;
  total: number;
  last_page: number;
  has_more: boolean;
}
```

---

## Error Handling

All endpoints may return the following error responses:

### 400 Bad Request
Invalid request parameters or business logic error.

```json
{
  "success": false,
  "message": "Error description"
}
```

### 404 Not Found
Resource not found.

```json
{
  "message": "No query results for model [App\\Models\\Person] {id}"
}
```

### 500 Internal Server Error
Server error.

```json
{
  "message": "Server Error",
  "exception": "..."
}
```

---

## Testing with cURL

### Get Recommended People
```bash
curl -X GET "http://localhost:8000/api/people?user_id=1&page=1&per_page=10"
```

### Like a Person
```bash
curl -X POST "http://localhost:8000/api/people/2/like" \
  -H "Content-Type: application/json" \
  -d '{"user_id": 1}'
```

### Dislike a Person
```bash
curl -X POST "http://localhost:8000/api/people/3/dislike" \
  -H "Content-Type: application/json" \
  -d '{"user_id": 1}'
```

### Get Liked People
```bash
curl -X GET "http://localhost:8000/api/people/liked?user_id=1"
```

### Get Disliked People
```bash
curl -X GET "http://localhost:8000/api/people/disliked?user_id=1"
```

### Get Liked Opponents
```bash
curl -X GET "http://localhost:8000/api/people/liked-opponents?user_id=1"
```

### Get Matches
```bash
curl -X GET "http://localhost:8000/api/people/matches?user_id=1"
```

### Check Popular People
```bash
curl -X POST "http://localhost:8000/api/people/check-popular"
```

---

## Testing with Postman

### Import Collection

Create a new Postman collection with the following requests:

1. **Get Recommended People**
   - Method: GET
   - URL: `{{base_url}}/people?user_id=1&page=1&per_page=10`

2. **Like Person**
   - Method: POST
   - URL: `{{base_url}}/people/2/like`
   - Body (JSON):
     ```json
     {
       "user_id": 1
     }
     ```

3. **Dislike Person**
   - Method: POST
   - URL: `{{base_url}}/people/3/dislike`
   - Body (JSON):
     ```json
     {
       "user_id": 1
     }
     ```

4. **Get Liked People**
   - Method: GET
   - URL: `{{base_url}}/people/liked?user_id=1`

5. **Get Matches**
   - Method: GET
   - URL: `{{base_url}}/people/matches?user_id=1`

### Environment Variables
```
base_url: http://localhost:8000/api
user_id: 1
```

---

## Rate Limiting

Currently, no rate limiting is implemented. For production, consider adding rate limiting:

```php
// routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // Your routes here
});
```

This limits to 60 requests per minute per IP.

---

## CORS Configuration

The API allows all origins by default (for development). For production, update `config/cors.php`:

```php
'allowed_origins' => ['https://your-app-domain.com'],
'allowed_methods' => ['GET', 'POST'],
'allowed_headers' => ['Content-Type', 'Authorization'],
```

---

## Future API Enhancements

- [ ] Authentication endpoints (register, login, logout)
- [ ] User profile endpoints (get, update, upload photos)
- [ ] Advanced filtering (age range, distance, gender preference)
- [ ] Super like endpoint
- [ ] Undo last swipe
- [ ] Report user endpoint
- [ ] Block user endpoint
- [ ] Chat messaging endpoints
- [ ] Push notification integration
- [ ] WebSocket for real-time features

---

## Changelog

### Version 1.0.0 (Current)
- Initial API release
- Basic CRUD operations for people
- Like/Dislike functionality
- Match detection
- Pagination support

---

For questions or issues, please create an issue in the GitHub repository.
