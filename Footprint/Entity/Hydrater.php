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
     * @param EntityInterface $object the object to hydrate
     * @param \Footprint\DataPrint\DataPrintCollection $dataPrint the dataprint for hydrating
     * @param StdClass|array $data the datas which will hydrate the object
     * @param array|null $nameMap Maps the object DataPrint columns to match with the given data names. Key is the dataPrint column name, value is the data name
     */
    public static function hydrate(EntityInterface $object,DataPrintCollection $dataPrint,$data,$nameMap=null){
        
        if(!$data)
            return false;
        
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
            var_dump($data->$name);
            $v->set($object,$data->$name);
            
        }
        
        
    }
}

?>
