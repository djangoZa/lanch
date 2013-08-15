<?php
class Lanch_ProductEquipment_Repository
{
    private $_gateway;

    public function __construct()
    {
        $this->_gateway = new Lanch_ProductEquipment_Gateway();
    }

    public function updateByProductId($productId, Array $equipment)
    {
        //remove all associations
        $this->_gateway->deleteByProductId($productId);

        if (!empty($equipment)) {
            //insert associations
            foreach ($equipment as $equipmentId => $null) {
                $this->_gateway->insertProductEquipment($productId, $equipmentId);
            }
        }
    }
}