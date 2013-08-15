<?php
class Lanch_Order_Gateway
{
    private $_session;

    public function __construct()
    {
        $this->_session = new Zend_Session_Namespace('order');
    }

    public function getOrderSession()
    {
        $out = array();
        
        if (!empty($this->_session->order)) {
            $out = $this->_session->order;
        }

        return $out;
    }

    public function setOrderSession(Array $order)
    {
        $this->_session->order = $order;
    }
}