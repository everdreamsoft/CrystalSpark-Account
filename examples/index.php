<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 21.10.20
 * Time: 14:56
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload


$accountManager = \CsAccount\CSAccountManager::initWithDebugServerKey()->withCookies();






 if(isset($_GET['loginBySign'])){


     print_r($accountManager->authenticate($_POST['signature'],$_POST['message']));

}

else{

    if (isset($_COOKIE[$accountManager->getCookieName()])) die ("we have the coookkkkkiiiee ".$_COOKIE[$accountManager->getCookieName()]);

    $accountManager->renderAuthView();;

}

function verifyParam($param,$value){



}





