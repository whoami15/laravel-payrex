# Contributing

Contributions are welcome and will be fully credited.

## Bug Reports

When filing a bug report, please include:

- A clear title and description
- Steps to reproduce the issue
- Expected vs actual behavior
- Your PHP, Laravel, and package version

## Pull Requests

- **Open an issue first** for significant changes to discuss the approach
- **One pull request per feature** — if you want to do more than one thing, send multiple pull requests
- **Add tests** — your patch won't be accepted if it doesn't have tests
- **Follow the code style** — run `composer format` to apply code style fixes
- **Run static analysis** — run `composer analyse` to ensure no PHPStan errors
- **Document any changes** — update the README or docs if your PR changes behavior

## Development Setup

1. Fork and clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Run the test suite:
   ```bash
   composer test
   ```
4. Run static analysis:
   ```bash
   composer analyse
   ```
5. Fix code style:
   ```bash
   composer format
   ```

## Code Style

We use [Laravel Pint](https://laravel.com/docs/pint) with the default Laravel preset. Run `composer format` before committing.

All source files use `declare(strict_types=1)`.
