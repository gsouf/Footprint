<?php 
    include('autoloader.php');
    
    use \Footprint\DataPrint\Elements\Column;
    use Zend\Db\Adapter\Adapter;
    use Zend\Db\Sql\Sql;
    use Zend\Db\ResultSet\ResultSet;
    use \Footprint\Sql\Generator\SelectGenerator;
    use Footprint\Sql\Reader\SelectResultReader;
    use Footprint\Sql\DBScanner;
    use Footprint\DataPrint\InternalPrintIterator;
    
    use Exemple\Dataprint\UserPrint;
    use Exemple\Dataprint\AddressPrint;
    

    
       
    $up=new UserPrint("getCustomer","setCustomer");
    $up->setLinkMode(UserPrint::LINK_BACKPORT);

    $d1=new AddressPrint("getAdress","setAdress");
    $d1->setJoin(array("id_customer"=>"id_customer"));
    $d1->setLinkMode(UserPrint::LINK_JOIN);


    $dataPrint=new UserPrint();
    $dataPrint->add($d1);
    
    
    

    $adapter=new Adapter(array(
            'driver'   => 'Mysqli',
            'database' => 'footprint',
            // password and login into local
            'options'  => array('buffer_results' => true),
            'username' => 'root',
            'password' => 'root',
        ));
    $sql=new Sql($adapter);
    $g=new SelectGenerator($sql, $dataPrint);
    $g->generate();
    $result=$sql->prepareStatementForSqlObject($g->getSelect())->execute();
    
     $resultSet = new ResultSet;
     $resultSet->initialize($result);
     
     
     $reader=new SelectResultReader($dataPrint, $resultSet);
     $users=$reader->buff();
     
     var_dump($users);
     
     //var_dump($dataPrint->getElementByInternalPrint(new InternalPrintIterator("0$\$__6")));
    
    //$scanner=new DBScanner($adapter, $user->getDataPrint(), null);
    
    //$scanner->find($adapter, $user->getDataPrint(), null);
    //var_dump($select->getSqlString());
    
    echo PHP_EOL;