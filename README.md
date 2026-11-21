# UstaTop — On-Demand Craftsman & Technician Service Platform

**UstaTop** is a full-stack multi-role web platform designed to connect clients with verified local craftsmen and service technicians (electricians, plumbers, mechanics, electronics repairers, etc.).

Built with **Laravel 12**, **Blade Templates**, **Tailwind CSS**, and **PostgreSQL**.

---

## Features & Roles

### 1. Client / User
- Browse service categories and submit service requests with location, contact info, and job descriptions.
- Monitor request lifecycle: `pending` → `accepted` → `provider_done` → `completed`.
- Confirm job completion and manage profile themes (Light, Dark, System).

### 2. Service Provider (Craftsman)
- Submit provider verification requests with phone, service city, and specialized trade categories.
- Dashboard with real-time incoming service requests matching provider trade categories.
- Accept service jobs, notify clients, and mark jobs as completed.

### 3. Administrator
- Administrative dashboard with statistics on users, active requests, and service volumes.
- Review, approve, or reject provider applications.
- Create and organize service categories.

---

## Tech Stack

- **Backend Framework:** Laravel 12 (PHP 8.2+)
- **Authentication & Starter:** Laravel Breeze
- **Frontend / Styling:** Blade, Tailwind CSS, Vite
- **Database:** PostgreSQL (with SQLite / MySQL compatibility)
- **Containerization:** Docker, Nginx, PHP-FPM, Supervisord

---

## How to Host 100% Free Live

This repository is pre-configured with a production multi-stage `Dockerfile` and `render.yaml` blueprint for **100% free hosting**.

### Method 1: Render.com (Recommended — 100% Free Forever)

1. Create a free account at [render.com](https://render.com) (no credit card needed).
2. Go to **Blueprints** -> Click **New Blueprint Instance**.
3. Connect your GitHub account and select your **`UstaTop`** repository.
4. Render will automatically read `render.yaml`, set up the free PostgreSQL database, build the Docker container, run migrations, and assign you a free HTTPS live URL (e.g. `https://ustatop.onrender.com`).
5. Every time you run `git push origin main`, Render automatically rebuilds and updates your live site!

### Method 2: Koyeb (Alternative Free Container Hosting)

1. Create a free account at [koyeb.com](https://www.koyeb.com).
2. Create a free serverless PostgreSQL database (via Neon.tech or Supabase).
3. Connect your `UstaTop` GitHub repository, select Docker builder, and set your `DB_HOST`, `DB_PASSWORD`, `APP_KEY` environment variables.

---

## Local Development Setup

```bash
# 1. Clone repository
git clone https://github.com/Nurmuhammad-Xusanov/UstaTop.git
cd UstaTop

# 2. Install dependencies
composer install
npm install && npm run build

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Run migrations
php artisan migrate

# 5. Serve application
php artisan serve
```

---

## License

Open-source project released under the [MIT License](LICENSE).