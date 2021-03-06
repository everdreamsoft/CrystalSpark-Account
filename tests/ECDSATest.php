<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.07.2019
 * Time: 17:46
 */


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use PHPUnit\Framework\TestCase;
use CsAccount\CsAccountECDSA ;




final class ECDSATest extends TestCase
{

    public function testBase(){

        \CsAccount\Account::getAccountFromImplicitString('asdfas');

        $message   = "b";
        $signature = "IEP9vwYjDXhHtdMqELfP2ehpAh74UPwIXp8mG8ihMy53R+0J7R6W9pjnpN4gf32IoxQgcC6VDd3sVnqPIvrpJmQ=";
        $address   = "1387Ze9HfexTm8zxZzt1xacSu6GtRkvNkS";


        $verif = CsAccountECDSA::verifyBitcoinSignature($message,$signature,$address);

        $this->assertTrue($verif);

    }


}
