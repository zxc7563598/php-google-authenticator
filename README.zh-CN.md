# hejunjie/google-authenticator

[English](./README.md) ｜ 简体中文

一个轻量的 PHP TOTP 认证库，用于生成密钥、创建二维码和验证 Google Authenticator 兼容的一次性密码。

> 🔗 通过 [Zread](https://zread.ai/zxc7563598/php-google-authenticator) 快速了解本项目结构和代码逻辑。

[![Packagist Version](https://img.shields.io/packagist/v/hejunjie/google-authenticator)](https://packagist.org/packages/hejunjie/google-authenticator)
[![PHP Version](https://img.shields.io/packagist/php-v/hejunjie/google-authenticator)](https://packagist.org/packages/hejunjie/google-authenticator)
[![License](https://img.shields.io/packagist/l/hejunjie/google-authenticator)](LICENSE)

## 特性

- 🔑 生成安全的 Base32 编码 TOTP 密钥
- 📱 生成二维码图片，支持自定义尺寸和 Logo
- ✅ 验证用户输入的 6 位 OTP 验证码
- 🪶 仅依赖 `endroid/qr-code`，无其他冗余依赖
- 🐘 要求 PHP >= 8.1，支持严格类型

## 环境要求

- PHP >= 8.1
- Composer

## 安装

```bash
composer require hejunjie/google-authenticator
```

## 快速开始

### 生成密钥

为每个用户生成一个唯一的 TOTP 密钥，建议加密后存入数据库。

```php
use Hejunjie\GoogleAuthenticator\GoogleAuthenticator;

$secret = GoogleAuthenticator::generateSecret();
// => "3PVPN3ASEIM457VR5VNUONDQB4"
```

### 生成二维码

生成二维码图片，用户使用 Google Authenticator 扫码即可自动添加账号。

```php
use Hejunjie\GoogleAuthenticator\GoogleAuthenticator;

$issuer = 'MyApp';                   // 应用名称
$label  = 'user@example.com';        // 用户标识
$secret = '3PVPN3ASEIM457VR5VNUONDQB4';

// 基础用法（使用默认参数）
GoogleAuthenticator::getQRCodeFile($issuer, $label, $secret, '/path/to/qrcode.png');

// 自定义尺寸与 Logo
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

| 参数 | 说明 | 默认值 |
|------|------|--------|
| `$issuer` | 应用名称，在 Authenticator 中显示为「issuer: label」 | 必填 |
| `$label` | 用户标识，在 Authenticator 中显示为「issuer: label」 | 必填 |
| `$secret` | TOTP 密钥 | 必填 |
| `$path` | 二维码图片保存路径（含文件名） | 必填 |
| `$width` | 图片宽度（像素） | `300` |
| `$logo` | Logo 文件路径，传空字符串表示不使用 | `''` |
| `$logo_width` | Logo 宽度（像素），`$logo` 为空时无效 | `50` |

### 验证 OTP

验证用户输入的 6 位验证码。

```php
use Hejunjie\GoogleAuthenticator\GoogleAuthenticator;

$secret = '3PVPN3ASEIM457VR5VNUONDQB4';
$code   = '123456';

if (GoogleAuthenticator::checkCode($secret, $code)) {
    echo '验证通过';
} else {
    echo '验证失败';
}
```

第三个可选参数 `$timeWindow` 控制时间步长（默认 30 秒），与 Google Authenticator 默认配置一致，一般无需修改。

## 注意事项

- **时钟同步**：TOTP 依赖服务器时间，请确保已配置 NTP 时间同步。当前实现仅验证当前时间窗口，服务器与用户设备若存在较大时间偏差可能导致验证失败。
- **密钥安全**：密钥等同于密码，需安全存储（如加密后存入数据库），泄露后攻击者可直接生成有效 OTP。
- **文件权限**：生成二维码时，确保目标目录存在且 PHP 有写入权限。

## 项目结构

```
src/
├── GoogleAuthenticator.php    # 主类：密钥生成、二维码创建、OTP 验证
└── Support/
    └── Base32.php             # Base32 编解码
```
