# hejunjie/google-authenticator

`hejunjie/google-authenticator` 是一个简洁的 PHP 包，专门用于生成和验证时间基础一次性密码（TOTP）。它适用于像 Google Authenticator 这样的应用，帮助你轻松实现双因素认证（2FA）。

## 用途 & 初衷

随着安全需求的提升，越来越多的网站和应用开始采用双因素认证（2FA）来保护用户账号。而 Google Authenticator 是最常见的 2FA 应用之一，使用基于时间的一次性密码（TOTP）来确保安全。

在实现 Google Authenticator 的过程中，我发现市面上有很多功能丰富、但较为复杂的 PHP 库。这些库不仅支持 TOTP 认证，还往往包含了用户管理、复杂的配置选项等额外功能，这些功能对于很多开发者来说，可能过于庞大，很多时候我们只需要完成几个简单的任务：

- 生成一个 TOTP 密钥；
- 生成二维码，供用户扫描；
- 验证用户输入的验证码。

因此，我做了这个 **`hejunjie/google-authenticator`** 库，旨在提供一个轻量、简洁的解决方案。如果你只需要快速实现 Google Authenticator 这几个基本功能，它应该会很适合你。

## 安装

通过 Composer 安装：

```bash
composer require hejunjie/google-authenticator
```

## 使用方法

### 1. 为用户生成密钥

> 为用户生成密钥，在验证时需要使用，因此需要为用户保存

```php

use Hejunjie\GoogleAuthenticator\GoogleAuthenticator;

$secret = GoogleAuthenticator::generateSecret();

// 输出 secret (var_dump)
// string(26) "3PVPN3ASEIM457VR5VNUONDQB4"
```

### 2. 根据密钥创建二维码
> 用于 Google Authenticator 扫码
```php

use Hejunjie\GoogleAuthenticator\GoogleAuthenticator;

$issuer = '应用名'; // Google Authenticator 中的显示的名称为「issuer: label」
$label = '用户名'; // Google Authenticator 中的显示的名称为「issuer: label」
$secret = '用户 TOTP 密钥'; // 可自传或者使用 GoogleAuthenticator::generateSecret() 生成的密钥
$path = '/www/wwwroot/xxxxx.png'; // 二维码文件存储路径(带名字)
$width = 300; // [非必传]二维码图片宽度，默认300
$logo = '/www/wwwroot/xxxxx.png'; // [非必传]logo文件路径（如果不需要logo给空字符串即可），默认空字符串
$logo_width = 50; // [非必传]二维码图片宽度如果logo为空则无效），默认50

$getQRCodeFile = GoogleAuthenticator::getQRCodeFile($issuer, $label, $secret, $path, $width, $logo, $logo_width);

// 输出图片路径 (var_dump)
// string(67) "/www/wwwroot/xxxxx.png"
```

### 3. 验证是否有效
```php

use Hejunjie\GoogleAuthenticator\GoogleAuthenticator;

$secret = '用户的密钥';
$code = '用户输入的code';

$checkCode = GoogleAuthenticator::checkCode($secret, $code);

// 输出结果
// bool(false)
```

## 🔧 更多工具包（可独立使用，也可统一安装）

本项目最初是从 [hejunjie/tools](https://github.com/zxc7563598/php-tools) 拆分而来，如果你想一次性安装所有功能组件，也可以使用统一包：

```bash
composer require hejunjie/tools
```

当然你也可以按需选择安装以下功能模块：

[hejunjie/cache](https://github.com/zxc7563598/php-cache) - 多层缓存系统，基于装饰器模式。

[hejunjie/china-division](https://github.com/zxc7563598/php-china-division) - 中国省市区划分数据包。

[hejunjie/error-log](https://github.com/zxc7563598/php-error-log) - 责任链日志上报系统。

[hejunjie/mobile-locator](https://github.com/zxc7563598/php-mobile-locator) - 国内手机号归属地 & 运营商识别。

[hejunjie/utils](https://github.com/zxc7563598/php-utils) - 常用工具方法集合。

[hejunjie/address-parser](https://github.com/zxc7563598/php-address-parser) - 收货地址智能解析工具，支持从非结构化文本中提取用户/地址信息。

[hejunjie/url-signer](https://github.com/zxc7563598/php-url-signer) - URL 签名工具，支持对 URL 进行签名和验证。

👀 所有包都遵循「轻量实用、解放双手」的原则，能单独用，也能组合用，自由度高，欢迎 star 🌟 或提 issue。

---

该库后续将持续更新，添加更多实用功能。欢迎大家提供建议和反馈，我会根据大家的意见实现新的功能，共同提升开发效率。








