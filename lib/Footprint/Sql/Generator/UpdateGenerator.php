<?php

namespace Footprint\Sql\Generator;

use Zend\Db\Sql\Update;
use Zend\Db\Sql\Sql;
use Footprint\DataPrint\Elements\AbstractEntityElement;

/**
 * Will generate an update with the given object with no recursion : only the given entity with the columns values will be updated, child entities are ignored
 *
 * @author SneakyBobito
 */
class UpdateGenerator {
    
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
     * @var Update the update which will be returned
     */
    private $update;
    
    public function __construct(Sql $sql, AbstractEntityElement $dataPrint) {
        $this->sql = $sql;
        $this->dataPrint = $dataPrint;
    }
    
    /**
     * @return Insert
     */
    public function getUpdate(){
        return $this->update;
    }
    
    /**
     * @return UpdateGenerator returns $this
     */
    public function generate($instance,$restrictColumns=null,$ingoreColumns=null){
        
        $this->update=$this->sql->update();
        $update=$this->update;

        $update->table($this->dataPrint->getTable());

        // WILL BUFF COLUMNS
        //
            $valuesArray=array();

            if(is_array($restrictColumns)){
                if(empty($restrictColumns))
                    throw new \Exception('List of restricted columns is empty');
                foreach($restrictColumns as $colName){
                    $elm=$this->dataPrint->getElementByName($colName);
                    if($elm && $elm->isColumn())
                        $valuesArray[$colName]=$elm->get($instance);
                    else
                        throw new \Exception("Error on getting a column with the restictive mode : column '".$colName."' doesnt exists or is not a valid column in the dataprint ".$this->dataPrint->getClass()."@@".$this->dataPrint->getTable ());
                }
            }else if(is_array($ingoreColumns)){ 
                foreach($this->dataPrint as $elm){
                    if($elm->isColumn() && !$elm->ignoreOnUpdate() && !in_array($elm->getColumnName (), $ingoreColumns))
                        $valuesArray[$elm->getColumnName ()]=$elm->get($instance);
                }
            }else{
                foreach($this->dataPrint as $elm){
                    if($elm->isColumn() && !$elm->ignoreOnUpdate())
                        $valuesArray[$elm->getColumnName ()]=$elm->get($instance);
                }
            }
            $update->set($valuesArray);
        
        
        // WILL BUFF WHERE CLAUSE
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

            $update->where($whereArray);
        
        
        
        return $this;
    }
   
    
    

}

?>