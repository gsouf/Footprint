<?php

namespace Footprint\Sql;

use Footprint\Sql\Generator\SelectGenerator;
use Footprint\DataPrint\DataPrint as DataPrintCollection;
use Footprint\DataPrint\Elements\AbstractEntityElement;
use Footprint\Sql\Reader\SelectResultReader;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

use Zend\Db\ResultSet\ResultSet;

/**
 * Description of DBScanner
 *
 * @author SneakyBobito
 */
class DBScanner {
    
    
    /**
     *
     * @var Adapter 
     */
    private $adapter;

    
    function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    
    public function select(AbstractEntityElement $dataPrint, $options=null){
        
        $adapter=$this->adapter;
        
        $sql=new Sql($adapter);
        $g=new SelectGenerator($sql, $dataPrint);
        $g->generate();
        $result=$sql->prepareStatementForSqlObject($g->getSelect())->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($result);


        $reader=new SelectResultReader($dataPrint, $resultSet);
        
        $returnResult=$reader->buff();

        return $returnResult;
    }
    
    
}

?>
