<?php
class Lanch_Product
{
    private $_id;
    private $_name;
    private $_price;
    private $_groupId;
    private $_categoryId;
    private $_needsWaiters;
    private $_equipment = array();

    public function __construct($data)
    {
        $this->_id = $data['id'];
        $this->_name = $data['name'];
        $this->_price = $data['price'];
        $this->_categoryId = $data['category_id'];
        $this->_groupId = $data['group_id'];
        $this->_needsWaiters = $data['needs_waiters'];
    }

    public function setEquipment(Array $equipment)
    {
        foreach ($equipment as $row) {
            $this->_equipment[$row->getId()] = $row;
        }
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

    public function getGroupId()
    {
        return $this->_groupId;
    }

    public function getCategoryId()
    {
        return $this->_categoryId;
    }

    public function getNeedsWaiters()
    {
        return $this->_needsWaiters;
    }

    public function getEquipment()
    {
        return $this->_equipment;
    }
}