<?php

namespace Hejunjie\GoogleAuthenticator\Support;

class Base32
{

    private static $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'; // Base32 编码字符集

    /**
     * Base32 解码
     * 
     * @param mixed $data 用户的 TOTP 密钥
     * 
     * @return string 
     * @throws Exception 
     */
    public static function base32_decode($data)
    {
        $base32 = strtoupper($data);
        $binary = '';
        $buffer = 0;
        $bufferLength = 0;
        foreach (str_split($base32) as $char) {
            $value = strpos(self::$alphabet, $char);
            if ($value === false) {
                throw new \Exception('Invalid character in base32 string.');
            }
            $buffer = ($buffer << 5) | $value;
            $bufferLength += 5;
            while ($bufferLength >= 8) {
                $binary .= chr(($buffer >> ($bufferLength - 8)) & 0xFF);
                $bufferLength -= 8;
            }
        }
        return $binary;
    }

    /**
     * Base32 编码
     * 
     * @param string $data 待编码数据
     * 
     * @return string 
     */
    public static function base32_encode($data)
    {
        $output = '';
        $buffer = 0;
        $bufferLength = 0;
        foreach (str_split($data) as $char) {
            $buffer = ($buffer << 8) | ord($char);
            $bufferLength += 8;
            while ($bufferLength >= 5) {
                $output .= self::$alphabet[($buffer >> ($bufferLength - 5)) & 0x1F];
                $bufferLength -= 5;
            }
        }
        if ($bufferLength > 0) {
            $output .= self::$alphabet[($buffer << (5 - $bufferLength)) & 0x1F];
        }
        return $output;
    }
}
