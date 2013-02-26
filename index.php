<?php 
    include('autoloader.php');
    
    use \Footprint\DataPrint\Elements\Column;
    use Zend\Db\Adapter\Adapter;
    use Zend\Db\Sql\Sql;
    use \Footprint\Sql\SelectGenerator;
    use Footprint\Sql\DBScanner;
    
    
    
    $user=new User("a@a.a","aaa");
    
    
    $dataPrint=$user->getDataPrint();
    

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
    var_dump($g->getSelect()->getSqlString());
    
    //$scanner=new DBScanner($adapter, $user->getDataPrint(), null);
    
    //$scanner->find($adapter, $user->getDataPrint(), null);
    //var_dump($select->getSqlString());
    
    echo PHP_EOL;