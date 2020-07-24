<?php

namespace LndConnect;


/**
 * Class LndConnect
 * @author tkijewski
 */
class LndConnect
{
    /**
     * Format an LNDConnect uri with already encoded parameters
     * @param string $host
     * @param string $encoded_cert
     * @param string $base64url_macaroon
     * @return string
     */
    public static function format($host,$encoded_cert,$base64url_macaroon)
    {
        $str = 'lndconnect://';
        $str .= $host;
        $str .= '?';
        $str .= 'cert='.$encoded_cert;
        $str .= '&';
        $str .= 'macaroon='.$base64url_macaroon;

        return $str;
    }

    /**
     * Encode and format a LNDConnect uri
     * @param string $host
     * @param string $raw_cert
     * @param string $macaroon_hex
     * @return string
     */
    public static function encode($host,$raw_cert,$macaroon_hex)
    {
        $str = 'lndconnect://';
        $str .= $host;
        $str .= '?';
        $str .= 'cert='.self::encodeCert($raw_cert);
        $str .= '&';
        $str .= 'macaroon='.self::encodeMacaroon($macaroon_hex);

        return $str;
    }

    /**
     * Parse an LNDConnect uri into friendly components
     * @param string $lndconnect_uri
     * @return array
     */
    public static function decode($lndconnect_uri)
    {
        $parsed = parse_url($lndconnect_uri);
        parse_str($parsed['query'], $query);
        $array = [
            'host'  =>  $parsed['host'].':'.$parsed['port'],
            'cert'  =>  self::decodeCert($query['cert']),
            'macaroon'=>self::decodeMacaroon($query['macaroon'])
        ];

        return $array;
    }

    /**
     * Encode cert to LNDConnect spec. cert = '-----BEGIN CERTIFICATE-----....'
     * @param string $raw_cert
     * @return string
     */
    public static function encodeCert($raw_cert)
    {
        $arr = [];
        foreach (explode("\n",$raw_cert) as $c) {
            if (stripos($c, 'BEGIN CERTIFICATE') !== false) {
                continue;
            }
            if (stripos($c, 'END CERTIFICATE') !== false) {
                continue;
            }
            $arr[] = $c;
        }

        return self::base64url_encode(base64_decode(implode("",$arr)));
    }

    /**
     * Transform LNDConnect uri cert back to it's original form
     * @param string $lndconnect_cert
     * @return string
     */
    public static function decodeCert($lndconnect_cert)
    {
        $str = "-----BEGIN CERTIFICATE-----\n".chunk_split(base64_encode(self::base64url_decode($lndconnect_cert)),64)."-----END CERTIFICATE-----";
        return $str;
    }

    /**
     * Encode macaroon to LNDConnect spec
     * @param string $macaroon_hex
     * @return string
     */
    public static function encodeMacaroon($macaroon_hex)
    {
        return self::base64url_encode(hex2bin($macaroon_hex));
    }

    /**
     * Transform LNDConnect spec macaroon to original
     * @param string $macaroon
     * @return string
     */
    public static function decodeMacaroon($lndconnect_macaroon)
    {
        return bin2hex(self::base64url_decode($lndconnect_macaroon));
    }


    /**
     * Helper function for base64url (no native function in PHP)
     * @param string $str
     * @return bool|string
     */
    public static function base64url_decode($str)
    {
        return base64_decode(str_pad(strtr($str, '-_', '+/'), strlen($str) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * Helper function for base64url (no native function in PHP)
     * @param string $str
     * @return bool|string
     */
    public static function base64url_encode($str) {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}