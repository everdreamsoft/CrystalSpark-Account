<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 21.10.20
 * Time: 18:18
 */

namespace CsAccount;


use Firebase\JWT\JWT;

class JwtService
{
    /**
     * @var CSAccountManager
     */
    private  $accountManager;

    public function __construct(CSAccountManager $accountManager)
    {
        $this->accountManager = $accountManager ;

    }

    public function requestServerChallenge($address){

        $key = $this->accountManager->getServerKey() ;
        $payload = array(
            "iss" => $_SERVER['HTTP_HOST'],
            "aud" => $_SERVER['HTTP_HOST'],
            "iat" => time(),
            "ip" => $_SERVER['REMOTE_ADDR'],

            "address" => $address
        );


        $jwt = JWT::encode($payload, $key);

        return $jwt ;

    }

    public function requestServerChallengeForDebug($address){

        $key = $this->accountManager->getServerKey() ;
        $payload = array(



            "address" => $address
        );


        $jwt = JWT::encode($payload, $key);

        return $jwt ;

    }

    public function isValidJwt($jwt)
    {
        try {

            $jwt = JWT::decode($jwt, $this->accountManager->getServerKey(), array('HS256'));
            $this->verifyAdditionalConstraints($jwt);
            return true ;
            }catch (\Exception $e){

            return false ;
        }
    }

    public function jwtAddressMatch($jwt,$address)
    {
        try {
            $this->isValidJwt($jwt);
            $jwt = JWT::decode($jwt, $this->accountManager->getServerKey(), array('HS256'));
            if ($jwt->address != $address) throw new \Exception("signer doens't match challenge");
            return true ;
        }catch (\Exception $e){
            throw new \Exception($e);

        }
    }

    private function verifyAdditionalConstraints( $jwt)
    {

        if(isset($jwt->iss) && $jwt->iss !=  $_SERVER['HTTP_HOST']) {
            throw new \Exception('issuer is set');

        }
        /*
        if(isset($jwt->aud) && $jwt->aud !=  $_SERVER['HTTP_ORIGIN']) {
            die($jwt->aud.$_SERVER['HTTP_ORIGIN']);
            throw new \Exception('wrong source');

        }

        */
        if(isset($jwt->ip) && $jwt->ip !=  $_SERVER['REMOTE_ADDR']) {

            throw new \Exception('wrong ip');

        }



    }

    public function issueSessionJWT($jwt)
    {
        try {

            JWT::decode($jwt, $this->accountManager->getServerKey(), array('HS256'));
            return true ;
        }catch (\Exception $e){

            return false ;
        }
    }

}