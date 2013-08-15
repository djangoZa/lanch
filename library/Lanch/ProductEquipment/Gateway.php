<?php
class Lanch_ProductEquipment_Gateway
{
    private $_db;

    public function __construct()
    {
        $this->_db = Lanch_Db::factory('mysql');
    }

    public function deleteByProductId($productId)
    {
        $this->_db->query('DELETE FROM product_equipment WHERE product_id = ?', $productId);
    }

    public function insertProductEquipment($productId, $equipmentId)
    {
        $this->_db->insert('product_equipment', array(
            'product_id' => $productId,
            'equipment_id' => $equipmentId
        ));
    }
}