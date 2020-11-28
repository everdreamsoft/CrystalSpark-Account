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
use CsAccount\CsAccountRole;
use CsAccount\CSAccountUser;
use PHPUnit\Framework\TestCase;
use CsAccount\CsAccountECDSA ;




final class UserTest extends TestCase
{


    public function testUser(){

        $anAdmin = new CsAccountRole('admin');
        $aUser = new CsAccountRole('user');
        $aGuest = new CsAccountRole('guest');

        $csAccountManager = CSAccountManager::initWithDebugServerKey();
        CSAccountUser::initWithName("belovedAdmin")
            ->setRoles([$anAdmin])
            ->setAuthEthSignAddresses(['0xB5529f45D86b0B7ce0bd1679a86E3d13f936573C'])
            ->addToManager($csAccountManager);


        $this->assertCount(1,$csAccountManager->userManager->getAddressMap());

    }




}
