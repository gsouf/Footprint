<?php

namespace Exemple\Dataprint;

use Footprint\DataPrint\Elements\Column;

/**
 * Description of UserPrint
 *
 * @author bobito
 */
class UserPrint extends \Footprint\DataPrint\Elements\DataPrint{
    

    function __construct($getter = "", $setter = "") {
        parent::__construct($getter, $setter);
        
        // Object Class Name
        $this->setClass("Exemple\Entity\User");
        // Database Table
        $this->setTable("customer");
        // COLUMNS
        $idCustomer=new Column("id_customer", "getId", "setId");
        $idCustomer->ignoreOnInsert(true);
        $idCustomer->ignoreOnUpdate(true);
        $this->add($idCustomer);
        $this->add(new Column("email", "getEmail", "setEmail"));
        $this->add(new Column("password", "getPassword", "setPassword"));
        $this->add(new Column("date_register", "getRegisterDate", "setRegisterDate"));
        $this->add(new Column("date_last_login", "getLastLogin", "setLastLogin"));
        // primary
        $this->registerPrimary("id_customer");

        
    }
    
        // ENTITIES
        //
    
        public function joinAddress(){
            $addressPrint=new AddressPrint("getAdress","setAdress");
            $addressPrint->setJoin(array("id_customer"=>"id_customer"));
            $this->add($addressPrint->setColumnName("address"));
            $addressPrint->isJoin();
            $addressPrint->backportCustomer();
            //$addressPrint->backportCustomer();
            return $this;
        }
        public function backportAddress(){
            $addressPrint=new AddressPrint("getAdress","setAdress");
            $this->add($addressPrint->setColumnName("adresse"));
            $addressPrint->isBackport();
            return $this;
        }
            
}

?>
