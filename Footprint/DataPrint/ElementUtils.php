<?php

namespace Footprint\DataPrint;

use Footprint\DataPrint\Elements\AbstractEntityElement;

/**
 * Description of Utils
 *
 * @author SneakyBobito
 */
class ElementUtils {
    
    public static function getEntitiesOfDataprint(&$array, AbstractEntityElement $dataPrint){
        
        $array[$dataPrint->_getInternalPrint()]=$dataPrint;
        
        foreach($dataPrint as $elm){
            if(is_a($elm, "Footprint\DataPrint\Elements\AbstractEntityElement")){
                self::getEntitiesOfDataprint($array,$elm);
            }
        }
        
        return $array;
        
    }
    
    public static function hydrateColmuns($object,  AbstractEntityElement $dataPrint,$data,$nameMap=null){
        
        if(!$data)
            return false;
        
        // On this way we dont care about array or object
        if(is_array($data))
            $data=(object)$data;
        
        // only usable if is array, else we can free memory
        if(!is_array($nameMap))
            $nameMap=null;
        

        
        foreach($dataPrint as $v){

            if($v->isColumn()){
                $columnName=$v->getColumnName();

                /* @var $v AbstractElement */
                if(is_array($nameMap) && isset($nameMap[$columnName]))
                    $name=$nameMap[$columnName];
                else 
                    $name=$columnName;
                $v->set($object,$data->$name);
            }
        }   
    }
    
    public static function createInstace(AbstractEntityElement $dataPrint) {
        $className=$dataPrint->getClass();
        return new $className();
    }
    
}

?>
