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
    private $options;
    
    private $columns;
    
    /**
     *
     * @var array array("joinInternalPrint"=>array(,,))
     */
    private $joins;
    
    /**
     * @var Select
     */
    private $select;
    
    public function __construct(Sql $sql,DataPrintCollection $dataPrint, $options=null) {
        $this->sql = $sql;
        $this->dataPrint = $dataPrint;
        $this->options = is_array($options)?$options:array();
        $this->columns=array();
        $this->joins=array();
    }
    
    /**
     * @return Select
     */
    public function getSelect(){
        return $this->select;
    }
    
    /**
     * @return Select the generated ZF2 Select 
     */
    public function generate(){
        $this->select=$this->sql->select();
        $select=$this->select;

        $select->from($this->dataPrint->getTable());
        
        $this->dataPrint->onSelect($this);
        
        $select->columns($this->columns);

    }
    
    public function addColumn($col,$alias,$wrapperPrint){
        $this->columns[$wrapperPrint][$alias]=$col;
    }
    
    public function addJoin($col,$alias,$wrapperPrint){
        $this->columns[$wrapperPrint][$alias]=$col;
    }
    

}

?>
