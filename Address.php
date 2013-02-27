<?php


use Footprint\Entity\EntityInterface;
use Footprint\DataPrint\DataPrintCollection;
use Footprint\DataPrint\Elements\AbstractEntityElement;

use Footprint\DataPrint\Elements\Column;

class Address{
    
    private $id;
    private $idCustomer;
    private $customer;
    
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
    
    public function getCustomer() {
        return $this->customer;
    }

    public function setCustomer($customer) {
        $this->customer = $customer;
    }



}