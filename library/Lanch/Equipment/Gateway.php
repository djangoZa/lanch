<?php
class Lanch_Equipment_Gateway
{
    private $_db;

    public function __construct()
    {
        $this->_db = Lanch_Db::factory('mysql');
    }

    public function getEquipmentByProductId($productId)
    {
        $select = $this->_db->select();
        $select->from('product_equipment');
        $select->join('equipment', 'equipment.id = product_equipment.equipment_id');
        $select->where('product_id = ?', $productId);

        $rows = $this->_db->fetchAll($select);

        return $rows;
    }
    
    public function getEquipmentByProductIds(Array $productIds)
    {
        $select = $this->_db->select();
        $select->from('product_equipment');
        $select->join('equipment', 'equipment.id = product_equipment.equipment_id');
        $select->where('product_id IN (?)', $productIds);
        $select->group('equipment.id');

        $rows = $this->_db->fetchAll($select);

        return $rows;
    }

    public function getEquipment()
    {
        $select = $this->_db->select();
        $select->from('equipment');
        $select->where('deleted = ?', 0);

        $rows = $this->_db->fetchAll($select);

        return $rows;
    }

    public function getEquipmentById($equipmentId)
    {
        $select = $this->_db->select();
        $select->from('equipment');
        $select->where('id = ?', $equipmentId);

        $row = $this->_db->fetchRow($select);

        return $row;
    }

    public function insertEquipment(Array $data)
    {
        $this->_db->insert('equipment', array(
            'id' => null,
            'name' => $data['name'],
            'price' => $data['price']
        ));

        return $this->_db->lastInsertId();
    }

    public function updateByEquipmentId($equipmentId, Array $options)
    {
        return $this->_db->update('equipment', array('name' => $options['name'],'price' => $options['price']), 'id = ' . $equipmentId);
    }

    public function deleteEquipmentById($equipmentId)
    {
        return $this->_db->update('equipment', array('deleted' => 1), 'id = ' . $equipmentId);
    }
}