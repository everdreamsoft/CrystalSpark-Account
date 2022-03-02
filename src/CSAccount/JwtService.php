<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 21.10.20
 * Time: 18:18
 */

namespace CsAccount;


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    /**
     * @var CsAccountManager
     */
    private  $accountManager;

    public function __construct(CsAccountManager $accountManager)
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
            "verified" => false,
            "address" => $address
        );


        $jwt = JWT::encode($payload, $key,'HS256');

        return $jwt ;

    }

    public function requestServerChallengeForDebug($address){

        $key = $this->accountManager->getServerKey() ;
        $payload = array(

            "address" => $address
        );





        $jwt = JWT::encode($payload, $key,'HS256');

        return $jwt ;

    }

    public function isValidJwt($jwt)
    {
        try {

            $keyArray = new Key($this->accountManager->getServerKey(), 'HS256');


                $jwt = JWT::decode($jwt, $keyArray );


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

            $keyArray = new Key($this->accountManager->getServerKey(), 'HS256');

            $jwt = JWT::decode($jwt, $keyArray);
            echo $jwt->address . " $address"  ;
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

    public function getUserFromSessionJwt($jwt){

        try {
            $keyArray = new Key($this->accountManager->getServerKey(), 'HS256');
            $loginJwt = JWT::decode($jwt, $keyArray);
            if($loginJwt->verified) {
                return $this->accountManager->userManager->getUserFromAddress($loginJwt->address);
            }
        } catch (\Exception $e){

        }

        return null ;

    }

    public function issueSessionJWT($jwt)
    {
        try {
            $keyArray = new Key($this->accountManager->getServerKey(), 'HS256');
            $loginJwt = JWT::decode($jwt, $keyArray);
            $address = $loginJwt->address ;
            $key = $this->accountManager->getServerKey() ;
            $payload = array(
                "iss" => $_SERVER['HTTP_HOST'],
                "aud" => $_SERVER['HTTP_HOST'],
                "iat" => time(),
                "ip" => $_SERVER['REMOTE_ADDR'],
                "verified" => true,
                "address" => $address
            );


            $jwt = JWT::encode($payload, $key,'HS256');

            return $jwt ;
        }catch (\Exception $e){

            return false ;
        }
    }

}