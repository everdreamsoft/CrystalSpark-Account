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




final class ECRecoverTest extends TestCase
{

    public function testBase(){



        \CsAccount\Account::getAccountFromImplicitString('asdfas');
        $ethSigRecover = new \CsAccount\EthSigRecover();

        $message   = "b";
        $signature = "0xfa076d068ca83ec87203f394c630a1a992f0d39eac5e761aec4c90011204f0b776adf698fe3d626dfd4e7c6ef1f89adb4b9831adaeac72dd19093381265b45471b";
        $address   = "1387Ze9HfexTm8zxZzt1xacSu6GtRkvNkS";


        $verif = $ethSigRecover->personal_ecRecover($message,$signature);

        echo"verif $verif";
        $this->assertTrue($verif);

    }


}
