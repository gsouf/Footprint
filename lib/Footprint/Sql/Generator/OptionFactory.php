<?php

namespace Footprint\Sql;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Footprint\DataPrint\DataPrint as DataPrintCollection;

/**
 * Class to populate options like limit,order... in Zend\Db\Sql\Select from an option array
 *
 * @author SneakyBobito
 */
class OptionFactory {
    private $options;
    
    function __construct($options) {
        $this->options = $options;
    }
    
    public function renderTo(Select $select){
        
    }

}

?>
