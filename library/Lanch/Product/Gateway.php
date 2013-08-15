<?php
class Lanch_Product_Gateway
{
    private $_db;

    public function __construct()
    {
        $this->_db = Lanch_Db::factory('mysql');
    }

    public function getNonDeletedProducts()
    {
        $select = $this->_db->select();
        $select->from('products');
        $select->where('deleted = ?', 0);
        $select->order('category_id DESC');
        $select->order('group_id DESC');

        $rows = $this->_db->fetchAll($select);

        return $rows;
    }

    public function getProductById($productId)
    {
        $select = $this->_db->select();
        $select->from('products');
        $select->where('id = ?', $productId);

        $row = $this->_db->fetchRow($select);

        return $row;
    }

    public function getProductsByComboId($comboId)
    {
        $select = $this->_db->select();
        $select->from('products');
        $select->where('combo_id = ?', $comboId);

        $rows = $this->_db->fetchAll($select);

        return $rows;
    }

    public function updateByProductId($id, Array $options)
    {
        return $this->_db->update('products', $options, 'id = ' . $id);
    }

    public function insertProduct(Array $options)
    {
        $this->_db->insert('products', array(
            'id' => null,
            'name' => $options['name'],
            'price' => $options['price'],
            'group_id' => $options['group_id'],
            'category_id' => $options['category_id'],
            'needs_waiters' => $options['needs_waiters']
        ));

        return $this->_db->lastInsertId();
    }

    public function deleteProduct($productId)
    {
        return $this->_db->update('products', array('deleted' => 1), 'id = ' . $productId);
    }
}