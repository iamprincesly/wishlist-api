
# Laravel Wishlist API

A production-grade Laravel 12 REST API providing Wishlist functionality for an e-commerce application.

---

## Features

- User Registration & Login (Laravel Sanctum Token-based Auth)
- Product listing
- Wishlist Management:
- Add products to wishlist
- View user's wishlist
- Remove products from wishlist
- Full Pest PHP Unit and Feature Tests
- Database reset between tests for isolation

---

## Requirements

| Software           | Version                     |
| ------------------ | --------------------------- |
| PHP                | >= 8.2                      |
| Composer           | Latest                      |
| Laravel            | 12.x                        |
| Database           | MySQL / PostgreSQL / SQLite |

---

## Installation Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/iamprincesly/wishlist-api.git
cd wishlist-api
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Environment Variables

```bash
cp .env.example .env
```

Then edit `.env` and set your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wishlist_api
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. (Optional) Seed Sample Products

If you want sample products for manual API testing:

```bash
php artisan app:refresh
```

---

## Running the API Locally

```bash
php artisan serve
```

API Base URL:

```
http://localhost:8000
```

---

## API Endpoints

| Method | Endpoint                  | Description                                  |
| ------ | ------------------------- | -------------------------------------------- |
| POST   | v1/auth/register            | Register a new user                          |
| POST   | v1/auth/login                | Login existing user                          |
| POST   | v1/auth/logout                | Logout user                          |
| GET   | v1/user                | Get logged in user details (Auth required)                          |
| GET    | /v1/products             | Get all products (Auth Required)             |
| GET    | /v1/products/wishlist             | Get user's wishlist (Auth Required)          |
| POST   | /v1/products/wishlist/{productId}             | Add product to wishlist (Auth Required)      |
| DELETE | /v1/products/wishlist/{productId} | Remove product from wishlist (Auth Required) |

**Authentication:** Pass Bearer token in the header:

```
Authorization: Bearer {token}
```

---

## Running Tests (Pest PHP)

### 1. Create `.env.testing`

Duplicate your `.env`:

```bash
cp .env .env.testing
```

Set a dedicated testing database in `.env.testing`:

```
DB_CONNECTION=mysql
DB_DATABASE=wishlist_api_test
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Run Test Migrations

```bash
php artisan app:refresh --env=testing
```

### 3. Run Pest Tests

```bash
php artisan test
```

Or directly:

```bash
vendor/bin/pest
```

---

# For easy setup just run the below command
```bash
make setup
```

```bash
make serve
```

```bash
make test
```

---

## Tests Covered

- ✅ User Registration
- ✅ User Login
- ✅ Invalid Login Attempt
- ✅ Fetching Products
- ✅ Adding Products to Wishlist
- ✅ Removing Products from Wishlist
- ✅ Fetching User Wishlist
- ✅ Access Control: Prevent Viewing Other Users' Wishlists

---

## API Doc

- Postman link https://documenter.getpostman.com/view/15626596/2sB2xEAoDn
