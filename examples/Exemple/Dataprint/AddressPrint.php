<?php

namespace Exemple\Dataprint;

use Footprint\DataPrint\Elements\Column;

/**
 * Description of UserPrint
 *
 * @author bobito
 */
class AddressPrint extends \Footprint\DataPrint\Elements\DataPrint{
    

    function __construct($getter = "", $setter = "") {
        parent::__construct($getter, $setter);
        // Object Class Name
        $this->setClass("Exemple\Entity\Address");
        // Database Table
        $this->setTable("address");
        
        
        // COLUMNS
        //
        $idAddress=new Column("id_address", "getId", "setId");
        $idAddress->ignoreOnInsert(true);
        $this->add($idAddress);
        $this->add(new Column("id_customer", "getIdCustomer", "setIdCustomer"));
        // primary
        $this->registerPrimary("id_address");

        
    }
    
        // ENTITIES
        //
    
        public function joinCustomer(){
            $userPrint=new UserPrint("getCustomer","setCustomer");
            $this->add($userPrint->setColumnName("customer"));
            $userPrint->setJoin(array("id_customer"=>"id_customer"));
            $userPrint->isJoin();
            return $this;
        }
        public function backportCustomer(){
            $userPrint=new UserPrint("getCustomer","setCustomer");
            $this->add($userPrint->setColumnName("customer"));
            $userPrint->isBackport();
            return $this;
        }
            
}

?>
