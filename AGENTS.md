# AGENTS Guidelines for This Repository

This repository contains the PHP SDK for the Circular Protocol API. When working on the project interactively with an agent (e.g., Codex CLI, Gemini CLI, Cursor, Claude Code, Open Code or other AI coding assistants), please follow the guidelines below to ensure a smooth development experience.

## 1. Development Environment Setup

* **Ensure PHP 8.0 or higher** is installed:
  ```bash
  php --version
  ```

* **Install dependencies** using Composer:
  ```bash
  composer install
  ```

* **Autoloading** - This project uses PSR-4 autoloading with the namespace `CircularProtocol\Api\` mapped to the `src/` directory.

## 2. Code Quality and Standards

* **Follow PSR standards**:
  - PSR-1: Basic Coding Standard
  - PSR-4: Autoloading Standard
  - PSR-12: Extended Coding Style Guide

* **Use type declarations** where possible (parameter types and return types).

* **Write PHPDoc comments** for all classes, methods, and complex functions:
  ```php
  /**
   * Brief description of the method
   *
   * @param string $param Description
   * @return array Description
   */
  ```

* **Test your changes** before committing - Write unit tests for new functionality.

## 3. Project Structure

* `src/` - Main SDK implementation (PSR-4 autoloaded under `CircularProtocol\Api\`)
* `lib/` - Additional library files
* `vendor/` - Composer dependencies (not committed to git)
* `composer.json` - Package configuration and dependencies

## 4. Testing and Verification

* **Install PHPUnit** if not already available:
  ```bash
  composer require --dev phpunit/phpunit
  ```

* **Run tests** (if configured):
  ```bash
  ./vendor/bin/phpunit
  # or
  composer test
  ```

* **Verify autoloading** after structural changes:
  ```bash
  composer dump-autoload
  ```

## 5. Dependencies

Current dependencies:
- `simplito/elliptic-php` (^1.0.12) - Elliptic curve cryptography for PHP

## 6. Useful Commands Recap

| Command                        | Purpose                                              |
| ------------------------------ | ---------------------------------------------------- |
| `composer install`             | Install all dependencies                             |
| `composer update`              | Update dependencies to latest compatible versions    |
| `composer dump-autoload`       | Regenerate autoload files                            |
| `composer require <package>`   | Add a new dependency                                 |
| `php -l <file.php>`            | Check PHP syntax of a file                           |
| `./vendor/bin/phpunit`         | Run PHPUnit tests (if configured)                    |

## 7. Code Style Tools (Optional but Recommended)

Consider using these tools to maintain code quality:

* **PHP_CodeSniffer** - Check code against PSR standards:
  ```bash
  composer require --dev squizlabs/php_codesniffer
  ./vendor/bin/phpcs src/
  ```

* **PHPStan** - Static analysis:
  ```bash
  composer require --dev phpstan/phpstan
  ./vendor/bin/phpstan analyse src/
  ```

## 8. API Documentation

* Refer to the [Circular Protocol Documentation](https://circular-protocol.gitbook.io/standard-apis) for API endpoints and usage patterns.
* Keep README.md examples up to date with any API changes.
* Include usage examples that demonstrate the PSR-4 namespace structure.

## 9. Namespace Usage

When adding new classes, ensure they follow the PSR-4 structure:

```php
<?php

namespace CircularProtocol\Api;

class YourNewClass
{
    // Implementation
}
```

---

Following these practices ensures that agent-assisted development remains efficient and maintains code quality. Always regenerate autoload files after structural changes to avoid class loading issues.
