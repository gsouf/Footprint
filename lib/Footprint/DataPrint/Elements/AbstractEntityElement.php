<?php

namespace Footprint\DataPrint\Elements;

use Footprint\Entity\EntityInterface;
use Footprint\DataPrint\Elements\AbstractElement;
use Footprint\DataPrint\Elements\Column;

use Footprint\Sql\Generator\SelectGenerator;
use Footprint\DataPrint\InternalPrintIterator;

/**
 * Description of Column
 *
 * @author Sneakybobito
 */
class AbstractEntityElement extends AbstractElement implements \IteratorAggregate {
    
    const LINK_JOIN="join";
    const LINK_NONE="none";
    const LINK_BACKPORT="backport";
    
    /**
     * @var AbstractElement[]
     */
    protected $elements;
    
    
    /**
     * @var array how to join this table with the wrapping dataprint table. Key is the wrapping column, value is this column
     */
    private $joinColumns;
    
    /**
     * @var string the mode of linking : parent, child, children or none
     */
    private $linkMode;
    
    /**
     *
     * @var AbstractEntityElement  the parent entity
     */
    
    private $table;
    private $class;
    
    private $primary;
    
    private $incrementedIdentifier;
    
    private $nameToInternalprintMap;
    
    // TODO CONSTRUCTOR
    
    public function __construct($getter="", $setter="") {
        parent::__construct($getter, $setter);
        $this->elements=array();
        $this->primary=array();
        
        $this->incrementedIdentifier=1;
        $this->_setInternalPrint(self::ROOTTOKEN);
        
        $this->joinColumns = array();
        $this->linkMode = self::LINK_NONE;
        $this->nameToInternalprintMap=null;
        
    }
    
    
    /**
     * Ajoute un élement à la liste des fields db
     * @param \Footprint\DataPrint\Elements\AbstractElement $value
     */
    public function add(AbstractElement $value){
        $value->_setIdentifier($this->incrementedIdentifier++);
        $value->_setInternalPrint($this->_getInternalPrint().self::INTERNALPRINTTOKEN.$value->_getIdentifier());
        $value->setWrapper($this);
        $this->elements[]=$value;
        $this->nameToInternalprintMap=null;
    }
    
    /**
     * give a name map mapping the column names with the dataprint. 
     * Intended to be used internaly with a resultSet generated from the selectGenerator to march the aliases with the true names
     * @return array The key is the column name, value is the alias. Eg : array("id_user"=>"0$$__1" , "name"=>"0$$__2" )
     */
    public function getInternalPrintMap(){
        if(null==$this->nameToInternalprintMap){
            foreach($this as $v){
                if(is_a($v,"Footprint\DataPrint\Elements\AbstractEntityElement")){

                }else{
                    $this->nameToInternalprintMap[$v->getColumnName()]=$v->_getInternalPrint();
                }
            }
        }
        
        return $this->nameToInternalprintMap;
    }
    
    public function getInternalPrintOf($name){
        return $this->getElementByName($name)->_getInternalPrint();
    }
    
    /**
     * search amoung the first-depth elements to find the element with the given name
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
     * search amoung the first-depth elements to find the element with the given identifier
     * @param type $name
     * @return AbstractElement|boolean
     */
    public function getElementByIdentifier($identifier){
        
        if(self::ROOTTOKEN==$identifier && self::ROOTTOKEN==$this->_getInternalPrint())
            return $this;
        
        foreach($this->elements as $v){

            if($v->_getIdentifier()==$identifier)
                return $v;
        }
        return false;
    }
    
    /**
     * RESERVED FOR INTERNAL USEp
     * search into the full depth for the element matching with the ierator
     * @param \Footprint\DataPrint\InternalPrintIterator $iterator the iterator should be positioned at the wished ittering position. (for initiale call, if it was aleready iterated, think about rewind it)
     */
    public function getElementByInternalPrint(InternalPrintIterator $iterator){
        $identifier=$iterator->current();
        $element=$this->getElementByIdentifier($identifier);
        $iterator->next();
        if($iterator->valid())
            return $element->getElementByInternalPrint($iterator);
        
        return $element;
    }

    /**
     * Give the string merging the primary keys value for the given entity, using this dataprint
     * @param StdObject $input object from which get the primary keys. Usualy it is an entity, but it can also be a SQL result row (then useProperty instead of getters thank to param $useProperty)
     * @param boolean $useProperty If true : properties will be used instead of getters. Default to false.
     * @param array|null $nameMap the map of names
     * @return String A string which reprents a primary trace of the object in the db
     */
    public function getPrimaryTrace($inputData,$useProperty=false,$nameMap=null){
        $primaryTrace="";
        foreach($this->primary as $k=>$elm){
            if(is_array($nameMap) && isset($nameMap[$k]))
                $name=$nameMap[$k];
            else 
                $name=$k;
            
            if($useProperty)
                $primaryTrace.=$inputData->$name;
            else
                $primaryTrace.=$elm->get($inputData);
        }
        
        return $primaryTrace;
        
    }
    
    // OVERIDE
    public function _setInternalPrint($internalPrint) {
        parent::_setInternalPrint($internalPrint);
        
        $this->_redoInternalPrint();
    }

    public function _redoInternalPrint(){
        foreach($this->elements as $v){
            $v->_setInternalPrint($this->_getInternalPrint().self::INTERNALPRINTTOKEN.$v->_getIdentifier());
        }
        $this->nameToInternalprintMap=null;
    }
    
    

    /**
     * define the given field as a primary key of the table
     * this field must already have been added whit the "add(Element)" method
     * @param string $value
     */
    public function registerPrimary($value){
        $elm=$this->getElementByName($value);
        if(!$elm)
            throw new \Exception("No such column : '".$value."'");
        $this->primary[$value]=$elm;
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
    
    // IMPLEMENTS
    public function getIterator() {
        return new \ArrayIterator($this->elements);
    }


    
    public function setJoin($joinArray){
        $this->joinColumns=$joinArray;
    }
    
    public function setLinkMode($joinMode){
        $this->linkMode=$joinMode;
    }
    public function getLinkMode(){
        return $this->linkMode;
    }
    
    
    public function isJoin(){
        $this->setLinkMode(self::LINK_JOIN);
    }
    
    public function isBackport(){
        $this->setLinkMode(self::LINK_BACKPORT);
    }
    
    public function onSelect(SelectGenerator $selectGenerator) {
        
        $iPrint=$this->_getInternalPrint();
        
        $continue=true;
        
        switch($this->linkMode){

        case self::LINK_JOIN :
            // parentColumn=thisColumn
            //$onClause=key($this->joinColumns)."=".current($this->joinColumns);
            $onClause=$this->getWrapper()->_getInternalPrint().".".key($this->joinColumns)."=".$iPrint.".".current($this->joinColumns);
            
            $selectGenerator->addJoin($iPrint,$this->getTable(),$onClause);
            break;
        case self::LINK_NONE :
            $selectGenerator->getSelect()->from(array($iPrint=>$this->getTable()));
            break;
        case self::LINK_BACKPORT :
            $continue=false;
            break;
        
        }
        
        if($continue){
            foreach($this->elements as $v){
                $v->onSelect($selectGenerator);
            }
        }
    }
}

?>