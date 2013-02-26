<?php


use Footprint\Entity\EntityInterface;
use Footprint\DataPrint\DataPrintCollection;
use Footprint\DataPrint\Elements\AbstractEntityElement;

use Footprint\DataPrint\Elements\Column;

class Address{
    
    private $id;
    private $idCustomer;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdCustomer() {
        return $this->idCustomer;
    }

    public function setIdCustomer($idCustomer) {
        $this->idCustomer = $idCustomer;
    }


}