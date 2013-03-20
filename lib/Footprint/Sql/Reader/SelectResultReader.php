<?php

namespace Footprint\Sql\Reader;

use Zend\Db\ResultSet\ResultSet;
use Footprint\DataPrint\Elements\DataPrint;
use Footprint\DataPrint\Elements\AbstractEntityElement;
use Footprint\DataPrint\ElementUtils;
use Footprint\DataPrint\InstanceManager as InstanceManager;

/**
 * Class to generate Zend\Db\Sql\Select from the Footprint, the depth mode and the options
 *
 * @author SneakyBobito
 */
class SelectResultReader {
    
    /**
     *
     * @var DataPrint
     */
    private $dataprint;
    /**
     *
     * @var ResultSet;
     */
    private $resultSet;
    
    function __construct(AbstractEntityElement $dataprint,ResultSet $resultSet) {
        $this->dataprint = $dataprint;
        $this->resultSet = $resultSet;
    }

    public function buff(){
        
        $returnArray=array();
        
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
                
                /* @var $dataPrint DataPrint */
                
                //if dataprint is backported it means an instance was already created on the top level
                //  + 1 we find the wrapper and the double wrapper
                //  + 2 we use the wrapper to retrieve the wrapper entity instance
                //      and the double wrapper to find the dwPrimaryTrace
                //  + 3 we look if the wrapper instance has already an instance of the dataprint entity
                //        (iterate over the result of the dataprint getter)
                //  + if yes
                //    + do nothing
                //  + else
                //    + 4 find the missing instance thanks to the doubl wrapper
                //    + 5 we call [getter of dataprint] to set [instance of double wrapper] into the [instance of wrapper]
                if(AbstractEntityElement::LINK_BACKPORT==$dataPrint->getLinkMode()){
                    //1
                    $wrapper=$dataPrint->getWrapper();
                    $dWrapper=$wrapper->getWrapper();
                    $dwPrimaryTrace=$dWrapper->getPrimaryTrace($row, true, $dWrapper->getInternalPrintMap());
                    //2
                    $wrapperInstance=$instanceManager->getInstanceOf($wrapper->getPrimaryTrace($row, true, $wrapper->getInternalPrintMap()), $wrapper);
                    //3
                    $instanceList=$dataPrint->get($wrapperInstance);
                    $hasAlreadyAnInstance=false;
                    if(!$instanceList); // if null
                    else if(is_object($instanceList)){ // if it is object we look whether primary trace of the object is the same
                        if($dwPrimaryTrace==$dWrapper->getPrimaryTrace($instanceList))
                            $hasAlreadyAnInstance=true;
                    }else if(is_array($instanceList)){
                        foreach($instanceList as $v){
                            if($dwPrimaryTrace==$dWrapper->getPrimaryTrace($v)){
                                $hasAlreadyAnInstance=true;
                            }
                        }
                    }else{
                        throw new \Exception("getter : ".$dataPrint->getGetter()." is doesnt return a valid result for the class ".$dataPrint->getClass());
                    }
                    
                    if(!$hasAlreadyAnInstance){
                        //4
                        $existingInstace=$instanceManager->getInstanceOf($dwPrimaryTrace, $dWrapper);
                        //5
                        $dataPrint->set($wrapperInstance, $existingInstace);
                    }
                    
                }else if(AbstractEntityElement::LINK_NONE==$dataPrint->getLinkMode() && AbstractEntityElement::ROOTTOKEN!==$dataPrint->_getInternalPrint()){
                
                    // this is neither backported nor joined child element, so lets ignore it. Maybe throw an exception ?
                
                }else{// ELSE WE ARE A TRUE AUTHENTIC ELEMENT
                
                    $primaryTrace=$dataPrint->getPrimaryTrace($row, true, $dataPrint->getInternalPrintMap());
                    
                    if(empty($primaryTrace))
                        continue;   // if it is empty is means that there is nothing to join
                                    //TODO : make this stronger and saffer

                    //if instance already exists, dont create it, dont hydrate it
                    if(!$instanceManager->hasInstaceOf($primaryTrace, $dataPrint)){

                        // create a new instance
                        // + hydrate its columns (only values, not entities)
                        // + add the instance to the instance list
                        $newInstance=ElementUtils::createInstace($dataPrint);
                        ElementUtils::hydrateColmuns($newInstance, $dataPrint, $row, $dataPrint->getInternalPrintMap());
                        $instanceManager->addInstance($newInstance, $dataPrint);
                    }else{
                        $newInstance=$instanceManager->getInstanceOf($primaryTrace, $dataPrint);
                    }
                    

                    
                    // FINALY WE LOOK IF WE HAVE TO ADD THE INSTANCE TO THE PARENT OR TO THE RETURNED ARRAY  
                    //
                    //
                    $wrapper=$dataPrint->getWrapper();

                    if($wrapper){//if has a wrapper, find the parentInstance, then add the childInstance into

                        $parentPrimaryTrace=$wrapper->getPrimaryTrace($row, true, $wrapper->getInternalPrintMap());
                        $parentInstance=$instanceManager->getInstanceOf($parentPrimaryTrace, $wrapper);
                        if($parentInstance){


                            if(!ElementUtils::hasThisInstace($dataPrint, $primaryTrace, $dataPrint->get($parentInstance))){// if the child instance is not into the parent
                                $dataPrint->set($parentInstance, $newInstance);
                            }


                        }else{
                            // TODO ERREUR
                        }
                    }else{
                        //else it means it is a root dataprint instance, then we add it to the return array if still not in
                        if(!ElementUtils::hasThisInstace($dataPrint, $primaryTrace, $returnArray))
                            $returnArray[]=$newInstance;
                    }
                }
            }

        }
        
        return $returnArray;
    }

}

?>
