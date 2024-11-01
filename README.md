
# Real Estate Bidding Platform Deployment Guide

This guide provides step-by-step instructions for deploying the Real Estate Bidding Platform on a production environment.

## Prerequisites
- **PHP >= 8.0**
- **Composer**
- **MySQL or compatible database**
- **Node.js and npm** (if using frontend assets)
- **Redis** (if using queues or caching)

---

## Deployment Steps

### 1. Clone the Repository
1. SSH into your server.
2. Clone the project repository.
   ```bash
   git clone https://github.com/syedahnb/real-estate-listing-bidding-api.git
   cd real-estate-listing-bidding-api
   ```

### 2. Install Dependencies
- **PHP Dependencies**:
  ```bash
  composer install
  ```
- **Node.js Dependencies** (if applicable):
  ```bash
  npm install
  ```

### 3. Configure Environment Variables
1. Copy `.env.example` to `.env`.
   ```bash
   cp .env.example .env
   ```
2. Update `.env` with production settings:
    - **DB_DATABASE**: Database name
    - **DB_USERNAME**: Database username
    - **DB_PASSWORD**: Database password
    - **APP_URL**: URL of the app, e.g., `https://yourdomain.com`

### 4. Generate the Application Key
This key is necessary for encrypted data.
```bash
php artisan key:generate
```

### 5. Set Up the Database
1. Run migrations to create tables.
   ```bash
   php artisan migrate
   ```
2. Optional: Seed data if required for testing.
   ```bash
   php artisan db:seed
   ```

### 6. Set Up File Storage Link
To enable file uploads, link storage:
```bash
php artisan storage:link
```

### 7. Configure Cache and Queue (Optional)
If using Redis, update `.env`:
```plaintext
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```
Run the queue worker:
```bash
php artisan queue:work
```

### 8. Build Frontend Assets (if applicable)
If frontend assets are used, build them:
```bash
npm run build
```

### 9. Serve the Application
#### Local Development
```bash
php artisan serve
```

#### Production (Apache/Nginx)
Point the web server to the `public` directory.

---

## Post-Deployment Notes
- **Clearing Cache**:
  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
  ```

- **Running Tests**:
  ```bash
  php artisan test
  ```

---

## API Endpoints

### Base URL
`https://yourdomain.com/api`

---

### User Authentication
1. **Register**
    - **Method**: POST
    - **Endpoint**: `/api/register`
    - **Body**:
      ```json
      {
        "name": "John Doe",
        "email": "johndoe@example.com",
        "password": "password123"
      }
      ```

2. **Login**
    - **Method**: POST
    - **Endpoint**: `/api/login`
    - **Body**:
      ```json
      {
        "email": "johndoe@example.com",
        "password": "password123"
      }
      ```

### Listings
1. **Create a Listing** (Admin only)
    - **Method**: POST
    - **Endpoint**: `/api/listings`
    - **Body**:
      ```json
      {
        "title": "Luxury Apartment",
        "description": "Beautiful apartment in NYC.",
        "base_price": 500000,
        "location": "New York",
        "status": "active",
        "expiry_date": "2024-12-31"
      }
      ```

2. **Get Listings**
    - **Method**: GET
    - **Endpoint**: `/api/listings`
    - **Params**:
        - `location`: Filter by location
        - `min_price`: Minimum price
        - `max_price`: Maximum price
        - `sort_by`: Sort by `base_price`, `created_at`, or `expiry_date`

### Bidding
1. **Place a Bid**
    - **Method**: POST
    - **Endpoint**: `/api/listings/{listingId}/bid`
    - **Body**:
      ```json
      {
        "bid_amount": 550000
      }
      ```

2. **Get Bidding History**
    - **Method**: GET
    - **Endpoint**: `/api/users/{userId}/bidding-history`
    - **Response** (Example):
      ```json
      [
        {
          "listing": {
            "title": "Luxury Apartment",
            "highest_bid": 550000
          },
          "bid_amount": 550000,
          "status": "winning",
          "timestamp": "2024-11-01T12:00:00Z"
        }
      ]
      ```

---

## Downloadable Postman Collection Files

For easier testing, download the Postman collection files for each module:

- **User Module**: [Download User Collection](sandbox:/mnt/data/User.postman_collection.json)
- **Listings Module**: [Download Listings Collection](sandbox:/mnt/data/Listings.postman_collection.json)
- **Bids Module**: [Download Bids Collection](sandbox:/mnt/data/Bids.postman_collection.json)

---

## Troubleshooting
- **500 Server Error**: Verify `.env` configurations, file permissions, and ensure dependencies are installed.
- **Queue Issues**: Confirm `queue:work` is running or check `QUEUE_CONNECTION`.

---

For more details, consult the application's documentation or reach out to support.

