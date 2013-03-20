<?php

namespace Footprint\Sql;

use Footprint\Sql\Generator\SelectGenerator;
use Footprint\Sql\Generator\SelectCountGenerator;
use Footprint\Sql\Generator\InsertGenerator;
use Footprint\DataPrint\DataPrint as DataPrintCollection;
use Footprint\DataPrint\Elements\AbstractEntityElement;
use Footprint\Sql\Reader\SelectResultReader;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

use Zend\Db\ResultSet\ResultSet;


use Footprint\Sql\Generator\OptionFactory;


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
        
        //generate sql string
        $g=new SelectGenerator($sql, $dataPrint);
        $g->generate();
        
        // execute the request
        $result=$sql->prepareStatementForSqlObject($g->getSelect())->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($result);

        // fill in objects
        $reader=new SelectResultReader($dataPrint, $resultSet);

        $returnResult=$reader->buff();

        return $returnResult;
    }
    
    public function count(AbstractEntityElement $dataPrint, $options=null){
        
        $adapter=$this->adapter;
        
        $sql=new Sql($adapter);
        $g=new SelectCountGenerator($sql, $dataPrint,$options);
        $g->generate();
        
        
        
        $result=$sql->prepareStatementForSqlObject($g->getSelect())->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($result);

        foreach($resultSet as $row){
            return $row['count'];
        }
        

        return 0;
    }
    
    public function insert(AbstractEntityElement $dataPrint,$entity, $options=null){
        
        $adapter=$this->adapter;
        
        $sql=new Sql($adapter);

        $g=new InsertGenerator($sql,$dataPrint);
        $insert=$g->generate($entity)->getInsert();
        $sql->prepareStatementForSqlObject($insert)->execute();
    }
    
    
}

?>
