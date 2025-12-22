# PHP_Laravel12_Register_Using_Api_with_Validation

A full Laravel 12 project demonstrating API authentication using **Laravel Sanctum**, supporting email or mobile login, protected dashboard, and public users listing. Includes a JS frontend with login, registration, and dashboard pages styled with **TailwindCSS**.

---

## Features

* User registration with validation (name, email, mobile, password)
* Login using email or mobile number
* Password hashed securely
* Token-based authentication using Laravel Sanctum
* Protected routes: `/profile`, `/logout`
* Public route: `/users` (for testing/demo purposes)
* JS frontend using TailwindCSS
* Dashboard showing logged-in user info
* Logout functionality

---

## Tech Stack

* **Backend:** Laravel 12, PHP 8.2+, Sanctum
* **Frontend:** Blade + JavaScript + TailwindCSS
* **Database:** MySQL
* **API Testing:** Postman

---

## Installation / Setup

### 1. Clone Repository

```bash
git clone https://github.com/your-username/PHP_Laravel12_Register_Using_Api_with_Validation.git
cd  PHP_Laravel12_Register_Using_Api_with_Validation
```

### 2. Install Dependencies

```bash
composer install
npm install
npm run dev
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Serve the Project

```bash
php artisan serve
```

Application URL: `http://localhost:8000`

---

## API Routes

| Method | URL           | Description                 | Protected |
| ------ | ------------- | --------------------------- | --------- |
| POST   | /api/register | Register new user           | ❌         |
| POST   | /api/login    | Login using email or mobile | ❌         |
| GET    | /api/users    | List all users              | ❌         |
| GET    | /api/profile  | Get logged-in user profile  | ✅         |
| POST   | /api/logout   | Logout user                 | ✅         |

**Headers for protected routes:**

```
Authorization: Bearer <token>
Accept: application/json
```

---

## Frontend Pages

| Page      | URL        | Description                       |
| --------- | ---------- | --------------------------------- |
| Login     | /login     | Enter email/mobile + password     |
| Register  | /register  | Create new account                |
| Dashboard | /dashboard | Shows logged-in user info, logout |

---

## Login Flow

1. Enter email or mobile and password.
2. JS sends POST request to `/api/login`.
3. On success, token is stored in `localStorage`.
4. Redirect to `/dashboard`.
5. Dashboard uses token to fetch `/api/profile`.
6. Logout deletes token and redirects to login page.

---

## Registration Flow

1. Enter name, email, mobile, password, password confirmation.
2. JS sends POST request to `/api/register`.
3. Validation errors are displayed if fields are invalid.
4. On success, redirected to login page.

---

## Postman Testing

**Register New User**

* Method: POST `/api/register`
* Body (JSON):

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "mobile": "9876543210",
  "password": "Password123",
  "password_confirmation": "Password123"
}
```

**Login User**

* Method: POST `/api/login`
* Body (JSON):

```json
{
  "login": "john@example.com",
  "password": "Password123"
}
```

Response includes token. Use `Authorization: Bearer <token>` for `/profile` or `/logout`.

**Get All Users (Public)**

* Method: GET `/api/users`
* No token required.

---

## Validation Rules

* **Name:** required, minimum 3 characters
* **Email:** required, unique
* **Mobile:** required, 10 digits, unique
* **Password:** required, confirmed, minimum 8 characters, must include uppercase, lowercase, and number

---

## Security Notes

* `/users` route is public for demo/testing only.
* `/profile` and `/logout` are protected using Sanctum tokens.
* Tokens must be included in `Authorization: Bearer <token>` header.

---

## Frontend Notes

* TailwindCSS for styling.
* JS handles:

  * Login
  * Registration
  * Dashboard user info
  * Logout
* Token stored in `localStorage` and used for protected API calls.

---

## Screenshots / Demo

* **Login Page:**
<img width="1318" height="859" alt="image" src="https://github.com/user-attachments/assets/2050c8b0-31e7-4f96-b386-da728298070f" />

* **Register Page:**
<img width="958" height="879" alt="image" src="https://github.com/user-attachments/assets/d2197418-d0ae-4536-8e70-7790f47187a9" />

* **Dashboard Page:**
<img width="1616" height="738" alt="image" src="https://github.com/user-attachments/assets/54d41e1c-2b08-43cb-aab4-01be9823d3a6" />


---

## Troubleshooting

* Run `php artisan cache:clear` or `php artisan config:clear` if environment changes are not reflecting
* Check `.env` for database or Sanctum settings
* Run `composer dump-autoload` if classes are not found
