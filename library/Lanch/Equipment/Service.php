<?php
class Lanch_Equipment_Service
{
    private $_repository;

    public function __construct()
    {
        $this->_repository = new Lanch_Equipment_Repository();
    }

    public function isValidEquipmentData(Array $data)
    {
        $messages = array();

        return $messages;
    }

    public function updateEquipment(Array $data)
    {
        $equipmentId = $data['id'];

        //update the product
        $options = array();
        $options['name'] = $data['name'];
        $options['price'] = $data['price'];

        $this->_repository->updateByEquipmentId($equipmentId, $options);
    }

    public function insertEquipment(Array $data)
    {
        $this->_repository->insertEquipment($data);
    }

    public function deleteEquipmentById($equipmentId)
    {
        $this->_repository->deleteEquipmentById($equipmentId);
    }
}