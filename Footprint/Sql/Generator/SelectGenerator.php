<?php

namespace Footprint\Sql\Generator;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Footprint\DataPrint\Elements\AbstractEntityElement;

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
     * @var array array("joinInternalPrint"=>array(alias,table,onclause))
     */
    private $joins;
    
    /**
     * @var Select
     */
    private $select;
    
    public function __construct(Sql $sql, AbstractEntityElement $dataPrint, $options=null) {
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
        
        // WILL BUFF COLUMNS AND JOINS
        $this->dataPrint->onSelect($this);
        
        $select->columns($this->columns[$this->dataPrint->_getInternalPrint()]);
        
        
        foreach($this->joins as $k=>$v){
            $select->join(array($v[0]=>$v[1]), $v[2], $this->columns[$k]);
        }

    }
    
    public function addColumn($col,$alias,$wrapperPrint){
        $this->columns[$wrapperPrint][$alias]=$col;
    }
    
    public function addJoin($alias,$table,$onClause){
        $this->joins[$alias]=array($alias,$table,$onClause);
    }
    

}

?>
