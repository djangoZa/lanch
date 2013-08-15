<?php
class Lanch_Category
{
    private $_id;
    private $_name;

    public function __construct(Array $data)
    {
        $this->_id = $data['id'];
        $this->_name = $data['name'];
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getName()
    {
        return $this->_name;
    }
}