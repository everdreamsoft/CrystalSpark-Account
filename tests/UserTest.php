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

    const PASSPHRASE_TEST = 'decline surprise mother rally fall gauge robust dentist sight goose peanut interest';

    const ADMIN_JWT = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODg4OCIsImF1ZCI6ImxvY2FsaG9zdDo4ODg4IiwiaWF0IjoxNjA2NTk4ODY3LCJpcCI6Ijo6MSIsInZlcmlmaWVkIjp'
    .'0cnVlLCJhZGRyZXNzIjoiMHhiNTUyOWY0NWQ4NmIwYjdjZTBiZDE2NzlhODZlM2QxM2Y5MzY1NzNjIn0.y_tE1pHNRRwnbS-Guo0nUBFU28JllO0sLfBgOFtp3z0';

    private function buildManager(){

        $adminAddress = '0xB5529f45D86b0B7ce0bd1679a86E3d13f936573C';
        $userAddress = '0x1E6638CA282D643e5c9a1651BC894A8e527D78b2';

        $anAdmin = new CsAccountRole('admin');
        $aUser = new CsAccountRole('user');
        $aGuest = new CsAccountRole('guest');

        $csAccountManager = CSAccountManager::initWithDebugServerKey();
        CSAccountUser::initWithName("belovedAdmin")
            ->setRoles([$anAdmin])
            ->setAuthEthSignAddresses([$adminAddress])
            ->addToManager($csAccountManager);

        CSAccountUser::initWithName("respectedMember")
            ->setRoles([$aUser])
            ->setAuthEthSignAddresses([$userAddress])
            ->addToManager($csAccountManager);

        return $csAccountManager ;

    }


    public function testUserManager(){

        $adminAddress = '0xB5529f45D86b0B7ce0bd1679a86E3d13f936573C';

        $anAdmin = new CsAccountRole('admin');
        $aUser = new CsAccountRole('user');
        $aGuest = new CsAccountRole('guest');

        $csAccountManager = CSAccountManager::initWithDebugServerKey();
        CSAccountUser::initWithName("belovedAdmin")
            ->setRoles([$anAdmin])
            ->setAuthEthSignAddresses(['0xB5529f45D86b0B7ce0bd1679a86E3d13f936573C'])
            ->addToManager($csAccountManager);


        $this->assertCount(1,$csAccountManager->userManager->getAddressMap());
        $this->assertEquals($csAccountManager->userManager->getAddressMap()[strtolower($adminAddress)]->getName(),'belovedAdmin');

    }

    public function testGetUserFromJWT(){

        $jwt = self::ADMIN_JWT ;

        $csAccountManager = $this->buildManager();
        $this->assertTrue($csAccountManager->isRegisteredUser($jwt));


    }

    public function testTwoUserSameAddress(){

        $jwt = self::ADMIN_JWT ;

        $csAccountManager = $this->buildManager();
        $this->assertTrue($csAccountManager->isRegisteredUser($jwt));

        $userDuplicateUser = new CsAccountRole('userDuplicateUser');

        $userAccepted = true ;
        try {

            CSAccountUser::initWithName("belovedAdmin")
                ->setRoles([$userDuplicateUser])
                ->setAuthEthSignAddresses(['0xB5529f45D86b0B7ce0bd1679a86E3d13f936573C'])
                ->addToManager($csAccountManager);

        }catch (Exception $e){

            $userAccepted = false ;
        }

        $this->assertFalse($userAccepted);

    }

    public function testHasRole(){

        $jwt = self::ADMIN_JWT ;

        $csAccountManager = $this->buildManager();
        $this->assertTrue($csAccountManager->hasRole(new CsAccountRole('admin'),$jwt));
        $this->assertFalse($csAccountManager->hasRole(new CsAccountRole('user'),$jwt));





    }



}
