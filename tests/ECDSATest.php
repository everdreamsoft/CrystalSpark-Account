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
        $signature = "IMgsv5lyQIfD7p/xF9TtJ7U84Uf78FkMITaLdbOhiuxxAeq3l3PdPg0KRZEVy0oZbsDQmc16fw5QE6SgMK94t88=";
        $address   = "1KrQMaMfxu4JAAcnZRYfvw5yyMT2J4Fhr6";


        $verif = CsAccountECDSA::verifyBitcoinSignature($message,$signature,$address);

        $this->assertTrue($verif);

    }


}
