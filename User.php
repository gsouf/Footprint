<?php


use Footprint\Entity\EntityInterface;
use Footprint\DataPrint\DataPrintCollection;
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
            
            $d1=new DataPrintCollection("","getAdress","setAdress");
            $d1->setClass("Address");
            $d1->setTable("address");
            $d1->add(new Column("id", "getId", "setId"));
            $d1->add(new Column("id_customer", "getIdCustomer", "setIdCustomer"));
            $d1->setJoin(array("id_customer"=>"id_customer"));
            $d1->setLinkMode(AbstractEntityElement::LINK_PARENT);
            $d1->registerPrimary("id");
            
            
            self::$dataPrintCollection=new DataPrintCollection();
            self::$dataPrintCollection->setClass("User");
            self::$dataPrintCollection->setTable("customer");
            self::$dataPrintCollection->add(new Column("id_customer", "getId", "setId"));
            self::$dataPrintCollection->add(new Column("email", "getEmail", "setEmail"));
            self::$dataPrintCollection->add(new Column("password", "getPassword", "setPassword"));
            self::$dataPrintCollection->add(new Column("date_register", "getRegisterDate", "setRegisterDate"));
            self::$dataPrintCollection->add(new Column("date_last_login", "getLastLogin", "setLastLogin"));
            self::$dataPrintCollection->add($d1);
            self::$dataPrintCollection->registerPrimary("id_customer");
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
