<?php

namespace Footprint\DataPrint\Elements;

use Footprint\Entity\EntityInterface;

/**
 * Description of Column
 *
 * @author Sneakybobito
 */
class AbstractEntityElement extends AbstractElement{
    
    public function hydrateProperty(EntityInterface $entity,$data) {
        $this->set($entity, $data);
    }

}

?>