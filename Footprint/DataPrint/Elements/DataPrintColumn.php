<?php

namespace Footprint\DataPrint\Elements;


/**
 * Description of DataPrint
 *
 * @author SneakyBobito
 */
class DataPrintColumn extends DataPrint{
    
     public function __construct($column,$getter="", $setter="") {
        parent::__construct($getter, $setter);
        $this->setColumnName($column);
        
    }
}

?>