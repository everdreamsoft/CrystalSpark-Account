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
    private $useCookies = false ;
    /**
     * @var UserManager
     */
    public UserManager $userManager;


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
        $this->userManager = new UserManager();

        if(isset($_GET['requestServerChallenge'])){
            header('Content-Type: application/json');
            echo json_encode($this->requestServerChallenge($_GET['requestServerChallenge']));
            die();

        }


    }

    public function withCookies(){

        $this->useCookies = true ;
        return $this;

    }

    public function renderAuthView()
    {

        $authenTicateView = new \CsAccount\view\AuthenticateView();
        echo $authenTicateView->returnAuthenticatePage();
        die();

    }

    public function requestServerChallenge($forAddress,$debug=false)
    {

        $jwtService = new JwtService($this);
        $jwt = !$debug ? $jwtService->requestServerChallengeForDebug($forAddress) : $jwtService->requestServerChallengeForDebug($forAddress);
        $response['jwt'] = $jwt;
        $response['message'] = $this->getSignMessage($jwt);



        return $response ;

    }

    public function getServerKey(){

        return $this->serverKey ;
    }

    private function getSignMessage($jwt){

        return "you have to sign this jwt 
        
        
        $this->messageDataSeparator$jwt";
    }

    public function authenticate($signature, $message)
    {
        //$chain = BlockchainRouting::blockchainFromAddress($address);

        try {
            $signer = EthSigRecover::personalEcRecover($message, $signature);

        }catch (\Exception $e){

            return "KO ".$e->getMessage();
        }

        //extract JWT from message
        $messageFull = explode($this->messageDataSeparator,$message);
        $jwt = $messageFull[1];
        $jwtService = new JwtService($this);

        try {
            $jwtService->jwtAddressMatch($jwt,$signer);

        }catch (\Exception $e){

            return "KO ".$e->getMessage();
        }





        if ($this->useCookies) {  $this->setCookie($jwt,time() + (86400 * 30)); }// 86400 = 1 day

        return "OK";
    }

    public function setCookie($jwt,$expirationTimestamp)
    {

        setcookie($this->cookieName, $jwt, $expirationTimestamp, "/"); // 86400 = 1 day

    }



    public function isAuthorized($requiredAuth,$jwt=null)
    {



    }

    public function isLogged($jwt=null)
    {

        if (isset($_COOKIE[$this->getCookieName()])){

            try {
                $jwtService = new JwtService($this);
                $jwtService->isValidJwt($_COOKIE[$this->getCookieName()]);
                return true ;

            }catch (\Exception $e){

            }



        }

        return false ;



    }

    public function logout(){

        if (isset($_COOKIE[$this->getCookieName()])) {
            unset($_COOKIE[$this->getCookieName()]);
            setcookie($this->getCookieName(), null, -1, '/');
            return true;
        } else {
            return false;
        }


    }

    public function addUser(CSAccountUser $user){

        $this->userManager->addUser($user);

    }

    public static function initWithDebugServerKey(){

       return new CSAccountManager('ServerKeyXXXXXXXXXXXXXX');


    }



}