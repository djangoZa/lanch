<?php
class Lanch_Combo_Gateway
{
    private $_db;

    public function __construct()
    {
        $this->_db = Lanch_Db::factory('mysql');
    }

    public function getCombos()
    {
        $select = $this->_db->select();
        $select->from('combos');
        $select->where('deleted = ?', 0);

        $rows = $this->_db->fetchAll($select);

        return $rows;
    }

    public function getComboById($comboId)
    {
        $select = $this->_db->select();
        $select->from('combos');
        $select->where('id = ?', $comboId);

        return $this->_db->fetchRow($select);
    }

    public function insertCombo(Array $data)
    {
        $this->_db->insert('combos', array(
            'name' => $data['name'],
            'base_price' => $data['base_price'],
            'discount' => $data['discount'],
            'minimum_guests' => $data['minimum_guests']
        ));

        return $this->_db->lastInsertId();
    }

    public function updateComboById($comboId, Array $data)
    {
        return $this->_db->update('combos', array(
            'name' => $data['name'],
            'base_price' => $data['base_price'],
            'discount' => $data['discount'],
            'minimum_guests' => $data['minimum_guests']
        ), 'id = ' . $comboId);
    }

    public function deleteComboById($comboId)
    {
        return $this->_db->update('combos', array('deleted' => 1), 'id = ' . $comboId);
    }

    public function saveImageNameByComboId($comboId, $imageName)
    {
        return $this->_db->update('combos', array('image_name' => $imageName), 'id = ' . $comboId);
    }
}