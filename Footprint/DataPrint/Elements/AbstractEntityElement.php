<?php

namespace Footprint\DataPrint\Elements;

use Footprint\Entity\EntityInterface;
use Footprint\DataPrint\Elements\AbstractElement;
use Footprint\DataPrint\Elements\Column;

use Footprint\Sql\SelectGenerator;


/**
 * Description of Column
 *
 * @author Sneakybobito
 */
class AbstractEntityElement extends AbstractElement implements \IteratorAggregate {
    
    const LINK_PARENT="parent";
    const LINK_CHILD="child";
    const LINK_CHILDREN="children";
    const LINK_NONE="none";
    
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
    
    // TODO CONSTRUCTOR
    
    public function __construct($table="",$class="") {
        $this->elements=array();
        $this->primary=array();
        $this->table=$table;
        $this->class=$class;
        
        $this->incrementedIdentifier=1;
        $this->_setInternalPrint("0#");
        
        $this->joinColumns = array();
        $this->linkMode = self::LINK_NONE;
        
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
        
        return $iterator;
    }

    /**
     * Give the string merging the primary keys value for the given entity, using this dataprint
     * @param StdObject $input object from which get the primary keys
     * @param array|null $nameMap the map of names
     * @param boolean $useProperty If true then properties will be used instead of getters. Default to false.
     * @return String A string which reprents a primary trace of the object in the db
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
    
    // OVERIDE
    public function _setInternalPrint($internalPrint) {
        parent::_setInternalPrint($internalPrint);
        
        $this->_redoInternalPrint();
    }

    public function _redoInternalPrint(){
        foreach($this->elements as $v){
            $v->_setInternalPrint($this->_getInternalPrint().self::INTERNALPRINTTOKEN.$v->_getIdentifier());
        }
    }
    
    

    /**
     * define the given field as a primary key of the table
     * this field must already have been added whit the "add(Element)" method
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
    
    public function onSelect(SelectGenerator $selectGenerator) {
        
        $iPrint=$this->_getInternalPrint();
                
        switch($this->linkMode){
        case self::LINK_CHILD :
            break;
        case self::LINK_CHILDREN :
            break;
        case self::LINK_PARENT :
            // parentColumn=thisColumn
            //$onClause=key($this->joinColumns)."=".current($this->joinColumns);
            $onClause=$this->getWrapper()->_getInternalPrint().".".key($this->joinColumns)."=".$iPrint.".".current($this->joinColumns);
            
            $selectGenerator->addJoin(array($iPrint=>$this->getTable()),$onClause,$print);
            break;
        case self::LINK_NONE :
            $selectGenerator->getSelect()->from(array($iPrint=>$this->getTable()));
            break;
        }
        
        foreach($this->elements as $v){
            $v->onSelect($selectGenerator);
        }
    }
}

?>