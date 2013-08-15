<?php
class Lanch_ComboProduct_Repository
{
    private $_gateway;

    public function __construct()
    {
        $this->_gateway = new Lanch_ComboProduct_Gateway();
    }

    public function updateByComboId($comboId, $products)
    {
        //clear all existing associations
        $this->_gateway->deleteByComboId($comboId);

        //insert new associations
        foreach ($products as $size => $productIds) {
            foreach ($productIds as $productId => $null) {
                $this->_gateway->insertComboProduct($comboId, $productId, $size);
            }
        }
    }

    public function getProductsByComboId($comboId)
    {
        $comboProducts = array();

        //Add the combo products that are set in the database
        $rows = $this->_gateway->getProductsByComboId($comboId);
        foreach ($rows as $row) {
            $comboProducts[] = new Lanch_ComboProduct($row);
        }

        //Add the combo products that were set by the user
        $rows = $this->_gateway->getPersonalProductsByComboId($comboId);
        foreach ($rows as $row) {
            $comboProducts[] = new Lanch_ComboProduct($row);
        }

        return $comboProducts;
    }

    public function getPersonalProductIdsByComboId($comboId)
    {
        $out = $this->_gateway->getPersonalProductIdsByComboId($comboId);
        return $out;
    }

    public function savePersonalProductIdsByComboId($comboId, Array $checkedProductIds)
    {
        $this->_gateway->savePersonalProductIdsByComboId($comboId, $checkedProductIds);
    }
}