<?php

namespace Footprint\DataPrint\Elements;

use Footprint\Entity\EntityInterface;

/**
 * Description of Parent
 *
 * @author Sneakybobito
 */
class Parent extends AbstractElement{
    
    public function hydrateProperty(EntityInterface $entity,$data) {
        $this->set($entity, $data);
    }

}

?>
