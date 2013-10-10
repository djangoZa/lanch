<?php
class Lanch_Order_Gateway
{
    private $_session;

    public function __construct()
    {
        $this->_session = new Zend_Session_Namespace('order');
    }

    public function getOrderSession(Lanch_Combo $combo, $size)
    {
        $out = array();
        
        $orderSessionExists = !empty($this->_session->order);
        $isTheSameComboId = $this->_session->order['comboId'] == $combo->getId();
        $isTheSameSize = $this->_session->order['size'] == $size;
        
        if ($orderSessionExists)
        {
            $out = $this->_session->order;
            
            if (!$isTheSameComboId && !$isTheSameSize)
            {
                $out['comboId'] = $combo->getId();
                $out['size'] = $size;
                $out['guests'] = $combo->getMinimimGuests();
                $out['waiters'] = $combo->getMinimumWaiters();
            }
            
        } else {
            $out['comboId'] = $combo->getId();
            $out['size'] = $size;
            $out['guests'] = $combo->getMinimimGuests();
            $out['waiters'] = $combo->getMinimumWaiters();
            $out['formalDishes'] = false;
        }
        
        return $out;
    }
    
    public function getOrderInSession()
    {
        $out = $this->_session->order;
        return $out;
    }

    public function setOrderSession(Array $order)
    {
        $this->_session->order = $order;
    }
}