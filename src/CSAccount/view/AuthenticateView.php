<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 21.10.20
 * Time: 14:38
 */

namespace CsAccount\view;


class AuthenticateView
{

   public function returnAuthenticatePage(){



       return  file_get_contents(__DIR__.'/default/login.html');


}

}