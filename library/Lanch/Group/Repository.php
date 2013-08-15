<?php
class Lanch_Group_Repository
{
    private $_gateway;

    public function __construct()
    {
        $this->_gateway = new Lanch_Group_Gateway();
    }

    public function getGroups()
    { 
        $groups = array();
        $rows = $this->_gateway->getGroups();

        foreach ($rows as $row) {
            $groups[$row['id']] = new Lanch_Group($row);
        }

        return $groups;
    }

    public function getGroupById($groupId)
    {
        $row = $this->_gateway->getGroupById($groupId);
        $group = new Lanch_Group($row);
        return $group;
    }

    public function updateByGroupId($groupId, Array $options)
    {
        return $this->_gateway->updateGroupById($groupId, $options);
    }

    public function insertGroup(Array $data)
    {
        return $this->_gateway->insertGroup($data);
    }

    public function deleteGroupById($groupId)
    {
        return $this->_gateway->deleteGroupById($groupId);
    }
}