<?php
class Lanch_Booking_Line_Item
{
    private $_name;
    private $_price;
    private $_quantity;
    
    public function __construct(Array $options)
    {
        $this->_name = $options['name'];
        $this->_price = $options['price'];
        $this->_quantity = $options['quantity'];
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function getPrice()
    {
        return $this->_price;
    }
    
    public function getQuantity()
    {
        return $this->_quantity;
    }
    
    public function getTotal()
    {
        return $this->_price * $this->_quantity;
    }
}