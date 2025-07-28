# hejunjie/google-authenticator

<div align="center">
  <a href="./README.md">English</a>｜<a href="./README.zh-CN.md">简体中文</a>
  <hr width="50%"/>
</div>

一个用于生成和验证时间基础一次性密码（TOTP）的 PHP 包，支持 Google Authenticator 及类似应用。功能包括密钥生成、二维码创建和 OTP 验证。

**本项目已经经由 Zread 解析完成，如果需要快速了解项目，可以点击此处进行查看：[了解本项目](https://zread.ai/zxc7563598/php-google-authenticator)**

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

[hejunjie/utils](https://github.com/zxc7563598/php-utils) - 一个零碎但实用的 PHP 工具函数集合库。包含文件、字符串、数组、网络请求等常用函数的工具类集合，提升开发效率，适用于日常 PHP 项目辅助功能。

[hejunjie/cache](https://github.com/zxc7563598/php-cache) - 基于装饰器模式实现的多层缓存系统，支持内存、文件、本地与远程缓存组合，提升缓存命中率，简化缓存管理逻辑。

[hejunjie/china-division](https://github.com/zxc7563598/php-china-division) - 定期更新，全国最新省市区划分数据，身份证号码解析地址，支持 Composer 安装与版本控制，适用于表单选项、数据校验、地址解析等场景。

[hejunjie/error-log](https://github.com/zxc7563598/php-error-log) - 基于责任链模式的错误日志处理组件，支持多通道日志处理（如本地文件、远程 API、控制台输出），适用于复杂日志策略场景。

[hejunjie/mobile-locator](https://github.com/zxc7563598/php-mobile-locator) - 基于国内号段规则的手机号码归属地查询库，支持运营商识别与地区定位，适用于注册验证、用户画像、数据归档等场景。

[hejunjie/address-parser](https://github.com/zxc7563598/php-address-parser) - 收货地址智能解析工具，支持从非结构化文本中提取姓名、手机号、身份证号、省市区、详细地址等字段，适用于电商、物流、CRM 等系统。

[hejunjie/url-signer](https://github.com/zxc7563598/php-url-signer) - 用于生成带签名和加密保护的URL链接的PHP工具包，适用于需要保护资源访问的场景

[hejunjie/google-authenticator](https://github.com/zxc7563598/php-google-authenticator) - 一个用于生成和验证时间基础一次性密码（TOTP）的 PHP 包，支持 Google Authenticator 及类似应用。功能包括密钥生成、二维码创建和 OTP 验证。

[hejunjie/simple-rule-engine](https://github.com/zxc7563598/php-simple-rule-engine) - 一个轻量、易用的 PHP 规则引擎，支持多条件组合、动态规则执行，适合业务规则判断、数据校验等场景。

👀 所有包都遵循「轻量实用、解放双手」的原则，能单独用，也能组合用，自由度高，欢迎 star 🌟 或提 issue。

---

该库后续将持续更新，添加更多实用功能。欢迎大家提供建议和反馈，我会根据大家的意见实现新的功能，共同提升开发效率。
