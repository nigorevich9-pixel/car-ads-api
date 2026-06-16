# Tests

## Unit

Run:

```bash
composer test
```

Covered scenarios:

- Create a car advertisement when `options` is absent.
- Create a car advertisement when `options` is `null`.
- Create a car advertisement with full `options`.
- Reject a create request with incomplete `options`.

## Manual API Check

After dependencies are installed and migrations are applied:

```bash
php yii serve --port=8080
```

Then call the endpoints from `README.md`.
