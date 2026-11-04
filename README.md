# My Perfect Stitch

A Zambian design and manufacturing studio — custom bags, bespoke furniture, and commercial interiors. Built in Lusaka, delivered to world-class standards.

## Tech Stack

- **Framework:** Laravel 12 (PHP 8.2)
- **Database:** MySQL / MariaDB
- **Frontend:** Blade + Tailwind CSS
- **Auth:** Laravel Breeze (admin) + Supabase (customer — email & Google OAuth)
- **Payments:** Lenco (mobile money + card via LencoPay inline widget)
- **Permissions:** Spatie Laravel Permission

## Features

- Public site: home, shop, gallery, portfolio, events pages
- E-commerce: product quick-view, cart, checkout via Lenco (mobile money + card)
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
- `LENCO_API_KEY` — secret Lenco API token (server-side only)
- `LENCO_PUBLIC_KEY` — public key from the Lenco Pay dashboard (card widget)
- `LENCO_WEBHOOK_SECRET` — optional; defaults to `SHA256(LENCO_API_KEY)`
- `LENCO_COUNTRY`, `LENCO_CURRENCY`, `LENCO_BEARER` — defaults `zm`, `ZMW`, `customer`
- `BROADPAY_*` — Broadpay Linco credentials for admin quote payment links

## Admin

Access the admin panel at `/login` after seeding.

## Payments (Lenco)

- **Mobile money** is initiated server-side via `POST /access/v2/collections/mobile-money` (Airtel, MTN, Zamtel).
- **Card** uses the PCI-compliant [LencoPay inline widget](https://pay.lenco.co/js/v1/inline.js), so card data never touches this app. Set `LENCO_PUBLIC_KEY` to enable it.
- Payment status is confirmed by webhook and by server-side requery of `/access/v2/collections/status/{reference}` — the client is never trusted.

Configure the Lenco webhook URL as:

```
https://<your-domain>/payment/webhook
```

Webhook signatures are HMAC-SHA512 over the raw request body using `SHA256(LENCO_API_KEY)` (or `LENCO_WEBHOOK_SECRET` if set).

### Sandbox test accounts

| Phone | Operator | Result |
| --- | --- | --- |
| 0961111111 | mtn | Successful |
| 0971111111 | airtel | Successful |
| 0962222222 | mtn | Failed (insufficient funds) |

Test card: `4622 9431 2701 3705`, CVV `838`, any future expiry.
