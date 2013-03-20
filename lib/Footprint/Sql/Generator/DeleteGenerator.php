<?php

namespace Footprint\Sql\Generator;

use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Sql;
use Footprint\DataPrint\Elements\AbstractEntityElement;

/**
 * Will generate an insert with the given object with no recursion : only the given entity with the columns values will be inserted, child entities are ignored
 *
 * @author SneakyBobito
 */
class DeleteGenerator {
    
    /**
     *
     * @var sql the zf2 sql used to generate insert statement
     */
    private $sql;
    
    /**
     *
     * @var AbstractEntityElement
     */
    private $dataPrint;

    

    /**
     * @var Delete the insert which will be returned
     */
    private $delete;
    
    public function __construct(Sql $sql, AbstractEntityElement $dataPrint) {
        $this->sql = $sql;
        $this->dataPrint = $dataPrint;
    }
    
    /**
     * @return Insert
     */
    public function getDelete(){
        return $this->delete;
    }
    
    /**
     * @return Footprint\Sql\Generator\DeleteGenerator returns $this
     */
    public function generate($instance){
        $this->delete=$this->sql->delete();
        $delete=$this->delete;
        
        $delete->from($this->dataPrint->getTable());
                
        
        // BUFF WHERE CLAUSE WITH PRIMARY
        //
        $whereArray=array();
        $primaries=$this->dataPrint->getPrimaries();
        if(!count($primaries)>0)
            throw new \Exception('The datapint '.$this->dataPrint->getClass()."@@".$this->dataPrint->getTable ().' has no primary key, this update generator wont work without primary keys, if you want to proced to a large update, use AdvancedUpdateGenerator instead');
        foreach($primaries as $elm){
            if($elm && $elm->isColumn())
                $whereArray[$elm->getColumnName()]=$elm->get($instance);
            else
                throw new \Exception("the registered primarykey  '".$colName."'  doesnt exists or is not a valid column  in the dataprint  '".$this->dataPrint->getClass()."@@".$this->dataPrint->getTable ()."' ");
        }
        $delete->where($whereArray);
       
        
        return $this;
    }
   
    

}

?>