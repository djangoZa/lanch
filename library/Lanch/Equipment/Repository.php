<?php
class Lanch_Equipment_Repository
{
    private $_gateway;

    public function __construct()
    {
        $this->_gateway = new Lanch_Equipment_Gateway(); 
    }

    public function getEquipmentByProductId($productId)
    {
        $equipment = array();
        $rows = $this->_gateway->getEquipmentByProductId($productId);

        foreach ($rows as $row) {
            $equipment[] = new Lanch_Equipment($row);
        }

        return $equipment;
    }
    
    public function getEquipmentByProductIds(Array $productIds)
    {
        $out = array();

        $rows = $this->_gateway->getEquipmentByProductIds($productIds);

        foreach ($rows as $row) {
            $equipment = new Lanch_Equipment($row);
            $out[$equipment->getId()] = $equipment;
        }

        return $out;
    }

    public function getEquipment()
    {
        $equipment = array();
        $rows = $this->_gateway->getEquipment();

        foreach ($rows as $row) {
            $equipment[] = new Lanch_Equipment($row);
        }

        return $equipment;
    }

    public function getEquipmentById($equipmentId)
    {
        $row = $this->_gateway->getEquipmentById($equipmentId);
        $equipment = new Lanch_Equipment($row);
        return $equipment;
    }

    public function insertEquipment(Array $data)
    {
        return $this->_gateway->insertEquipment($data);
    }

    public function updateByEquipmentId($equipmentId, Array $options)
    {
        return $this->_gateway->updateByEquipmentId($equipmentId, $options);
    }

    public function deleteEquipmentById($equipmentId)
    {
        return $this->_gateway->deleteEquipmentById($equipmentId);
    }
}