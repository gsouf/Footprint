<?php

namespace Footprint\Sql\Reader;

use Zend\Db\ResultSet\ResultSet;
use Footprint\DataPrint\DataPrintCollection;
use Footprint\DataPrint\ElementUtils;
use Footprint\DataPrint\InstanceManager;

/**
 * Class to generate Zend\Db\Sql\Select from the Footprint, the depth mode and the options
 *
 * @author SneakyBobito
 */
class SelectResultReader {
    
    /**
     *
     * @var DataPrintCollection
     */
    private $dataprint;
    /**
     *
     * @var ResultSet;
     */
    private $resultSet;
    
    function __construct(DataPrintCollection $dataprint,ResultSet $resultSet) {
        $this->dataprint = $dataprint;
        $this->resultSet = $resultSet;
    }

    public function buff(){
        
        /**
         * buffedEntities= array(
         *      Dataprint.internalPrint1=>array(primarytrace1,primarytrace2,primarytrace3),
         *      Dataprint.internalPrint2=>array(primarytrace1,primarytrace2,primarytrace3),
         *      ...
         * )
         */
        $rootDataPrint=$this->dataprint;
        
        $instanceManager=new InstanceManager($rootDataPrint);
        
        
        // foreach result row, we look for each entity if it is already instanciated (thanks to the primaryTrace)
        // If no, we instanciate it and we hydrate it
        foreach($this->resultSet as $row){
            $entityIterator=$instanceManager->getEntitiesIterator();
            foreach($entityIterator as $entity){
                $dataPrint=$entity;
                /* @var $dataPrint DataPrintCollection */
                
                $primaryTrace=$dataPrint->getPrimaryTrace($row, true, $dataPrint->getInternalPrintMap());
                //if instance already exists, we ignore it
                if(!$instanceManager->hasInstaceOf($primaryTrace, $dataPrint)){
                    
                    // create a new instance
                    // + hydrate its columns (only values, not entities)
                    // + add the instance to the instance list
                    $newInstance=ElementUtils::createInstace($dataPrint);
                    ElementUtils::hydrateColmuns($newInstance, $dataPrint, $row, $dataPrint->getInternalPrintMap());
                    $instanceManager->addInstance($newInstance, $dataPrint);
                    
                    // searching the parent instance
                    $wrapper=$dataPrint->getWrapper();
                    if($wrapper){

                        //if ok, find the parentInstance, then add the childInstance into
                        $parentPrimaryTrace=$wrapper->getPrimaryTrace($row, true, $wrapper->getInternalPrintMap());
                        $parentInstance=$instanceManager->getInstanceOf($parentPrimaryTrace, $wrapper);
                        if($parentInstance){
                            $dataPrint->set($parentInstance, $newInstance);
                        }else{
                            // TODO ERREUR ?
                        }
                        var_dump($parentInstance);
                    }else{
                        //else it means it is the root dataprint, then we add it to the return array
                        
                    }
                    
                }
            }
        }
        
    }

}

?>
