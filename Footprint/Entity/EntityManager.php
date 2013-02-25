<?php

namespace Footprint\Entity;

use Footprint\DataPrint\DataPrintCollection;

/**
 * Description of EntityManager
 *
 * @author SneakyBotito
 */
abstract class EntityInstanceManager{
    
    public static $entities;
    
    public static function register(\stdClass $object, DataPrintCollection $dataPrintParam=null){
        
        // check if params are ok and get the dataprint
        if(($dataPrint=self::retrieveDataPrint($object, $dataPrintParam))==null)
                return false;
        
        $primaryTrace=$dataPrint->getPrimaryTrace($object);
        $table=$dataPrint->getTable();
        
        $this->entity[$table][$primaryTrace]=$object;
        
        return true;
    }
    
    public static function hasInstance(\stdClass $object, DataPrintCollection $dataPrintParam=null) {
        
        // check if params are ok and get the dataprint
        if(($dataPrint=self::retrieveDataPrint($object, $dataPrintParam))==null)
                return false;
        
        $primaryTrace=$dataPrint->getPrimaryTrace($object);
        $table=$dataPrint->getTable();
        
        if(isset($this->entity[$table][$primaryTrace]))
            return true;
        
        return false;
    }
    
    /**
     * 
     * @param \stdClass $object
     * @param \Footprint\DataPrint\DataPrintCollection $dataPrintParam
     * @return null|\Footprint\DataPrint\DataPrintCollection
     * @throws Exception
     */
    private static function retrieveDataPrint(\stdClass $object, DataPrintCollection $dataPrintParam=null) {
        if(!$object)
            return null;
        
        if(!$dataPrintParam){
            if(is_a($object,"EntityInterface"))
                    $dataPrint=$object->getDataPrint();
            else 
                throw new Exception('Neither dataPrint nor EntityInterface');
        }else
            $dataPrint=$dataPrintParam;
        
        return $dataPrint;
    }
    
    

}

?>
