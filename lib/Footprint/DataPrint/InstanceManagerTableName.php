<?php

namespace Footprint\DataPrint;
use Footprint\DataPrint\Elements\AbstractEntityElement;

/**
 * A class internally used which manages instances for a given dataprint
 * But contrary to the traditional instanceManager, 
 * rather than idexing instances with the internalPrint, this one indexes instance with tableNames
 *
 * @author Sneakybobito
 */
class InstanceManagerTableName extends InstanceManager{
    
    function __construct(AbstractEntityElement $dataPrint) {
        parent::__construct($dataPrint);

        foreach($this->getEntitiesIterator() as $v){
            $this->instances[$v->getTable()]=array();
        }

        
    }
    
    /**
     * Says wether or not the given instance (identified by primaryTrace) is registered
     * for the given table name of dataprint
     * 
     * @param String $primaryTrace the primary trace of the instance we are searching for or the name of the table
     * @param String|\Footprint\DataPrint\Elements\AbstractEntityElement $dataprint the dataprint or its internalPrint of which we are searching for an instance
     * @return boolean true if the instance is found.
     */
    public function hasInstaceOf($primaryTrace,$dataPrint){
        // TODO ERROR IF DATAPRINT DOESNT EXISTS IN THIS.DATAPRINT
        if(is_a($dataPrint,"\Footprint\DataPrint\Elements\AbstractEntityElement"))
            $table=$dataPrint->getTable();
        else
            $table=$dataPrint;
        
        return isset($this->instances[$table][$primaryTrace]);
    }
    
    /**
     * add the given instance to the given dataprint instance list
     * @param \stdClass $instance
     * @param \Footprint\DataPrint\Elements\AbstractEntityElement $dataprint
     */
    public function addInstance($instance,\Footprint\DataPrint\Elements\AbstractEntityElement $dataprint){
        //TODO MANAGE ERROR OF BAD INSTANCE/DATAPRINT COUPLE
        $this->instances[$dataprint->getTable()][$dataprint->getPrimaryTrace($instance)]=$instance;
    }
    
    /**
     * get the instance of the primarytrace with the given dataprint (or table name)
     * @param String $primaryTrace
     * @param AbstractEntityElement|String $dataPrint
     * @return Mixed  the instance or null
     */
    public function getInstanceOf($primaryTrace,$dataPrint){
        // TODO ERROR IF DATAPRINT DOESNT EXISTS IN THIS.DATAPRINT
        if(is_a($dataPrint,"\Footprint\DataPrint\Elements\AbstractEntityElement"))
            $table=$dataPrint->getTable();
        else
            $table=$dataPrint;
        
        return $this->instances[$table][$primaryTrace];
    }
    

    
}

?>
