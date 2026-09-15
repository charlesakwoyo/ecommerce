# SwiftHub

SwiftHub is a single-vendor e-commerce storefront for the Kenyan market, built with Laravel and Livewire. It covers the full flow from browsing a product catalogue through to a Stripe-powered checkout and order history, plus an admin panel for managing products, categories and orders.

All pricing is in Kenyan Shillings (KES), and the storefront ships with a seeded catalogue of realistic Kenyan-market products across 8 categories.

## Tech stack

| Layer | Choice |
|---|---|
| Framework | Laravel 13 (PHP 8.4) |
| Frontend | Livewire 4 + Blade (no separate JS framework/SPA) |
| Styling | Tailwind CSS v4, bundled with Vite |
| Auth | Laravel Fortify (headless) for the web app; Laravel Sanctum (token auth) for the API |
| Payments | Laravel Cashier + Stripe Checkout (one-off payments, not subscriptions) |
| Database | MySQL (local dev), SQLite (automatically used for tests) |
| API docs | [Scramble](https://scramble.dedoc.co/) — auto-generated OpenAPI/Swagger |

## Features

**Storefront**
- Product catalogue with categories, search and filtering
- Product detail pages with related products
- Session-based cart for guests, merged into the user's cart on login
- Stripe Checkout with stock reservation during checkout
- Order history and order detail pages
- Account settings with optional two-factor authentication

**Admin** (`/admin`, requires an admin account)
- Dashboard with product/order counts and revenue
- Product management (create, edit, images, stock, active/inactive)
- Category management
- Order management and status tracking

**API** (`/api/v1`) — a token-authenticated JSON API covering browsing, cart, checkout and orders, documented with interactive Swagger/OpenAPI docs. See [API](#api) below.

## Requirements

- PHP 8.4+
- Composer
- Node.js + npm
- MySQL (or edit `.env` to use SQLite)

## Getting started

```bash
# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Point .env at your database, then:
php artisan migrate --seed
php artisan storage:link

# Build frontend assets
npm run build
```

### Running the app

`composer run dev` starts the PHP dev server, the queue listener and the Vite dev server together:

```bash
composer run dev
```

The app will be available at the `APP_URL` configured in `.env` (defaults to `http://localhost:8000`).

### Seeded accounts

`php artisan migrate --seed` creates two accounts, both with password `password`:

| Email | Role |
|---|---|
| `admin@example.com` | Admin (can access `/admin`) |
| `test@example.com` | Regular customer |

It also seeds 8 categories and ~46 Kenyan-market products with placeholder images. Re-seed at any time with:

```bash
php artisan migrate:fresh --seed
```

### Stripe

Checkout requires real Stripe test keys. Add them to `.env`:

```
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=
CASHIER_CURRENCY=kes
```

Without valid keys, checkout will fail when it reaches Stripe.

## API

SwiftHub exposes a versioned JSON API under `/api/v1`, separate from the Livewire web app, for building a mobile app or third-party integration against the same catalogue, cart and checkout.

### Interactive docs (Swagger/OpenAPI)

Full interactive API documentation is generated automatically from the route definitions, form requests and API resources — no hand-written spec to keep in sync:

- **Swagger UI:** `http://localhost:8000/docs/api`
- **Raw OpenAPI spec:** `http://localhost:8000/docs/api.json`

The UI includes a "Try it" panel for calling endpoints directly from the browser.

### Authentication

The API uses [Sanctum](https://laravel.com/docs/sanctum) personal access tokens (not the web session). Register or log in to receive a token, then send it as a Bearer token on subsequent requests:

```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"email":"test@example.com","password":"password","device_name":"my-app"}'
# => { "user": {...}, "token": "1|xxxxxxxx..." }

curl http://localhost:8000/api/v1/me \
  -H "Authorization: Bearer 1|xxxxxxxx..." -H "Accept: application/json"
```

### Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| POST | `/api/v1/register` | — | Create an account, returns a token |
| POST | `/api/v1/login` | — | Exchange credentials for a token |
| POST | `/api/v1/logout` | ✓ | Revoke the current token |
| GET | `/api/v1/me` | ✓ | Current authenticated user |
| GET | `/api/v1/categories` | — | List categories with product counts |
| GET | `/api/v1/products` | — | List/search products (`q`, `category` query params), paginated |
| GET | `/api/v1/products/{product}` | — | Show a product by slug |
| GET | `/api/v1/cart` | ✓ | View the current user's cart |
| POST | `/api/v1/cart/items` | ✓ | Add a product to the cart |
| PATCH | `/api/v1/cart/items/{cartItem}` | ✓ | Update a cart item's quantity |
| DELETE | `/api/v1/cart/items/{cartItem}` | ✓ | Remove a cart item |
| POST | `/api/v1/checkout` | ✓ | Reserve stock, create an order and return a Stripe Checkout URL |
| GET | `/api/v1/orders` | ✓ | List the current user's orders |
| GET | `/api/v1/orders/{order}` | ✓ | Show one of the current user's orders |

`✓` routes require `Authorization: Bearer <token>`. The full request/response shape for every endpoint — including validation error formats — is in the Swagger UI.

## Testing

```bash
php artisan test
```

Tests run against an in-memory SQLite database automatically (see `phpunit.xml`) and don't touch your local MySQL database.

## Code style

This project uses [Laravel Pint](https://laravel.com/docs/pint) for formatting:

```bash
vendor/bin/pint
```

## Project structure

Domain models live under `app/Models`: `Category` (self-referencing), `Product`, `ProductImage`, `Address`, `Cart`/`CartItem`, `Order`/`OrderItem`. Storefront and admin Livewire components live under `app/Livewire/Storefront` and `app/Livewire/Admin`, with matching Blade views in `resources/views/livewire`. Shared layouts (`app`, `guest`, `admin`) live in `resources/views/components/layouts`.

The API layer lives alongside the web app and reuses the same models/services: controllers under `app/Http/Controllers/Api/V1`, request validation under `app/Http/Requests/Api/V1`, and response shaping under `app/Http/Resources`. Routes are in `routes/api.php`.

Checkout reserves stock and creates a `Pending` order before handing off to Stripe Checkout; `App\Http\Controllers\StripeWebhookController` marks orders paid (or releases reserved stock on an expired session) via Stripe webhooks.

## Known limitations

This is a demo/portfolio build, not production-ready. Notably missing:

- Real product photography (seeded products use a generated placeholder image showing the product name)
- Tax and shipping cost calculation (columns exist, currently always 0)
- Product variants (size/colour)
- Reviews, ratings, wishlists, coupon codes
- Email verification (Fortify feature is present but disabled)

## License

Built on the [Laravel framework](https://laravel.com), open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
