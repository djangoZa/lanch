<?php
class Lanch_Booking_Adjustment
{
    private $_description;
    private $_amount;
    private $_date;
    
    public function __construct(Array $options)
    {
        $this->_description = $options['description'];
        $this->_amount = $options['amount'];
        $this->_date = $options['date'];
    }
    
    public function getDescription()
    {
        return $this->_description;
    }
    
    public function getAmount()
    {
        return $this->_amount;
    }
    
    public function getDate()
    {
        return $this->_date;
    }
}