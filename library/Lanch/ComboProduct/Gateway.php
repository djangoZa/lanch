<?php
class Lanch_ComboProduct_Gateway
{
    private $_db;
    private $_session;

    public function __construct()
    {
        $this->_db = Lanch_Db::factory('mysql');

        try{
            $this->_session = new Zend_Session_Namespace('combo_personal_product_selection');
        } catch (Exception $e){
            //do nothing
        }
    }

    public function deleteByComboId($comboId)
    {
        $this->_db->query('DELETE FROM combo_product WHERE combo_id = ?', $comboId);
    }

    public function insertComboProduct($comboId, $productId, $size)
    {
        return $this->_db->insert('combo_product', array(
            'combo_id' => $comboId,
            'product_id' => $productId,
            'size' => $size
        ));
    }

    public function getProductsByComboId($comboId)
    {
        $select = $this->_db->select();
        $select->from('combo_product');
        $select->where('combo_id = ?', $comboId);

        return $this->_db->fetchAll($select);
    }

    public function getPersonalProductsByComboId($comboId)
    {
        $out = array();

        if (!empty($this->_session->{$comboId})) {
            foreach ($this->_session->{$comboId} as $productId) {
                $out[] = array(
                    'combo_id' => $comboId,
                    'product_id' => $productId,
                    'size' => 'personal'
                );
            }
        }

        return $out;
    }

    public function getPersonalProductIdsByComboId($comboId)
    {
        $out = array();

        if (!empty($this->_session->{$comboId})) {
            $out = $this->_session->{$comboId};
        }

        return $out;
    }

    public function savePersonalProductIdsByComboId($comboId, Array $checkedProductIds)
    {
        $this->_session->{$comboId} = $checkedProductIds;
    }
}