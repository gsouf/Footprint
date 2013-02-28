<?php

namespace Footprint\DataPrint\Elements;

use Footprint\Entity\EntityInterface;

/**
 * Description of Column
 *
 * @author Sneakybobito
 */
class Column extends AbstractValueElement{
    
    public function __construct($columnName, $getter, $setter) {
        parent::__construct($getter, $setter);
        $this->isColumn(true);
        $this->setColumnName($columnName);
    }


}

?>
