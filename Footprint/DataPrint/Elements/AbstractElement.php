<?php

namespace Footprint\DataPrint\Elements;

use \Footprint\Entity\EntityInterface;

/**
 * Description of AbstractElement
 *
 * @author SneakyBobito
 */
abstract class AbstractElement {
    
    /**
     * @var int internal identifier used by the dataPrintCollection
     */
    private $internalIdentifier;
    
    /**
     * @var String name of the DB Column
     */
    private $columnName;
    
    /**
     * @var String name of the getter method, or null 
     */
    private $getter;
    
    /**
     * @var String name of the setter method, or null 
     */
    private $setter;
    
    
    public function __construct($columnName, $getter, $setter) {
        $this->columnName = $columnName;
        $this->getter = $getter;
        $this->setter = $setter;
    }
    
    /**
     * the column name of the DB
     * @return string
     */
    public function getColumnName(){
        return $this->columnName;
    }
    
    /**
     * @param \Footprint\Entity\EntityInterface $instance Instance from which to get
     * @return mixed the value returned by the getter of the parent Entity
     */
    public function get($instance){
        $getterName=$this->getter;
        return $instance->$getterName();
    } 
    
    /**
     * @param \Footprint\Entity\EntityInterface $instance Instance into which to set
     * @param mixed $value set into the instance of the parent Entity
     */
    public function set($instance,$value){
        $setterName=$this->setter;
        $instance->$setterName($value);
    }
    
    public function _setIdentifier($identifier){
        $this->internalIdentifier=$identifier;
    }
    
    public function getIdentifier(){
        return $this->internalIdentifier;
    }
    
}

?>
