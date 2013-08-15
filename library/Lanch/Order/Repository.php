<?php
class Lanch_Order_Repository
{
    private $_gateway;

    public function __construct()
    {
        $this->_gateway = new Lanch_Order_Gateway();
    }

    public function setOrderSession(Array $order)
    {
        $this->_gateway->setOrderSession($order);
    }
    
    public function saveCustomerDetailsToOrderSession(Array $details)
    {
        $row = $this->_gateway->getOrderSession();
        $row['customerDetails'] = $details;
        $this->_gateway->setOrderSession($row);
    }
    
    public function saveGuestsToOrderSession($guests)
    {
        $row = $this->_gateway->getOrderSession();
        $row['guests'] = $guests;
        $this->_gateway->setOrderSession($row);
    }

    public function getOrderSession()
    {
        $row = $this->_gateway->getOrderSession();
        $order = new Lanch_Order($row);
        return $order;
    }
}