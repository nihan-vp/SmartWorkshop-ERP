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
