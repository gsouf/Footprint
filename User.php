<?php


use Footprint\Entity\EntityInterface;
use Footprint\DataPrint\DataPrint as DataPrintCollection;
use Footprint\DataPrint\Elements\AbstractEntityElement;

use Footprint\DataPrint\Elements\Column;

class User implements EntityInterface{
    
    /**
     * @var DataPrintCollection
     */
    private static $dataPrintCollection;
    
    private $id;
    
    private $adress;
    
    private $email;
    private $password;
    
    private $lastLogin;
    private $registerDate;
    
    function __construct($email="", $password="") {
        $this->email = $email;
        $this->password = $password;
    }
    
    public function getDataPrint() {
        
        if(!self::$dataPrintCollection){
            
            $d1=new DataPrintCollection("getAdress","setAdress");
            $d1->setClass("Address");
            $d1->setTable("address");
            $d1->add(new Column("id", "getId", "setId"));
            $d1->add(new Column("id_customer", "getIdCustomer", "setIdCustomer"));
            
            $up=new UserPrint();
            $up->setLinkMode(AbstractEntityElement::LINK_BACKPORT);
            $up->setJoin(array("id_customer"=>"id_customer"));
            $up->setSetter("setCustomer");
            $up->setGetter("getCustomer");
            $d1->add($up);
            
            $d1->setJoin(array("id_customer"=>"id_customer"));
            $d1->setLinkMode(AbstractEntityElement::LINK_PARENT);
            $d1->registerPrimary("id");
            
            
            self::$dataPrintCollection=new UserPrint();
            self::$dataPrintCollection->add($d1);
        }
        
        return self::$dataPrintCollection;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

        
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function getLastLogin() {
        return $this->lastLogin;
    }

    public function setLastLogin($lastLogin) {
        $this->lastLogin = $lastLogin;
    }

    public function getRegisterDate() {
        return $this->registerDate;
    }

    public function setRegisterDate($registerDate) {
        $this->registerDate = $registerDate;
    }
    
    public function getAdress() {
        return $this->adress;
    }

    public function setAdress($adress) {
        $this->adress[] = $adress;
    }


    



    


}

?>
