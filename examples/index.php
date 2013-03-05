<?php 
    include('autoloader.php');
    
    use \Footprint\DataPrint\Elements\Column;
    use Zend\Db\Adapter\Adapter;
    use Zend\Db\Sql\Sql;
    use Zend\Db\ResultSet\ResultSet;
    use \Footprint\Sql\Generator\SelectGenerator;
    use Footprint\Sql\Generator\InsertGenerator;
    use Footprint\Sql\Generator\UpdateGenerator;
    use Footprint\Sql\Reader\SelectResultReader;
    use Footprint\Sql\DBScanner;
    use Footprint\DataPrint\InternalPrintIterator;
    
    use Exemple\Entity\User;
    use Exemple\Dataprint\UserPrint;
    use Exemple\Dataprint\AddressPrint;
       
    $adapter=new Adapter(array(
            'driver'   => 'Mysqli',
            'database' => 'footprint',
            
            'options'  => array('buffer_results' => true),
            'username' => 'root',
            'password' => 'root',
            /* MAMP * /
            'host'     => '127.0.0.1',
            'port'     => '8889',
            /* MAMP */
        ));
    $sql=new Sql($adapter);
    
    $dataPrint=new UserPrint();
   // $dataPrint->joinAddress();
    
    
    $db=new DBScanner($adapter);
    var_dump($db->select($dataPrint));
    
    /*  SELECT * /
    
    $g=new SelectGenerator($sql, $dataPrint);
    $g->generate();
    $result=$sql->prepareStatementForSqlObject($g->getSelect())->execute();
    
    $resultSet = new ResultSet;
    $resultSet->initialize($result);


    $reader=new SelectResultReader($dataPrint, $resultSet);
    $users=$reader->buff();

    var_dump($users);
    
    /**/
    
    
    
    /* INSERT * /
    
    $user=new User();
    $user->setEmail("blabla@sneaky.org");
    $user->setLastLogin("2012-01-01");
    $user->setRegisterDate("2012-01-01");
    
    $g=new InsertGenerator($sql,$dataPrint);
    $insert=$g->generate($user)->getInsert();
    $sql->prepareStatementForSqlObject($insert)->execute();
    /**/
    
    
    /* UPDATE * /
    
    $user=new User();
    $user->setId(2);
    $user->setEmail("blabla@sneaky.org");
    $user->setLastLogin("2012-01-01");
    $user->setRegisterDate("2012-01-01");
    
    $g=new UpdateGenerator($sql,$dataPrint);
    $update=$g->generate($user,array("date_last_login"))->getUpdate();
    $sql->prepareStatementForSqlObject($update)->execute();
    /**/
    

    //var_dump($dataPrint->getElementByInternalPrint(new InternalPrintIterator("0$\$__6")));

   //$scanner=new DBScanner($adapter, $user->getDataPrint(), null);

   //$scanner->find($adapter, $user->getDataPrint(), null);
   //var_dump($select->getSqlString());

   echo PHP_EOL;