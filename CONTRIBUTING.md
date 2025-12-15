# Contributing to Circular Protocol PHP SDK

Thank you for your interest in contributing to the Circular Protocol PHP SDK!

## Development Environment

### Prerequisites

- **PHP**: 8.0 or higher
- **Composer**: Latest version
- **PHPUnit**: For testing (installed via Composer)

### Setup

```bash
# Clone repository
git clone https://github.com/circular-protocol/circular-php.git
cd circular-php

# Install dependencies
composer install

# Install development dependencies
composer require --dev phpunit/phpunit
composer require --dev squizlabs/php_codesniffer
composer require --dev phpstan/phpstan
```

### Available Commands

```bash
# Testing
./vendor/bin/phpunit                    # Run all tests
./vendor/bin/phpunit --testdox          # Run with verbose output
./vendor/bin/phpunit --coverage-html coverage  # Generate coverage report

# Code Quality
./vendor/bin/phpcs src/                 # Check code style (PSR-12)
./vendor/bin/phpcbf src/                # Auto-fix code style
./vendor/bin/phpstan analyse src/       # Static analysis

# Package Management
composer dump-autoload                  # Regenerate autoload files
composer validate                       # Validate composer.json
composer audit                          # Check for security vulnerabilities
```

## Testing

### Test Structure

Write tests in the `tests/` directory using PHPUnit:

```php
<?php

namespace CircularProtocol\Api\Tests;

use PHPUnit\Framework\TestCase;
use CircularProtocol\Api\CircularProtocolAPI;

class WalletTest extends TestCase
{
    private CircularProtocolAPI $api;

    protected function setUp(): void
    {
        $this->api = new CircularProtocolAPI();
    }

    public function testCheckWallet(): void
    {
        $blockchain = '0x8a20baa40c45dc5055aeb26197c203e576ef389d9acb171bd62da11dc5ad72b2';
        $address = '0xbd1d7ff426d094605a0902c78812dded6bbebdb42b20d9c722dc87bde0f30f44';
        
        $result = $this->api->checkWallet($blockchain, $address);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('Result', $result);
        $this->assertEquals(200, $result['Result']);
    }
}
```

### Running Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test file
./vendor/bin/phpunit tests/WalletTest.php

# Run specific test method
./vendor/bin/phpunit --filter testCheckWallet

# Run with coverage (requires Xdebug or PCOV)
./vendor/bin/phpunit --coverage-html coverage
```

## Code Style

### PHP Standards Recommendations (PSR)

This project follows:
- **PSR-1**: Basic Coding Standard
- **PSR-4**: Autoloading Standard
- **PSR-12**: Extended Coding Style Guide

### Naming Conventions

```php
<?php

namespace CircularProtocol\Api;  // PascalCase namespace

class CircularProtocolAPI  // PascalCase class names
{
    private string $apiUrl;  // camelCase properties
    
    public function getWallet(string $blockchain, string $address): array  // camelCase methods
    {
        $walletData = [];  // camelCase variables
        // Implementation
        return $walletData;
    }
}
```

### Type Declarations

Always use type declarations (PHP 8.0+):

```php
<?php

// Good: Full type declarations
public function getWallet(string $blockchain, string $address): array
{
    return $this->makeRequest('getWallet', compact('blockchain', 'address'));
}

// Good: Nullable types
public function findTransaction(?string $hash): ?array
{
    if ($hash === null) {
        return null;
    }
    // Implementation
}

// Good: Union types (PHP 8.0+)
public function processResponse(array|object $response): array
{
    // Implementation
}
```

### Docblocks

Use PHPDoc for all public methods:

```php
<?php

/**
 * Retrieve wallet information from the blockchain.
 *
 * @param string $blockchain The blockchain identifier (hex string)
 * @param string $address The wallet address (hex string)
 * @return array The API response containing wallet data
 * @throws \InvalidArgumentException If parameters are invalid
 */
public function getWallet(string $blockchain, string $address): array
{
    // Implementation
}
```

### Code Style Checking

Use PHP_CodeSniffer to enforce PSR-12:

```bash
# Check code style
./vendor/bin/phpcs src/

# Auto-fix issues
./vendor/bin/phpcbf src/

# Custom standard
./vendor/bin/phpcs --standard=PSR12 src/
```

### Static Analysis

Use PHPStan for type safety:

```bash
# Run static analysis
./vendor/bin/phpstan analyse src/

# Strict level
./vendor/bin/phpstan analyse --level=max src/
```

## Pull Request Process

### Before Submitting

1. **Create an issue** describing the bug or feature
2. **Fork the repository** and create a branch from `main`
3. **Write tests** for your changes
4. **Ensure all tests pass**: `./vendor/bin/phpunit`
5. **Check code style**: `./vendor/bin/phpcs src/`
6. **Run static analysis**: `./vendor/bin/phpstan analyse src/`
7. **Regenerate autoload**: `composer dump-autoload`

### PR Checklist

- [ ] All tests pass (`./vendor/bin/phpunit`)
- [ ] Code follows PSR-12 (`./vendor/bin/phpcs`)
- [ ] PHPStan analysis passes
- [ ] Type declarations added for all methods
- [ ] Docblocks added for public methods
- [ ] Autoload files regenerated
- [ ] CHANGELOG.md updated (for user-facing changes)
- [ ] README.md updated (if API changes)
- [ ] Commit messages are descriptive

### Commit Message Format

Use clear, descriptive commit messages:

```
Add pagination support for transaction history

