<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 28.11.20
 * Time: 12:50
 */

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use CsAccount\CSAccountManager;
use CsAccount\CsAccountRole;
use CsAccount\CSAccountUser;



$adminAddress = '0xB5529f45D86b0B7ce0bd1679a86E3d13f936573C';
$userAddress = '0x1E6638CA282D643e5c9a1651BC894A8e527D78b2';

$anAdmin = new CsAccountRole('admin');
$aUser = new CsAccountRole('user');
$aGuest = new CsAccountRole('guest');

$csAccountManager = CSAccountManager::initWithDebugServerKey()->withCookies();
CSAccountUser::initWithName("belovedAdmin")
    ->setRoles([$anAdmin])
    ->setAuthEthSignAddresses([$adminAddress])
    ->addToManager($csAccountManager);

CSAccountUser::initWithName("respectedMember")
    ->setRoles([$aUser])
    ->setAuthEthSignAddresses([$userAddress])
    ->addToManager($csAccountManager);

if ($csAccountManager->hasRole(new CsAccountRole('admin'))){
    echo "admin";
} else echo "not admin";

echo PHP_EOL ;

if ($csAccountManager->hasRole(new CsAccountRole('user'))){
    echo "user";
} else echo "not user";


