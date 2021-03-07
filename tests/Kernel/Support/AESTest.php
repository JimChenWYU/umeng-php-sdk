<?php

namespace EasyUmeng\Tests\Kernel\Support;

use EasyUmeng\Kernel\Support\AES;
use EasyUmeng\Tests\TestCase;

class AESTest extends TestCase
{
    public function testEncrypt()
    {
        $key = 'abcdefghijklmnopabcdefghijklmnop';
        $iv = substr($key, 0, 16);

        $expected = openssl_encrypt('foo', 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        self::assertSame($expected, AES::encrypt('foo', $key, $iv, OPENSSL_RAW_DATA));

        self::assertSame('foo', AES::decrypt($expected, $key, $iv, OPENSSL_RAW_DATA));
    }

    public function keyCases()
    {
        return [[12], [13], [15], [16], [18], [20], [21], [24], [26], [31], [32], [33]];
    }

    /**
     * @dataProvider keyCases
     */
    public function testValidKey($length)
    {
        try {
            $result = AES::validateKey(str_repeat('x', $length));
            if (in_array($length, [16, 24, 32], true)) {
                self::assertNull($result);
            } else {
                self::fail('No expected exception thrown.');
            }
        } catch (\Exception $e) {
            self::assertSame(sprintf('Key length must be 16, 24, or 32 bytes; got key len (%s).', $length), $e->getMessage());
        }
    }

    public function IvCases()
    {
        return [[12], [13], [15], [16], [18], [20], [21], [24], [26], [31], [32], [33]];
    }

    /**
     * @dataProvider IvCases
     */
    public function testValidateIv($length)
    {
        try {
            $result = AES::validateIv(str_repeat('x', $length));
            if (16 === $length) {
                self::assertNull($result);
            } else {
                self::fail('No expected exception thrown.');
            }
        } catch (\Exception $e) {
            self::assertSame('IV length must be 16 bytes.', $e->getMessage());
        }
    }
}
