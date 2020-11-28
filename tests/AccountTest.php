<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.07.2019
 * Time: 17:46
 */


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use CsAccount\Account;
use CsAccount\CSAccountManager;
use PHPUnit\Framework\TestCase;
use CsAccount\CsAccountECDSA ;




final class AccountTest extends TestCase
{

    public function testAccount(){

        $csAccountManager = CSAccountManager::initWithDebugServerKey();
        $debugAddress = '0xB5529f45D86b0B7ce0bd1679a86E3d13f936573C';
        $debugAddress = strtolower($debugAddress);
        $signature = '0x1f0c870c054517655b18f0fe9c1156f36784d497a4fa58adf1cc53276e8349003ad2cc510c98ca2e039975eac87e7b5a388846e6f0d78f48a2ece2d7dfbb08ed1b';

        $challenge =$csAccountManager->requestServerChallenge($debugAddress,true);
        $this->assertEquals('OK',$csAccountManager->authenticate($signature,$challenge['message']));


    }

    public function testAlteredSignature(){

        $csAccountManager = CSAccountManager::initWithDebugServerKey();
        $debugAddress = '0xB5529f45D86b0B7ce0bd1679a86E3d13f936573C';
        $debugAddress = strtolower($debugAddress);
        $alteredSignature = '0x1f0c870c054517655b18f0fe9c1156f36784d497a4fa58adf1cc53276e8349003ad2cc510c98ca2e039975eac87e7b5a388846e6f0d78f48a2ece2d7dfbb08ed1';

        $challenge =$csAccountManager->requestServerChallenge($debugAddress,true);
        $this->assertNotEquals('OK',$csAccountManager->authenticate($alteredSignature,$challenge['message']));


    }

    public function testInvalidAddress(){

        $csAccountManager = CSAccountManager::initWithDebugServerKey();
        $falseAddress = '0x1E6638CA282D643e5c9a1651BC894A8e527D78b2';
        $falseAddress = strtolower($falseAddress);
        $signature = '0x1f0c870c054517655b18f0fe9c1156f36784d497a4fa58adf1cc53276e8349003ad2cc510c98ca2e039975eac87e7b5a388846e6f0d78f48a2ece2d7dfbb08ed1b';

        $challenge =$csAccountManager->requestServerChallenge($falseAddress,true);
        $this->assertNotEquals('OK',$csAccountManager->authenticate($signature,$challenge['message']));

    }

    public function testAddressShift(){

        $csAccountManager = CSAccountManager::initWithDebugServerKey();
        $debugAddress = '0xB5529f45D86b0B7ce0bd1679a86E3d13f936573C';
        $debugAddress = strtolower($debugAddress);
        $otherAddressSignature = '0x248a0d54313a04bc650f332df9f92adf8c87e50aeba419585508e2e7aebb102f5b3f50f94d3eda5677bd130dc221a6906bf9a97b7f4cbeca3c6589d14da469eb1c';

        $challenge =$csAccountManager->requestServerChallenge($debugAddress,true);
        $this->assertNotEquals('OK',$csAccountManager->authenticate($otherAddressSignature,$challenge['message']));

    }

    public function testOtherAddress(){

        $csAccountManager = CSAccountManager::initWithDebugServerKey();
        $otherAddress = '0x1E6638CA282D643e5c9a1651BC894A8e527D78b2';
        $otherAddress = strtolower($otherAddress);
        $otherAddressSignature = '0x248a0d54313a04bc650f332df9f92adf8c87e50aeba419585508e2e7aebb102f5b3f50f94d3eda5677bd130dc221a6906bf9a97b7f4cbeca3c6589d14da469eb1c';

        $challenge =$csAccountManager->requestServerChallenge($otherAddress,true);
        $this->assertEquals('OK',$csAccountManager->authenticate($otherAddressSignature,$challenge['message']));

    }


}
