<?php

namespace Footprint\DataPrint;


/**
 * Description of InternalPrintIterator
 *
 * @author SneakyBobito
 */
class InternalPrintIterator extends \ArrayIterator {
    
    public function __construct($InternalPrintString) {
        parent::__construct($this->decodeString($InternalPrintString));
    }
    
    /**
     * 
     * @param string $string
     * @return array
     */
    public function decodeString($stringParam){
        $string="";
        $finalArray=array();
        
        $arr=explode(Elements\AbstractElement::INTERNALPRINTTOKEN, $stringParam);
  

        foreach($arr as $v){
            if(Elements\AbstractElement::ROOTTOKEN!=$v){
                $string.=Elements\AbstractElement::INTERNALPRINTTOKEN;
            }
            $string.=$v;
            $finalArray[$string]=$v;
        }

        //return explode(Elements\AbstractElement::INTERNALPRINTTOKEN, $stringParam);
        return $finalArray;
    }
}

?>