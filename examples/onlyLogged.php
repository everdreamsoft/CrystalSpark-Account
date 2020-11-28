<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 27.11.20
 * Time: 18:08
 */

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

$accountManager = new \CsAccount\CSAccountManager('serverKeyXXXXXXXXXXXXXXXXXXXX');

if ($accountManager->isLogged()) echo "you are logged in thanks";
else echo "Sorry Verified Users only. Please very your wallet address to proceed";