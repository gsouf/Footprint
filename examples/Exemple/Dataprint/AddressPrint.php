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
        $this->setClass("Exemple\Entity\Address");
        $this->setTable("address");
        $this->add(new Column("id", "getId", "setId"));
        $this->add(new Column("id_customer", "getIdCustomer", "setIdCustomer"));
        $this->add((new UserPrint("getCustomer","setCustomer"))->setColumnName("customer"));

        
        $this->backportCustomer();
        
        $this->registerPrimary("id");
    }
    
    public function joinCustomer(){
        $this->getElementByName("customer")->isJoin();
    }
    
    public function backportCustomer(){
        //var_dump($this->getElementByName("id_customer"));
        $this->getElementByName("customer")->isBackport();
    }
            
}

?>
