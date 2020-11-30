<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 28.11.20
 * Time: 12:43
 */

namespace CsAccount;


class CsAccountUser
{

    private $name = null ;
    /**
     * @var array
     */
    private $authAddress = array();

    private $manager = null ;

    /**
     * @return array
     */
    public function getAuthAddress(): array
    {
        return $this->authAddress;
    }

    /**
     * @param array $authAddress
     * @return CsAccountUser
     */
    public function setAuthEthSignAddresses(array $authAddress)
    {
        foreach ($authAddress as $key => $address) $authAddress[$key] = strtolower($address);
        $this->authAddress = $authAddress ;
        return $this ;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this ;
    }

    /**
     * @var CsAccountRole[]
     */
    private array $roles;

    /**
     *
     * @param CsAccountRole[] $roles
     */
    public function getRoles(){

       return $this->roles ;

    }

    /**
     *
     * @param CsAccountRole[] $roles
     * @return CsAccountUser
     */
    public function setRoles(array $roles){

        $this->roles = $roles ;
        return $this ;

    }

    public function addToManager(CsAccountManager $manager){

        $manager->addUser($this);
        return $this ;

    }


    public static function initWithName(string $name):self{

        $user = new CsAccountUser();
        $user->setName($name);
        return $user ;

    }

}