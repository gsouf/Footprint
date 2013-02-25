<?php

namespace Footprint\Sql;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Footprint\DataPrint\DataPrintCollection;

/**
 * Class to generate Zend\Db\Sql\Select from the Footprint, the depth mode and the options
 *
 * @author SneakyBobito
 */
class SelectGenerator {
    
    private $sql;
    private $dataPrint;
    private $depthMode;
    private $options;
    
    private $select;
    
    public function __construct(Sql $sql,DataPrintCollection $dataPrint,$depthMode, $options=null) {
        $this->sql = $sql;
        $this->dataPrint = $dataPrint;
        $this->depthMode = $depthMode;
        $this->options = is_array($options)?$options:array();
    }
    
    /**
     * 
     * @return Select the generated ZF2 Select 
     */
    public function generate(){
        
        $select=$this->sql->select();
        
        $select->from($this->dataPrint->getTable());
        
        
        
        
        $this->select=$select;
    }
    

}

?>
