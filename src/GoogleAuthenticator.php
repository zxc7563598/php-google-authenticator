<?php

namespace Hejunjie\GoogleAuthenticator;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Hejunjie\GoogleAuthenticator\Support\Base32;
use Exception;

class GoogleAuthenticator
{


    /**
     * 生成一个 TOTP 密钥
     *
     * @param int $length 密钥长度，默认为 16
     * 
     * @return string 用户的 TOTP 密钥
     */
    public static function generateSecret(int $length = 16): string
    {
        return Base32::base32_encode(random_bytes($length));
    }


    /**
     * 根据密钥生成二维码
     *
     * @param string $issuer 应用名，Google Authenticator 中的显示的名称为「issuer: label」
     * @param string $label 用户名，Google Authenticator 中的显示的名称为「issuer: label」
     * @param string $secret 用户 TOTP 密钥
     * @param string $path 二维码文件存储路径
     * @param int $width 二维码宽度
     * @param string $logo logo文件路径（如果不需要logo给空字符串即可）
     * @param int $logo_width logo宽度（如果logo为空则无效）
     * 
     * @return string|false
     */
    public static function getQRCodeFile(string $issuer, string $label, string $secret, string $path, int $width = 300, string $logo = '', int $logo_width = 50): string|false
    {
        $url = 'otpauth://totp/' . urlencode($issuer . ':' . $label) .
            '?secret=' . $secret .
            '&issuer=' . urlencode($issuer);
        // 生成二维码的 URL
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size($width)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin);
        // 如果有logo
        if (!empty($logo)) {
            $result = $result->logoPath($logo)
                ->logoResizeToWidth($logo_width)
                ->logoPunchoutBackground(true);
        }
        $result = $result->validateResult(false)
            ->build();
        $result->saveToFile($path);
        return realpath($path);
    }

    /**
     * 验证用户输入的 OTP 是否有效
     *
     * @param string $secret 用户的 TOTP 密钥
     * @param string $code 用户输入的 OTP
     * 
     * @return bool 验证是否通过
     */
    public static function checkCode(string $secret, string $code, int $timeWindow = 30): bool
    {
        // 设置时间偏移窗口（一般为 30 秒）
        $timeWindow = 30;
        // 获取当前的 Unix 时间戳，使用时区为 UTC
        $time = floor(time() / $timeWindow);
        // 生成与时间戳相关的 HMAC-SHA1
        $hash = hash_hmac('sha1', pack('N*', 0) . pack('N*', $time), Base32::base32_decode($secret), true);
        // 截取 HMAC-SHA1 的最后一个字节
        $offset = ord(substr($hash, -1)) & 0x0F;
        // 从哈希值中提取动态生成的数字
        $codeGenerated = unpack('N', substr($hash, $offset, 4))[1] & 0x7FFFFFFF;
        // 将生成的数字缩小到 6 位
        $codeGenerated %= 1000000;
        // 比较用户输入的验证码与生成的验证码
        return str_pad($codeGenerated, 6, '0', STR_PAD_LEFT) === $code;
    }
}
