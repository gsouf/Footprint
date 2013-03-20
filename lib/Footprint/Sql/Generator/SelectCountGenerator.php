<?php

namespace Footprint\Sql\Generator;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Footprint\DataPrint\Elements\AbstractEntityElement;
use Footprint\Sql\Generator\OptionFactory;

/**
 * Class to generate Zend\Db\Sql\Select from the Footprint, the depth mode and the options
 *
 * @author SneakyBobito
 */

class SelectCountGenerator extends SelectGenerator{

    public function generate() {
        parent::generate();
        
        $this->getSelect()->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(*)')));
    }
    
}

?>
