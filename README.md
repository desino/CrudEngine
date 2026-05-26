# desino/crud-engine

[![Latest Version on Packagist](https://img.shields.io/packagist/v/desino/crud-engine.svg?style=flat-square)](https://packagist.org/packages/desino/crud-engine)
[![Total Downloads](https://img.shields.io/packagist/dt/desino/crud-engine.svg?style=flat-square)](https://packagist.org/packages/desino/crud-engine)
[![License](https://img.shields.io/packagist/l/desino/crud-engine.svg?style=flat-square)](https://packagist.org/packages/desino/crud-engine)
[![PHP Version](https://img.shields.io/packagist/php-v/desino/crud-engine.svg?style=flat-square)](https://packagist.org/packages/desino/crud-engine)

**Laravel Artisan scaffold generator** for Desino admin CRUD modules. One command generates the model, controller, migration, Blade views, web routes, and translation keys in your host application.

## Features

- **Artisan command** — `php artisan make:crud-engine {Entity}`
- **Full scaffold** — model, controller, migration, views (`index`, `create`, `edit`)
- **Admin UX** — list with status filters and keyword search, create/edit forms, activate/deactivate, delete with confirmation modals
- **Conventions** — matches Desino patterns (`AppMiscService`, `layouts.app`, `lang/en/messages.php`)
- **Safe re-runs** — skips overwriting existing files unless `--force` is used; skips duplicate route blocks marked in `routes/web.php`
- **Auto-discovery** — service provider registered via Laravel package discovery

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP | `^8.1` |
| Laravel | `^10.0`, `^11.0`, or `^12.0` |

Your Laravel application must already include:

- `App\Services\AppMiscService` — flash messages and validation message helpers
- Blade layout `layouts.app`
- Translation file `lang/en/messages.php` with shared keys (e.g. `general_not_allowed_access_message_text`, `general_no_results_text`)
- Session-based authentication (`\Auth::user()`)

## Installation

Install via Composer:

```bash
composer require desino/crud-engine
```

Laravel discovers the package automatically. Confirm the command is available:

```bash
php artisan list | findstr crud-engine
```

On Linux/macOS:

```bash
php artisan list | grep crud-engine
```

## Usage

Generate CRUD for an entity (use singular or plural; the command normalizes names):

```bash
php artisan make:crud-engine Category
php artisan make:crud-engine Product --force
```

Example output:

```
CRUD scaffold for Category is ready.
Model:      app/Models/Category.php
Controller: app/Http/Controllers/CategoryController.php
Views:      resources/views/categories/
Routes:     appended to routes/web.php
```

Run migrations after generation:

```bash
php artisan migrate
```

### Generated artifacts

| Artifact | Path (example: `Category`) |
|----------|----------------------------|
| Model | `app/Models/Category.php` |
| Controller | `app/Http/Controllers/CategoryController.php` |
| Migration | `database/migrations/*_create_categories_table.php` |
| Views | `resources/views/categories/index.blade.php`, `create.blade.php`, `edit.blade.php` |
| Routes | Appended to `routes/web.php` |
| Translations | New keys merged into `lang/en/messages.php` |

### Generated behaviour

- **Index** — paginated list, status checkboxes, keyword search (session-backed filters)
- **Create / edit** — validated `name` field (unique, max 55 characters)
- **Activate / deactivate** — toggles `status` with audit fields (`updated_at`, `updated_by`)
- **Delete** — hard delete with confirmation modal

Default table columns: `id`, `name`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`.

## Route names

For an entity `Category`, the generator appends routes under the URI prefix `/categories` (snake-case plural). Route **names** follow camelCase plural:

| Route name | Controller action | Methods |
|------------|-------------------|---------|
| `categories.index` | `index` | GET, POST |
| `categories.create` | `create` | GET, POST (`/categories/create`) |
| `categories.edit` | `edit` | GET, POST (`/categories/{id}/edit`) |
| `categories.activate` | `activate` | POST |
| `categories.deactivate` | `deactivate` | POST |
| `categories.delete` | `delete` | POST |

Activate, deactivate, and delete use dedicated POST endpoints:

- `POST /categories/activate`
- `POST /categories/deactivate`
- `POST /categories/delete`

Re-running the command does not duplicate routes if `routes/web.php` already contains the marker:

```php
// Routers :categories
```

## Publishing to Packagist

1. Tag a release in your Git repository (e.g. `v1.0.0`).
2. Submit the repository URL on [packagist.org](https://packagist.org/packages/submit).
3. Enable the Packagist webhook (or use Packagist’s GitHub integration) so new tags update the package automatically.

Ensure `composer.json` includes valid `name`, `description`, `license`, and `authors` before submitting.

## Support

- **Issues:** [github.com/desino/CrudEngine/issues](https://github.com/desino/CrudEngine/issues)
- **Source:** [github.com/desino/CrudEngine](https://github.com/desino/CrudEngine)
- **Email:** india@desino.be

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for release history.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Author

**Desino** — [india@desino.be](mailto:india@desino.be)
