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
    const INTERNALPRINTTOKEN="__f__";
    
    /**
     * @var String the tring which is used for the root dataprint internalprint
     */
    const ROOTTOKEN="000f";
    
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
     * @var String name of the getter method, or null 
     */
    private $getter;
    
    /**
     * @var String name of the setter method, or null 
     */
    private $setter;
    
    private $wrapper;
    
    private $isColumn;
    private $ignoreOnInsert;
    private $ignoreOnUpdate;
    
        
    /**
     * @var String name of the DB Column
     */
    private $columnName;

    public function __construct($getter, $setter) {
        $this->getter = $getter;
        $this->setter = $setter;
        $this->internalPrint="";
        
        $this->wrapper=null;
        $this->ignoreOnInsert=false;
        $this->ignoreOnUpdate=false;
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
     * the column name of the DB
     * @return string
     */
    public function getColumnName(){
        return $this->columnName;
    }
    
    public function setColumnName($columnName) {
        $this->columnName = $columnName;
        return $this;
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
    

    public function setGetter($getter) {
        $this->getter = $getter;
    }



    public function setSetter($setter) {
        $this->setter = $setter;
    }
    
    public function getGetter() {
        return $this->getter;
    }

    public function getSetter() {
        return $this->setter;
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
    
    /**
     * 
     * @param boolean|null $isColumn default to null
     * @return \Footprint\DataPrint\Elements\AbstractElement|boolean  if param $isColumn is passed and is true or false, the method will return $this. Else it will return true or false
     */
    public function isColumn($isColumn=null) {
        if(false===$isColumn || true===$isColumn){
            $this->isColumn = $isColumn;
            return $this;
        }
        
        return $this->isColumn;
    }
    
    /**
     * 
     * @param Boolean|null $ignore
     * @return \Footprint\DataPrint\Elements\AbstractElement|boolean  if param $isColumn is passed and is true or false, the method will return $this. Else it will return true or false
     */
    public function ignoreOnInsert($ignore=null) {
        if(false===$ignore || true===$ignore){
            $this->ignoreOnInsert = $ignore;
            return $this;
        }
        
        return $this->ignoreOnInsert;
    }
    
    /**
     * 
     * @param Boolean|null $ignore
     * @return \Footprint\DataPrint\Elements\AbstractElement|boolean  if param $isColumn is passed and is true or false, the method will return $this. Else it will return true or false
     */
    public function ignoreOnUpdate($ignore=null) {
        if(false===$ignore || true===$ignore){
            $this->ignoreOnUpdate = $ignore;
            return $this;
        }
        
        return $this->ignoreOnUpdate;
    }

    
    public abstract function onSelect(SelectGenerator $select);
    
}

?>
