<?php

namespace Footprint\DataPrint;


/**
 * Description of DataPrintCollection
 *
 * @author SneakyBobito
 */
class InternalPrintIterator extends \ArrayIterator {
    
    public function __construct($InternalPrintString) {
        parent::__construct($this->decodeString($string($InternalPrintString)));
    }
    
    /**
     * 
     * @param string $string
     * @return array
     */
    public function decodeString($string){
        return explode(Elements\AbstractElement::INTERNALPRINTTOKEN, $string);
    }
}

?>