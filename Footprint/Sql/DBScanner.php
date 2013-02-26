<?php

namespace Footprint\Sql;

use Footprint\Sql\Generator\SelectGenerator;
use Footprint\DataPrint\DataPrintCollection;
use Footprint\Entity\Hydrater;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

/**
 * Description of DBScanner
 *
 * @author SneakyBobito
 */
class DBScanner {
    

    
    public function __construct() {}
    
    
    public function find(Adapter $adapter,DataPrintCollection $dataPrint,$depthMode=null, $options=null){
        $sql=new Sql($adapter);
        $generator=new SelectGenerator($sql,$dataPrint,$depthMode, $options);
        $select=$generator->generate();
        
        $result=$sql->prepareStatementForSqlObject($select)->execute();
        
        $HydratedObjects=array();
        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $hydrater=new Hydrater();
            
            $resultSet = new ResultSet;
            $resultSet->initialize($result);
                        
            foreach ($resultSet as $row) {
                $primaryTrace=$dataPrint->getPrimaryTrace($row,true);
                if(!isset($HydratedObjects[$primaryTrace])){
                    $className=$dataPrint->getClass();
                    $HydratedObjects[$primaryTrace]=new $className();
                }
                
                $hydrater->hydrate($HydratedObjects[$primaryTrace],$dataPrint,$row);
            }
        }
        return $HydratedObjects;
        
    }
    
    
}

?>
