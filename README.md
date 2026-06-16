# Car Ads API

REST API service for car advertisements built with PHP 8, Yii2 and PostgreSQL.

## Requirements

- PHP 8.0+
- Composer
- PostgreSQL

## Installation

```bash
git clone <repository-url>
cd car-ads-api
composer install
```

Configure PostgreSQL through environment variables:

```bash
export DB_DSN="pgsql:host=127.0.0.1;port=5432;dbname=car_ads"
export DB_USER="car_ads"
export DB_PASSWORD="car_ads"
export COOKIE_VALIDATION_KEY="change-me"
```

Apply migrations:

```bash
php yii migrate/up
```

Start the app:

```bash
php yii serve --port=8080
```

## Docker

Docker is optional. It is useful for a local PostgreSQL and PHP runtime:

```bash
docker compose up -d
docker compose exec php composer install
docker compose exec php php yii migrate/up
```

The API will be available at `http://localhost:8080`.

## API

### POST `/car/create`

Creates a car advertisement.

```bash
curl -X POST http://localhost:8080/car/create \
  -H "Content-Type: application/json" \
  -d '{
    "title": "BMW 320",
    "description": "Good condition",
    "price": 22000,
    "photo_url": "https://example.com/bmw.jpg",
    "contacts": "+995555222222",
    "options": {
      "brand": "BMW",
      "model": "320",
      "year": 2020,
      "body": "sedan",
      "mileage": 45000
    }
  }'
```

Successful response: `201 Created`.

`options` can be omitted, set to `null`, or passed as a full object with `brand`, `model`, `year`, `body`, `mileage`.

### GET `/car/{id}`

Returns one advertisement.

```bash
curl http://localhost:8080/car/1
```

If the advertisement does not exist, the API returns `404`.

### GET `/car/list?page=N`

Returns a paginated list. Default page is `1`, page size is `20`.

```bash
curl "http://localhost:8080/car/list?page=1"
```

## Response Format

Successful responses include advertisement fields and `options`.

```json
{
  "id": 1,
  "title": "BMW 320",
  "description": "Good condition",
  "price": 22000,
  "photo_url": "https://example.com/bmw.jpg",
  "contacts": "+995555222222",
  "created_at": "2026-06-16 14:20:00",
  "options": {
    "brand": "BMW",
    "model": "320",
    "year": 2020,
    "body": "sedan",
    "mileage": 45000
  }
}
```

When technical characteristics are absent, `options` is `null`.

Validation errors return `422 Unprocessable Entity`:

```json
{
  "errors": {
    "options": [
      "Option field 'model' is required."
    ]
  }
}
```

## Tests

```bash
composer test
```

The unit-test covers `CarService::createCar` with absent, null, full and incomplete `options`.

## Architecture

The request flow is intentionally simple:

`CarController -> CreateCarRequest -> CarService -> CarRepository -> CarDataMapper -> PostgreSQL`

- Controller handles HTTP input, status codes and JSON output.
- `CreateCarRequest` validates incoming JSON and keeps validation out of the controller.
- `CarService` contains the application scenario for creating and reading advertisements.
- `CarRepositoryInterface` lets the service depend on storage abstraction.
- `CarRepository` persists `car` and `car_option` in one transaction.
- `CarDataMapper` converts ActiveRecord models to domain entities and response arrays.
- Entity classes do not depend on Yii or HTTP.

The database has `UNIQUE(car_id)` on `car_option` because one advertisement can have at most one set of technical characteristics.
