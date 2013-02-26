<?php

namespace Footprint\DataPrint\Elements;

use \Footprint\Entity\EntityInterface;
use Footprint\Sql\Generator\SelectGenerator;

/**
 * Description of AbstractElement
 *
 * @author SneakyBobito
 */
abstract class AbstractElement {
    
    /**
     * @var String the string which separat each identifier of the internalPrint. It must be SQL table and column syntaxe compatible
     * see http://social.msdn.microsoft.com/Forums/sk/databasedesign/thread/154c19c4-95ba-4b6f-b6ca-479288feabfb#473bcd1a-34c4-45e2-a60b-9f2881727a9c
     */
    const INTERNALPRINTTOKEN="\$__";
    
    /**
     * @var String the tring which is used for the root dataprint internalprint
     */
    const ROOTTOKEN="0\$";
    
    /**
     * @var int internal identifier used by the dataPrint wrapper. Each identifier must be unique in each dataprint
     */
    private $internalIdentifier;
    
    
    /**
     *
     * @var string 
     */
    private $internalPrint;
    
    
    
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
    
    private $wrapper;
    
    public function __construct($columnName, $getter, $setter) {
        $this->columnName = $columnName;
        $this->getter = $getter;
        $this->setter = $setter;
        $this->internalPrint="";
        
        $this->wrapper=null;
    }
    
    /**
     * the column name of the DB
     * @return string
     */
    public function getColumnName(){
        return $this->columnName;
    }
    
    public function setWrapper(AbstractEntityElement $wrapper){
        $this->wrapper=$wrapper;
    }
    
    /**
     * 
     * @return AbstractEntityElement
     */
    public function getWrapper(){
        return $this->wrapper;
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
     * @param \Footprint\Entity\EntityInterface $instance Instance on which "set" is call
     * @param mixed $value value to set into instance
     */
    public function set($instance,$value){
        $setterName=$this->setter;
        $instance->$setterName($value);
    }
    
    /**
     * INTERNAL USAGE ONLY
     * set the internal identifier.
     * @param int $identifier
     */
    public function _setIdentifier($identifier){
        $this->internalIdentifier=$identifier;
    }
    
    /**
     * INTERNAL USAGE ONLY
     * get the internal identifier.
     * @return int
     */
    public function _getIdentifier(){
        return $this->internalIdentifier;
    }
    
    /**
     * INTERNAL USAGE ONLY
     * @return String
     */
    public function _getInternalPrint() {
        return $this->internalPrint;
    }
    
    /**
     * INTERNAL USAGE ONLY
     * @param type $internalPrint
     */
    public function _setInternalPrint($internalPrint) {
        $this->internalPrint = $internalPrint;
    }

    public abstract function onSelect(SelectGenerator $select);
    
}

?>
