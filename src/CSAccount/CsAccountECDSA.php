<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 2019-08-06
 * Time: 12:29
 */

namespace CsAccount;


use Elliptic\EC;
use StephenHill\Base58;

class CsAccountECDSA
{

    const MainNetId = "\x00";
    const TestNetId = "\x6F";
    const PrefixNetIdMap = [ "1" => self::MainNetId, "m" => self::TestNetId ];

    public static  function pubKeyAddress($pubkey, $netid = self::MainNetId) {
    $b58 = new Base58();

    $pubenc   = hex2bin($pubkey->encode("hex", true));
    $pubhash  = $netid . hash('ripemd160', hash('sha256', $pubenc, true), true);
    $checksum = substr( hash('sha256', hash('sha256', $pubhash, true), true), 0, 4);

    return $b58->encode($pubhash . $checksum);
}

    public static function verifyBitcoinSignature($message, $signature, $address) {
        $signbin = base64_decode($signature);

        $signarr  = [ "r" => bin2hex(substr($signbin, 1, 32)),
            "s" => bin2hex(substr($signbin, 33, 32)) ];

        $nv = ord(substr($signbin, 0, 1)) - 27;
        if ($nv != ($nv & 7))
            return false;

        $recid = ($nv & 3);
        $compressed = ($nv & 4) != 0;

        $msglen = strlen($message);
        $hash = hash('sha256', hash('sha256', "\x18Bitcoin Signed Messag:\n" . chr($msglen) . $message, true));

        $ec = new EC('secp256k1');
        $pub = $ec->recoverPubKey($hash, $signarr, $recid);

        $result = self::pubKeyAddress($pub, self::PrefixNetIdMap[$address[0]]);
        return $result == $address;
    }

}