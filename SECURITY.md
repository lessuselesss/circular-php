# Security Policy

## Supported Versions

We release patches for security vulnerabilities in the following versions:

| Version | Supported          |
| ------- | ------------------ |
| Latest  | :white_check_mark: |
| < Latest| :x:                |

## Reporting a Vulnerability

We take the security of the Circular Protocol PHP SDK seriously. If you believe you have found a security vulnerability, please report it to us as described below.

### Where to Report

**Please do NOT report security vulnerabilities through public GitHub issues.**

Instead, please report them via email to:
- **Email**: security@circularlabs.io

If you prefer encrypted communication, please request our PGP key.

### What to Include

Please include the following information in your report:

- **Type of vulnerability** (e.g., cryptographic weakness, input validation issue, etc.)
- **Full paths of source file(s)** related to the manifestation of the vulnerability
- **Location of the affected source code** (tag/branch/commit or direct URL)
- **Step-by-step instructions** to reproduce the issue
- **Proof-of-concept or exploit code** (if possible)
- **Impact of the vulnerability**, including how an attacker might exploit it
- **Your contact information** for follow-up questions

### Response Timeline

- **Initial Response**: Within 48 hours of report submission
- **Vulnerability Assessment**: Within 5 business days
- **Fix Timeline**: Depends on severity and complexity
  - Critical: Within 7 days
  - High: Within 14 days
  - Medium: Within 30 days
  - Low: Next scheduled release

### Security Update Process

1. **Confirmation**: We confirm the vulnerability and determine its severity
2. **Fix Development**: We develop a fix in a private repository
3. **Testing**: Thorough testing of the fix
4. **Release**: Security patch released with credit to reporter (unless anonymity requested)
5. **Disclosure**: Public disclosure after patch is available

### Security Best Practices

When using the Circular Protocol PHP SDK:

#### Private Key Management

**NEVER** store private keys in:
- Source code or version control
- Environment variables in public repositories
- Client-side code or frontend applications
- Log files or error messages
- Configuration files committed to git
- Session data or cookies

**DO** store private keys in:
- Secure key management systems (AWS Secrets Manager, HashiCorp Vault, etc.)
- Hardware security modules (HSMs)
- Encrypted environment variables (with restricted access)
- Secure secrets management services
- PHP-FPM environment variables (not accessible to web tier)

```php
<?php
// ❌ NEVER DO THIS
$privateKey = 'c87509a1c067bbde78beb793e6fa76530b6382a4c0241e5e4a9ec0a0f44dc0d3';

// ✅ DO THIS
$privateKey = getenv('WALLET_PRIVATE_KEY');
if (!$privateKey) {
    throw new \RuntimeException('Private key not configured');
}

// ✅ EVEN BETTER - Use a dedicated secrets manager
// $privateKey = $secretsManager->get('WALLET_PRIVATE_KEY');
```

#### Input Validation

Always validate and sanitize inputs:

```php
<?php
namespace CircularProtocol\Api;

class Validator
{
    /**
     * Validate a Circular Protocol address.
     */
    public static function isValidAddress(string $address): bool
    {
        // Remove 0x prefix if present
        $cleanAddress = str_replace('0x', '', $address);
        return (bool) preg_match('/^[a-fA-F0-9]{64}$/', $cleanAddress);
    }

    /**
     * Validate blockchain identifier.
     */
    public static function isValidBlockchain(string $blockchain): bool
    {
        $validBlockchains = [
            'Circular Main Public',
            'Circular Secondary Public',
            'Circular Documark Public',
            'Circular SandBox'
        ];
        return in_array($blockchain, $validBlockchains, true);
    }
}

// Usage
if (!Validator::isValidAddress($address)) {
    throw new \InvalidArgumentException('Invalid address format');
}
```

#### Transaction Verification

Always verify transaction parameters before signing:

```php
<?php
// Verify transaction before signing
$nonce = $api->getWalletNonce($blockchain, $fromAddress);
error_log("Transaction Details:");
error_log("From: {$fromAddress}");
error_log("To: {$toAddress}");
error_log("Amount: {$amount}");
error_log("Nonce: {$nonce}");

// Confirm before proceeding
$signature = $api->signMessage($transactionHash, $privateKey);
```

