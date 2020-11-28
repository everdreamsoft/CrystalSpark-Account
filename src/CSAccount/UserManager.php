<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 28.11.20
 * Time: 21:38
 */

namespace CsAccount;


class UserManager
{

    private array $user = array();
    private $addressMap;

    public function addUser(CSAccountUser $user){

        $this->mapAddress($user);
        $this->user[$user->getName()] = $user ;


    }

    public function mapAddress(CSAccountUser $user){

        foreach ($user->getAuthAddress() as $address){

            if (isset($this->addressMap[$address])) throw new \Exception("address conflict two user with same address");

            $this->addressMap[$address] = $user ;

        }
        $this->user[$user->getName()] = $user ;

    }

    /**
     * @return CSAccountUser[];
     */
    public function getAddressMap(){

    return $this->addressMap ;

    }

}