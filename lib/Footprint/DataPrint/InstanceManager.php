<?php

namespace Footprint\DataPrint;
use Footprint\DataPrint\Elements\AbstractEntityElement;

/**
 * A class internally used which manages instances for a given dataprint
 *
 * @author Sneakybobito
 */
class InstanceManager {
    
    private $dataPrint;
    
    /**
     *  array(
     *      AbstractEntityElement.internalPrint=>array(
     *                                              instance.primaryTrace=>instance,
     *                                              .... ,
     *                                          ),
     *      ... ,
     *  )
     * @var array 
     */
    protected $instances;
    
    /**
     *  array("internalPrint"=>AbstractentityElement)
     * @var array
     */
    private $entities;
    
    function __construct(AbstractEntityElement $dataPrint) {
        $this->dataPrint = $dataPrint;
        $this->instances = array();
        $this->entities = array();
        
        ElementUtils::getEntitiesOfDataprint($this->entities, $this->dataPrint);

        foreach($this->entities as $v){
            $this->instances[$v->_getInternalPrint()]=array();
        }
        
        // now $this->entities has all entities registered but empty. 
        // 
        // NOTE THAT IS IS IMPORTANT TO KEEP THE ORDER OF THIS ARRAY
        // BECAUSE WHEN WE ITERAT IT WE CANT INSTANCIATE A CHILD AND SET IT INTO A NOT INSTACIATED PARENT
    }
    
    /**
     * Says wether or not the given instance (identified by primaryTrace) is registered
     * for the given table name of dataprint
     * 
     * @param String $primaryTrace the primary trace of the instance we are searching for
     * @param String|\Footprint\DataPrint\Elements\AbstractEntityElement $dataprint the dataprint or its internalPrint of which we are searching for an instance
     * @return boolean true if the instance is found.
     */
    public function hasInstaceOf($primaryTrace,$dataPrint){
        // TODO ERROR IF DATAPRINT DOESNT EXISTS IN THIS.DATAPRINT
        if(is_a($dataPrint,"\Footprint\DataPrint\Elements\AbstractEntityElement"))
            $internalPrint=$dataPrint->_getInternalPrint();
        else
            $internalPrint=$dataPrint;
        
        return isset($this->instances[$internalPrint][$primaryTrace]);
    }
    
    /**
     * add the given instance to the given dataprint instance list
     * @param \stdClass $instance
     * @param \Footprint\DataPrint\Elements\AbstractEntityElement $dataprint
     */
    public function addInstance($instance,\Footprint\DataPrint\Elements\AbstractEntityElement $dataprint){
        //TODO MANAGE ERROR OF BAD INSTANCE/DATAPRINT COUPLE
        $this->instances[$dataprint->_getInternalPrint()][$dataprint->getPrimaryTrace($instance)]=$instance;
    }
    
    
    /**
     * 
     * @param string $primaryTrace the primary trace of the instance
     * @param type $dataPrint the dataPrint model
     * @return Object the searched instance or null
     */
    public function getInstanceOf($primaryTrace,$dataPrint){
        // TODO ERROR IF DATAPRINT DOESNT EXISTS IN THIS.DATAPRINT
        if(is_a($dataPrint,"\Footprint\DataPrint\Elements\AbstractEntityElement"))
            $internalPrint=$dataPrint->_getInternalPrint();
        else
            $internalPrint=$dataPrint;
        
        return $this->instances[$internalPrint][$primaryTrace];
    }
    
    public function getDataPrint() {
        return $this->dataPrint;
    }

    public function setDataPrint($dataPrint) {
        $this->dataPrint = $dataPrint;
    }


    public function getEntitiesIterator() {
        return new \ArrayIterator($this->entities);
    }
    
    public function getInstancesIterator() {
        return new \ArrayIterator($this->instances);
    }




    
}

?>