#### Network Security

- **Use HTTPS**: Always use HTTPS endpoints for NAG communication
- **Verify SSL Certificates**: Enable SSL verification in cURL/HTTP clients
- **Rate Limiting**: Implement server-side rate limiting
- **Timeout Handling**: Set appropriate timeouts for API calls

```php
<?php
// ✅ Good - uses HTTPS with proper SSL verification
$api = new \CircularProtocol\Api\CircularProtocolAPI(
    'https://nag.circularlabs.io/NAG.php?cep='
);

// Configure cURL for security
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

// ❌ Never do this
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disables SSL verification!
```

#### Dependency Security

- Keep dependencies up to date using Composer
- Regularly check for vulnerabilities
- Review dependency changes before updating
- Use `composer.lock` to ensure consistent environments

```bash
# Check for known security vulnerabilities
composer audit

# Update dependencies
composer update

# Check for outdated packages
composer outdated
```

#### Error Handling

Never expose sensitive information in error messages:

```php
<?php
try {
    $result = $api->callContract($params);
} catch (\Exception $e) {
    // ❌ DON'T expose internal details
    // throw new \Exception("Failed with key: {$privateKey}");
    
    // ✅ DO log securely and show safe message
    error_log('Contract call failed: ' . $e->getMessage());
    throw new \Exception('Transaction failed. Please try again.');
}
```

## Known Security Considerations

### Cryptographic Operations

This SDK uses:
- **secp256k1** elliptic curve (same as Bitcoin/Ethereum)
- **ECDSA** for digital signatures via `simplito/elliptic-php`

These are industry-standard cryptographic primitives. However:

- **Randomness**: Ensure your environment has sufficient entropy for key generation
- **Side-channel attacks**: Private keys should never be exposed to untrusted code
- **Timing attacks**: Use constant-time comparison for sensitive data

```php
<?php
// ✅ Use hash_equals for timing-safe comparison
if (!hash_equals($expectedSignature, $actualSignature)) {
    throw new \Exception('Invalid signature');
}

// ❌ DON'T use regular comparison for secrets
// if ($expectedSignature !== $actualSignature) { ... }
```

### Server-Side Usage Only

**⚠️ This SDK is designed for server-side use**

Using this SDK in client-facing PHP code exposes:
- Private keys to potential attacks
- Transaction signing logic to inspection
- Server-side secrets to public view

Best practices:
- Implement proper API authentication
- Never expose private keys to the frontend
- Use server-side session management
- Implement CSRF protection

### PHP Version Compatibility

- **Minimum PHP 8.0** is required
- Keep PHP updated to receive security patches
- Disable dangerous PHP functions in `php.ini`:

```ini
disable_functions = exec,passthru,shell_exec,system,proc_open,popen
```

### Composer Autoloading

- Use PSR-4 autoloading (already configured)
- Never include files from user input
- Regenerate autoload files after structural changes:

```bash
composer dump-autoload --optimize
```

### Common PHP Vulnerabilities to Avoid

1. **SQL Injection**: Use prepared statements (not applicable to this SDK, but good practice)
2. **XSS**: Sanitize output if displaying transaction data
3. **CSRF**: Implement CSRF tokens for transaction submissions
4. **File Inclusion**: Never include files based on user input
5. **Deserialization**: Be cautious with `unserialize()` on untrusted data

## Security Advisories

Security advisories will be published at:
- [GitHub Security Advisories](https://github.com/circular-protocol/circular-php/security/advisories)
- Release notes with `[SECURITY]` tag
- CHANGELOG.md with security section

## Bug Bounty Program

Currently, we do not have a formal bug bounty program. However:
- Significant vulnerabilities may be eligible for recognition
- Contributors will be credited in release notes (unless anonymity requested)
- We appreciate responsible disclosure

## Contact

For security concerns or questions:
- **Security Email**: security@circularlabs.io
- **General Support**: support@circularlabs.io
- **GitHub Issues**: For non-security bugs only

## Acknowledgments

We would like to thank the following individuals for responsibly disclosing security vulnerabilities:

*No vulnerabilities reported yet*

---

**Last Updated**: 2025-12-13
