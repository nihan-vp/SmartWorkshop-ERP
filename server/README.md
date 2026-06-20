# Suhaim Soft Workshop Management System

A robust and secure workshop management platform built with Laravel, designed to run in a local environment.

## Local Development Setup

To run this application locally:

1. **Configure Environment**: Copy `.env.example` to `.env` (if not already present) and configure your database settings (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
2. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```
3. **Database Migration & Seed**:
   ```bash
   php artisan migrate --seed
   ```
4. **Compile Assets & Start Servers**:
   ```bash
   npm run dev
   php artisan serve
   ```
   The application will be accessible locally at `http://127.0.0.1:8000`.

---

## 🔐 Super Admin Credentials

Login URL: `http://127.0.0.1:8000/login`

### Super Admin 1
| Field    | Value                        |
|----------|------------------------------|
| Name     | Suhaim Soft Super Admin      |
| Email    | `infosuhaimsoft@gmail.com`   |
| Password | `12345678`                   |

### Super Admin 2
| Field    | Value                        |
|----------|------------------------------|
| Name     | Super Admin 2                |
| Email    | `admin2@suhaimsoft.com`      |
| Password | `admin2@123`                 |

> ⚠️ **Important:** Change these passwords in a production environment.

---

## 🔄 Reset Super Admin

To reset or recreate a super admin account, run:

```bash
php artisan super-admin:reset
```

Or use the secure web route (requires `ADMIN_RESET_KEY` env variable):

```
http://127.0.0.1:8000/run-migrations?key=YOUR_SECRET_KEY
```
