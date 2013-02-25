<?php

namespace Footprint\DataPrint;

use Footprint\DataPrint\Elements\AbstractElement;

/**
 * Description of DataPrintCollection
 *
 * @author SneakyBobito
 */
class DataPrintCollection  implements \IteratorAggregate {
    
    private $elements;
    private $table;
    private $class;
    
    private $primary;
    
    // TODO CONSTRUCTOR
    
    public function __construct($table="",$class="") {
        $this->elements=array();
        $this->primary=array();
        $this->table=$table;
        $this->class=$class;
        
    }
    
    public function getIterator() {
        return new \ArrayIterator($this->elements);
    }
    
    public function add(AbstractElement $value){
        $this->elements[]=$value;
    }
    
    /**
     * 
     * @param type $name
     * @return AbstractElement|boolean
     */
    public function getElementByName($name){
        foreach($this->elements as $v){
            if($v->getColumnName()==$name)
                return $v;
        }
        return false;
    }

    /**
     * 
     * @param StdObject $input
     * @param array|null $nameMap the map of names
     * @param boolean $useProperty If true, properties will be used instead of getters. Default to false.
     * @return String A string which reprents a unique trace of the object in the db
     */
    public function getPrimaryTrace($input,$useProperty=false,$nameMap=null){
        $primaryTrace="";
        foreach($this->primary as $v){
            if(is_array($nameMap) && isset($nameMap[$v]))
                $name=$nameMap[$v];
            else 
                $name=$v;
            
            if($useProperty)
                $primaryTrace.=$input->$name;
            else
                $primaryTrace.=$this->getElementByName($name)->get($input);
        }
        
        return $primaryTrace;
        
    }
    
    /**
     * 
     * @param string $value
     */
    public function registerPrimary($value){
        $this->primary[]=$value;
        //TODO : ERROR WHEN $value doesnt exists
    }
    
    public function getTable() {
        return $this->table;
    }

    public function setTable($table) {
        $this->table = $table;
    }
    
    public function getClass() {
        return $this->class;
    }

    public function setClass($class) {
        $this->class = $class;
    }

    



}

?>
