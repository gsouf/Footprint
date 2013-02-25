<?php


use Footprint\Entity\EntityInterface;
use Footprint\DataPrint\DataPrintCollection;

use Footprint\DataPrint\Elements\Column;

class User implements EntityInterface{
    
    /**
     * @var DataPrintCollection
     */
    private static $dataPrintCollection;
    
    private $id;
    
    private $email;
    private $password;
    
    function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }
    
    public function getDataPrint() {
        
        if(!self::$dataPrintCollection){
            self::$dataPrintCollection=new DataPrintCollection();
            self::$dataPrintCollection->setClass("User");
            self::$dataPrintCollection->setTable("customer");
            self::$dataPrintCollection->add(new Column("id_customer", "getId", "setId"));
            self::$dataPrintCollection->add(new Column("email", "getEmail", "setEmail"));
            self::$dataPrintCollection->add(new Column("password", "getPassword", "setPassword"));
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
    
    


}

?>
