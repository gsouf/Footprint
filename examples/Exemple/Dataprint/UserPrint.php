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

        $this->setClass("Exemple\Entity\User");
        $this->setTable("customer");
        $this->add(new Column("id_customer", "getId", "setId"));
        $this->add(new Column("email", "getEmail", "setEmail"));
        $this->add(new Column("password", "getPassword", "setPassword"));
        $this->add(new Column("date_register", "getRegisterDate", "setRegisterDate"));
        $this->add(new Column("date_last_login", "getLastLogin", "setLastLogin"));
        
        $this->registerPrimary("id_customer");
    }
            
}

?>
