<?php

namespace Footprint\Sql\Generator;

use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Footprint\DataPrint\Elements\AbstractEntityElement;

/**
 * Will generate an insert with the given object with no recursion : only the given entity with the columns values will be inserted, child entities are ignored
 *
 * @author SneakyBobito
 */
class InsertGenerator {
    
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
     * @var Insert the insert which will be returned
     */
    private $insert;
    
    public function __construct(Sql $sql, AbstractEntityElement $dataPrint) {
        $this->sql = $sql;
        $this->dataPrint = $dataPrint;
    }
    
    /**
     * @return Insert
     */
    public function getInsert(){
        return $this->insert;
    }
    
    /**
     * @return InsertGenerator returns $this
     */
    public function generate($instance){
        $this->insert=$this->sql->insert();
        $insert=$this->insert;

        $insert->into($this->dataPrint->getTable());

        // WILL BUFF COLUMNS
        $valuesArray=array();
        foreach($this->dataPrint as $elm){
            if($elm->isColumn() && !$elm->ignoreOnInsert())
                $valuesArray[$elm->getColumnName ()]=$elm->get($instance);
        }
        $insert->values($valuesArray);
        
        return $this;
    }
   
    

}

?>