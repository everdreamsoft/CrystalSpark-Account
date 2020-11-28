<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 28.11.20
 * Time: 12:50
 */


use CsAccount\CsAccountRole;

$accountManager = \CsAccount\CSAccountManager::initWithDebugServerKey();

$adminRole = new CsAccountRole('admin');
$memberRole = new CsAccountRole('member');

