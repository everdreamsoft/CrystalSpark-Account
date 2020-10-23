<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 21.10.20
 * Time: 18:03
 */

namespace CsAccount;


use CsCannon\BlockchainRouting;
use CsCannon\Blockchains\Ethereum\EthereumBlockchain;
use Firebase\JWT\JWT;

class CSAccountManager
{

    private $serverKey;
    private $messageDataSeparator = "==CSAccount Server Validation==";
    private $cookieName = "CSAccount_jwt";

    /**
     * @return string
     */
    public function getCookieName(): string
    {
        return $this->cookieName;
    }

    /**
     * @param string $cookieName
     */
    public function setCookieName(string $cookieName): void
    {
        $this->cookieName = $cookieName;
    }

    public function __construct($serverKey)
    {

        $this->serverKey = $serverKey ;



    }

    public function renderAuthView()
    {

        $authenTicateView = new \CsAccount\view\AuthenticateView();
        echo $authenTicateView->returnAuthenticatePage();
        die();

    }

    public function requestServerChallenge($forAddress)
    {

        $jwtService = new JwtService($this);
        $jwt = $jwtService->requestServerChallenge($forAddress);
        $response['jwt'] = $jwt;
        $response['message'] = $this->getSignMessage($jwt);



        return $response ;

    }

    public function getServerKey(){

        return $this->serverKey ;
    }

    public function getSignMessage($jwt){

        return "you have to sign this jwt 
        
        
        $this->messageDataSeparator$jwt";
    }

    public function authenticate($address, $message)
    {

        $chain = BlockchainRouting::blockchainFromAddress($address);
        $validSignature = false ;

        if ($chain instanceof EthereumBlockchain)
           // $validSignature = EthSigRecover::personalEcRecover($message,$address);

        //extract JWT from message
        $jwt = explode($this->messageDataSeparator,$message);
        $jwtService = new JwtService($this);

        if (!$jwtService->isValidJwt($jwt[1])){
            return $jwtService->isValidJwt($jwt[1]) ;

        }
        setcookie($this->cookieName, $jwt[1], time() + (86400 * 30), "/"); // 86400 = 1 day

        return "OK";

    }

    public function isAuthorized($requiredAuth,$jwt=null)
    {



    }

    public function isLoggedOrRedirect($jwt=null)
    {




    }



}