# Suhaim Soft Workshop

This project uses a custom directory structure where the Laravel backend is placed inside the `server/` directory, while the `public/` directory remains at the root level for easy shared-hosting deployment.

## How to Run the Application Locally

Because the `artisan` file is located inside the `server/` folder, you cannot run `php artisan` commands from the root directory.

### Option 1: Using `php artisan serve` (Recommended)
1. Open your terminal.
2. Navigate into the `server` directory:
   ```bash
   cd server
   ```
3. Run the artisan serve command:
   ```bash
   php artisan serve
   ```
4. Access the application in your browser at `http://127.0.0.1:8000`.

### Option 2: Using PHP's Built-in Server
If you prefer to serve the application directly from the root folder using the `public` directory:
1. Open your terminal in the root directory (`suhaimsoftworkshop`).
2. Run the following command:
   ```bash
   php -S localhost:8000 -t public
   ```
3. Access the application in your browser at `http://localhost:8000`.

## Running Artisan Commands
Any time you need to run an artisan command (such as `migrate`, `optimize`, or `tinker`), you **must** first change your directory to the `server` folder:

```bash
cd server
php artisan migrate
```
