<?php

namespace Footprint\Entity;

use Footprint\DataPrint\DataPrintCollection;
use Footprint\DataPrint\Elements\AbstractElement;

/**
 * Description of Hydrater
 *
 * @author bobito
 */
class Hydrater {
    
    public function __construct(){
        
    }
    
    /**
     * 
     * @param \Footprint\DataPrint\DataPrintCollection $dataPrint
     * @param StdClass|array $data
     * @param array|null $nameMap Maps the object DataPrint columns to match with the given data names. Key is the dataPrint column name, value is the data name
     */
    public function hydrate(EntityInterface $object,$data,$nameMap=null){
        
        // On this way we dont care about array or object
        if(is_array($data))
            $data=(object)$data;
        
        if(!is_array($nameMap))
            $nameMap=null;
        
        $dataPrint=$object->getDataPrint();
        
        foreach($dataPrint as $v){
            
            if(is_array($nameMap) && isset($nameMap[$v->getColumnName()]))
                $name=$nameMap[$v->getColumnName()];
            else 
                $name=$v->getColumnName();
            
            $v->set($object,$data->$name);
            
        }
        
        
    }
}

?>
