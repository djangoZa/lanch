<?php
class Lanch_Equipment
{
    private $_id;
    private $_name;
    private $_price;

    public function __construct($data)
    {
        $this->_id = $data['id'];
        $this->_name = $data['name'];
        $this->_price = $data['price'];
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getName()
    {
        return ucfirst($this->_name);
    }

    public function getPrice()
    {
        return $this->_price;
    }
}