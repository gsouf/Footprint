<?php

namespace Footprint\Entity;

/**
 *
 * @author SneakyBobito
 */
interface EntityInterface {
    
    /**
     * 
     * @return \Footprint\DataPrint\Elements\AbstractEntityElement
     */
    public function getDataPrint();
}

?>
