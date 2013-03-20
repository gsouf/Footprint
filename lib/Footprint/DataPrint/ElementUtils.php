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
        
        if(AbstractEntityElement::LINK_BACKPORT!==$dataPrint->getLinkMode()){
            foreach($dataPrint as $elm){
                if(is_a($elm, "Footprint\DataPrint\Elements\AbstractEntityElement")){
                    self::getEntitiesOfDataprint($array,$elm);
                }
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
    
    /**
     * 
     * will search in the given array if an object of the array matches with the given primarytrace/dataprint
     * 
     * @param \Footprint\DataPrint\Elements\AbstractEntityElement $dataPrint the dataprint from which the primary comes
     * @param type $primaryTrace the required primarytrace
     * @param Object|array $searched if it is array (must be an array of the searched class) will search into each element of the array else will look wether the object matches
     */
    public static function hasThisInstace(AbstractEntityElement $dataPrint,$primaryTrace,$searched){
        if(is_a($searched, $dataPrint->getClass())){ // if it is the same class
            return $dataPrint->getPrimaryTrace($searched)==$primaryTrace; // and the same primary trace
        
            
        }else if(is_array($searched)){ 
            foreach($searched as $object){ //if it is an array we look with recursion
                if(self::hasThisInstace($dataPrint, $primaryTrace, $object))
                    return true;
            }
        }
        
        return false;
    }
    
}

?>
