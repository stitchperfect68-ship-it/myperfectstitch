# My Perfect Stitch

A Zambian design and manufacturing studio — custom bags, bespoke furniture, and commercial interiors. Built in Lusaka, delivered to world-class standards.

## Tech Stack

- **Framework:** Laravel 12 (PHP 8.2)
- **Database:** MySQL / MariaDB
- **Frontend:** Blade + Tailwind CSS
- **Auth:** Laravel Breeze (admin) + Supabase (customer — email & Google OAuth)
- **Payments:** Broadpay Linco
- **Permissions:** Spatie Laravel Permission

## Features

- Public site: home, shop, gallery, portfolio, events pages
- E-commerce: product quick-view, cart, checkout via Broadpay Linco
- Quote system: 5-step modal — saves to DB and sends via WhatsApp
- Admin panel: full CRUD for products, categories, gallery, portfolio, events, sliders, team, clients, orders, quotes, customers, payments
- Mobile responsive (public site + admin panel)

## Setup

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Environment Variables

Copy `.env.example` to `.env` and fill in:
- `DB_*` — database credentials
- `SUPABASE_URL`, `SUPABASE_ANON_KEY`, `SUPABASE_JWT_SECRET` — Supabase project
- `BROADPAY_*` — Broadpay Linco payment gateway credentials

## Admin

Access the admin panel at `/login` after seeding.