- Implement limit and offset parameters
- Add tests for pagination edge cases
- Update documentation with pagination examples
```

For conventional commits:

```
feat: add wallet history pagination support
fix: correct signature verification for multi-sig
docs: update README with new contract examples
test: add integration tests for NAG API
chore: update dependencies to latest versions
```

## Namespace and Autoloading

### PSR-4 Namespace Structure

The namespace `CircularProtocol\Api\` maps to the `src/` directory:

```
src/
├── CircularProtocolAPI.php  → CircularProtocol\Api\CircularProtocolAPI
├── Utils/
│   └── Validator.php        → CircularProtocol\Api\Utils\Validator
└── Contracts/
    └── CallBuilder.php      → CircularProtocol\Api\Contracts\CallBuilder
```

### Adding New Classes

When adding new classes:

1. Place in appropriate directory under `src/`
2. Use correct namespace
3. Regenerate autoload: `composer dump-autoload`

```php
<?php

namespace CircularProtocol\Api\Utils;

class Validator
{
    public static function isValidAddress(string $address): bool
    {
        // Implementation
    }
}
```

## Backwards Compatibility

### Breaking Changes NOT Allowed (without major version bump)

- Changing method signatures
- Removing public methods or classes
- Changing namespace
- Changing response structure

### Deprecation Process

If you need to deprecate a feature:

1. Add `@deprecated` tag in docblock
2. Trigger `E_USER_DEPRECATED` error
3. Update documentation
4. Keep deprecated feature for at least one minor version

```php
<?php

/**
 * Old method for getting wallet data.
 *
 * @deprecated Use getWalletInfo() instead
 * @param string $address
 * @return array
 */
public function getWalletData(string $address): array
{
    trigger_error(
        'getWalletData() is deprecated, use getWalletInfo() instead',
        E_USER_DEPRECATED
    );
    return $this->getWalletInfo($address);
}
```

## Version Management

- **Version location**: `composer.json`
- **Versioning scheme**: [Semantic Versioning](https://semver.org/)
  - MAJOR: Breaking changes
  - MINOR: New features (backwards compatible)
  - PATCH: Bug fixes

## Release Process

Releases are managed by maintainers:

1. Update version in `composer.json`
2. Update `CHANGELOG.md`
3. Create git tag: `git tag -a v1.0.x -m "Release v1.0.x"`
4. Push tag: `git push origin v1.0.x`
5. Publish to Packagist (automatic via GitHub webhook)

## Development Best Practices

### Error Handling

```php
<?php

// Good: Specific exceptions
if (!$this->isValidAddress($address)) {
    throw new \InvalidArgumentException("Invalid address format: {$address}");
}

// Good: Try-catch for external calls
try {
    $response = $this->httpClient->get($url);
} catch (\Exception $e) {
    throw new \RuntimeException("API request failed: " . $e->getMessage(), 0, $e);
}

// Bad: Silent failures
try {
    $result = $this->doSomething();
} catch (\Exception $e) {
    // Nothing - error swallowed
}
```

### Security

```php
<?php

// Good: Use hash_equals for timing-safe comparison
if (!hash_equals($expectedSignature, $actualSignature)) {
    throw new \Exception('Invalid signature');
}

// Bad: Regular comparison (timing attack vulnerable)
if ($expectedSignature !== $actualSignature) {
    throw new \Exception('Invalid signature');
}

// Good: Validate and sanitize input
$cleanAddress = filter_var($address, FILTER_SANITIZE_STRING);
if (!$this->validator->isValidAddress($cleanAddress)) {
    throw new \InvalidArgumentException('Invalid address');
}
```

### Dependencies

- Keep dependencies minimal
- Use specific version constraints in `composer.json`
- Run `composer audit` regularly

```json
{
    "require": {
        "php": ">=8.0",
        "simplito/elliptic-php": "^1.0.12"
    }
}
```

## Documentation

### API Documentation

Generate API documentation using phpDocumentor:

```bash
# Install phpDocumentor
composer require --dev phpdocumentor/phpdocumentor

# Generate documentation
./vendor/bin/phpdoc -d src/ -t docs/
```

### README Updates

Update README.md when:
- Adding new public methods
- Changing usage examples
- Updating installation instructions

## Questions?

- **Bug reports**: File an issue with reproduction steps
- **Feature requests**: Open an issue for discussion first
- **Questions**: Use GitHub Discussions
- **Security**: Email security@circularlabs.io

## Code of Conduct

Be respectful, inclusive, and professional. We're all here to build great software together.

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

**Last Updated**: 2025-12-13
