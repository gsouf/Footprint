<?php 
    include('autoloader.php');
    
    use \Footprint\DataPrint\Elements\Column;
    use Zend\Db\Adapter\Adapter;
    use \Footprint\Sql\SelectGenerator;
    use Footprint\Scanner\DBScanner;
    
    
    
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
    
    $scanner=new DBScanner($adapter, $user->getDataPrint(), null);
    
    $scanner->find($adapter, $user->getDataPrint(), null);
    //var_dump($select->getSqlString());
    
    echo PHP_EOL;