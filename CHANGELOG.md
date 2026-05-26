# Changelog

All notable changes to [desino/crud-engine](https://packagist.org/packages/desino/crud-engine) are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.2] - 2026-05-19

### Added

- `CHANGELOG.md` — release history for Packagist and GitHub releases.

## [1.0.1] - 2026-05-21

### Added

- `README.md` — installation, usage, generated artifacts, route reference, and Packagist publishing notes.
- `composer.json` — `keywords`, `homepage`, and `support` (issues/source) for Packagist discovery.

### Fixed

- **Stub placeholders** — corrected order of `getNamesPlaceholders()` / `getNamesPlaceholderValues()` so `{{snakeCaseSingularName}}`, `{{camelCaseSingularName}}`, and headline name tokens resolve to the right values in generated code.
- **Generated routes** — create and edit no longer shared the list URL:
  - Create: `{entity}/create` (was `{entity}` for all actions).
  - Edit: `{entity}/{id}/edit` (was `{entity}`).

### Changed

- `composer.json` description updated for Packagist.

## [1.0.0] - 2026-05-20

### Added

- Initial release: `php artisan make:crud-engine {name}` scaffold generator.
- Generates model, controller, migration, Blade views (`index`, `create`, `edit`), web routes, and translation keys.
- Desino conventions: `AppMiscService`, `layouts.app`, `lang/en/messages.php`, auth middleware in controller.
- List filters (status + keyword), create/edit with validation, activate/deactivate, delete with modals.
- PHP `^8.1` and Laravel `^10` / `^11` / `^12` support in `composer.json`.
- Laravel package auto-discovery for `CrudEngineServiceProvider`.

[1.0.2]: https://github.com/desino/CrudEngine/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/desino/CrudEngine/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/desino/CrudEngine/releases/tag/v1.0.0
