<?php

namespace Footprint\Sql\Generator;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\AbstractSql;
use Zend\Db\Adapter\Adapter;
use Footprint\DataPrint\DataPrint as DataPrintCollection;

/**
 * Class to populate options like limit,order, where... in Zend\Db\Sql\AbstractSql from an option array
 *
 * @author SneakyBobito
 */
class OptionFactory {
    private $options;
    
    /**
     * 
     * @param array $options associative array of options (key is option name)
     */
    function __construct($options) {
        $this->options = $options;
    }
    
    public function renderTo($query){
        foreach($this->options as $k=>$opt){
            switch ($k){
                case "where":
                    $this->where($query,$opt);
                    break;
                case "limit":
                    $this->limit($query,$opt);
                case "offset":
                    $this->offset($query,$opt);
                
                // TODO : Warning when default is reached
            }
        }
    }

    public function where($query,$opt){
        $query->where($opt);
    }
    public function limit($query,$opt){
        $query->limit($opt);
    }
    public function offset($query,$opt){
        $query->offset($opt);
    }


}

?>