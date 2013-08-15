<?php
class Lanch_Group_Service
{
    private $_repository;

    public function __construct()
    {
        $this->_repository = new Lanch_Group_Repository();
    }

    public function isValidGroupData(Array $data)
    {
        return array();
    }

    public function updateGroup(Array $data)
    {
        $groupId = $data['id'];

        $options = array();
        $options['name'] = $data['name'];

        $this->_repository->updateByGroupId($groupId, $options);
    }

    public function insertGroup(Array $data)
    {
        return $this->_repository->insertGroup($data);
    }
}