<?php

namespace Footprint\DataPrint\Elements;

use Footprint\Entity\EntityInterface;

/**
 * Description of Column
 *
 * @author Sneakybobito
 */
abstract class AbstractValueElement extends AbstractElement{
    
    public function onSelect(\Footprint\Sql\SelectGenerator $selectGen) {
        $selectGen->addColumn($this->getColumnName(), $this->_getInternalPrint(),$this->getWrapper()->_getInternalPrint());
    }

}

?>
