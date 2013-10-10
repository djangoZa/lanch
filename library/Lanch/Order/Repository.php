<?php
class Lanch_Order_Repository
{
    private $_gateway;
    private $_comboRepository;

    public function __construct()
    {
        $this->_gateway = new Lanch_Order_Gateway();
        $this->_comboRepository = new Lanch_Combo_Repository();
    }

    public function setOrderSession(Array $order)
    {
        $this->_gateway->setOrderSession($order);
    }
    
    public function saveCustomerDetailsToOrderSession(Array $details)
    {
        $row = $this->_gateway->getOrderInSession();
        $row['customerDetails'] = $details;
        $this->_gateway->setOrderSession($row);
    }
    
    public function saveGuestsToOrderSession($guests)
    {
        $row = $this->_gateway->getOrderSession();
        $row['guests'] = $guests;
        $this->_gateway->setOrderSession($row);
    }

    public function getOrderSession($comboId, $size)
    {
        $combo = $this->_comboRepository->getComboById($comboId);
        $row = $this->_gateway->getOrderSession($combo, $size);
        $order = new Lanch_Order($row);
        return $order;
    }
    
    public function getOrderInSession()
    {
        $row = $this->_gateway->getOrderInSession();
        $order = new Lanch_Order($row);
        return $order;
    }
}