# hejunjie/google-authenticator

English ｜ [简体中文](./README.zh-CN.md)

A lightweight PHP TOTP authentication library for generating secrets, creating QR codes, and verifying Google Authenticator-compatible one-time passwords.

> 🔗 Quickly understand this project's structure and code logic via [Zread](https://zread.ai/zxc7563598/php-google-authenticator).

[![Packagist Version](https://img.shields.io/packagist/v/hejunjie/google-authenticator)](https://packagist.org/packages/hejunjie/google-authenticator)
[![PHP Version](https://img.shields.io/packagist/php-v/hejunjie/google-authenticator)](https://packagist.org/packages/hejunjie/google-authenticator)
[![License](https://img.shields.io/packagist/l/hejunjie/google-authenticator)](LICENSE)

## Features

- 🔑 Generate secure Base32-encoded TOTP secrets
- 📱 Create QR code images with customizable size and logo
- ✅ Verify 6-digit OTP codes entered by users
- 🪶 Only depends on `endroid/qr-code`, no other unnecessary dependencies
- 🐘 Requires PHP >= 8.1, with strict typing support

## Requirements

- PHP >= 8.1
- Composer

## Installation

```bash
composer require hejunjie/google-authenticator
```

## Quick Start

### Generate a Secret

Generate a unique TOTP secret for each user. It is recommended to encrypt it before storing in the database.

```php
use Hejunjie\GoogleAuthenticator\GoogleAuthenticator;

$secret = GoogleAuthenticator::generateSecret();
// => "3PVPN3ASEIM457VR5VNUONDQB4"
```

### Generate a QR Code

Generate a QR code image that users can scan with Google Authenticator to add the account.

```php
use Hejunjie\GoogleAuthenticator\GoogleAuthenticator;

$issuer = 'MyApp';                   // Application name
$label  = 'user@example.com';        // User identifier
$secret = '3PVPN3ASEIM457VR5VNUONDQB4';

// Basic usage (with default parameters)
GoogleAuthenticator::getQRCodeFile($issuer, $label, $secret, '/path/to/qrcode.png');

// Custom size and logo
GoogleAuthenticator::getQRCodeFile(
    issuer: 'MyApp',
    label: 'user@example.com',
    secret: $secret,
    path: '/path/to/qrcode.png',
    width: 400,
    logo: '/path/to/logo.png',
    logo_width: 60
);
```

| Parameter | Description | Default |
|-----------|-------------|---------|
| `$issuer` | Application name, displayed as "issuer: label" in Authenticator | Required |
| `$label` | User identifier, displayed as "issuer: label" in Authenticator | Required |
| `$secret` | TOTP secret | Required |
| `$path` | File path for saving the QR code image (including filename) | Required |
| `$width` | Image width in pixels | `300` |
| `$logo` | Logo file path, pass an empty string to disable | `''` |
| `$logo_width` | Logo width in pixels, ignored when `$logo` is empty | `50` |

### Verify OTP

Verify the 6-digit code entered by the user.

```php
use Hejunjie\GoogleAuthenticator\GoogleAuthenticator;

$secret = '3PVPN3ASEIM457VR5VNUONDQB4';
$code   = '123456';

if (GoogleAuthenticator::checkCode($secret, $code)) {
    echo 'Verification passed';
} else {
    echo 'Verification failed';
}
```

The third optional parameter `$timeWindow` controls the time step (default 30 seconds), matching the Google Authenticator default setting. It generally does not need to be changed.

## Notes

- **Clock synchronization**: TOTP relies on server time. Ensure NTP time synchronization is configured. The current implementation only verifies the current time window — a significant time skew between the server and user device may cause verification failures.
- **Secret security**: The secret is equivalent to a password. Store it securely (e.g., encrypted in the database). If leaked, an attacker can directly generate valid OTPs.
- **File permissions**: When generating QR codes, ensure the target directory exists and PHP has write permissions.

## Project Structure

```
src/
├── GoogleAuthenticator.php    # Main class: secret generation, QR code creation, OTP verification
└── Support/
    └── Base32.php             # Base32 encoding/decoding
```
