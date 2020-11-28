<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 27.11.20
 * Time: 18:24
 */

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

$accountManager = new \CsAccount\CSAccountManager('serverKeyXXXXXXXXXXXXXXXXXXXX');

$accountManager->logout();

$accountManager->renderAuthView();