<?php

namespace App\Libraries;

class InstagramID
{
    const BASE64URL_CHARMAP = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
    const BASE10_MOD2 = ['0', '1', '0', '1', '0', '1', '0', '1', '0', '1'];

    public static $bitValueTable = null;

    public static function toCode($postID)
    {
        $charMap = self::BASE64URL_CHARMAP;
        $shortCode = '';
        while ($postID > 0) {
            $remainder = ($postID % 64);
            $postID = ($postID - $remainder) / 64;
            $shortCode = $charMap{
            (int)$remainder} . $shortCode;
        }

        return $shortCode;
    }

    public static function fromCode($code)
    {
        if (!is_string($code) || preg_match('/[^A-Za-z0-9\-_]/', $code)) {
            throw new \InvalidArgumentException('Input must be a valid Instagram shortcode.');
        }

        $base2 = '';
        for ($i = 0, $len = strlen($code); $i < $len; ++$i) {
            $base64 = strpos(self::BASE64URL_CHARMAP, $code[$i]);
            $base2 .= str_pad(decbin($base64), 6, '0', STR_PAD_LEFT);
        }
        $base10 = self::base2to10($base2);
        return $base10;
    }

    public static function base10to2($base10, $padLeft = true)
    {
        $base10 = (string)$base10;
        if ($base10 === '' || preg_match('/[^0-9]/', $base10)) {
            throw new \InvalidArgumentException('Input must be a positive integer.');
        }

        $base2 = '';
        do {
            $lastDigit = $base10[(strlen($base10) - 1)];
            $base2 .= self::BASE10_MOD2[$lastDigit];
            $base10 = floor($base10 / 2);
        } while ($base10 !== '0');

        $base2 = strrev($base2);

        if ($padLeft) {
            $padAmount = (8 - (strlen($base2) % 8));
            if ($padAmount != 8 || strlen($base2) === 0) {
                $base2 = str_repeat('0', $padAmount) . $base2;
            }
        } else {
            $base2 = ltrim($base2, '0');
        }

        return $base2;
    }

    public static function buildBinaryLookupTable($maxBitCount)
    {
        $table = [];
        for ($bitPosition = 0; $bitPosition < $maxBitCount; ++$bitPosition) {
            $bitValue = pow(2, (int)$bitPosition);
            $table[] = $bitValue;
        }

        return $table;
    }

    public static function base2to10($base2)
    {
        if (!is_string($base2) || preg_match('/[^01]/', $base2)) {
            throw new \InvalidArgumentException('Input must be a binary string.');
        }

        if (self::$bitValueTable === null) {
            self::$bitValueTable = self::buildBinaryLookupTable(512);
        }

        $base2rev = strrev($base2);

        $base10 = '0';
        $bits = str_split($base2rev, 1);
        for ($bitPosition = 0, $len = count($bits); $bitPosition < $len; ++$bitPosition) {
            if ($bits[$bitPosition] == '1') {
                if (isset(self::$bitValueTable[$bitPosition])) {
                    $bitValue = self::$bitValueTable[$bitPosition];
                } else {
                    $bitValue = pow(2, (int)$bitPosition);
                    self::$bitValueTable[$bitPosition] = $bitValue;
                }

                $base10 = $base10 + $bitValue;
            }
        }

        return $base10;
    }
}