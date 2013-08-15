<?php
class Lanch_Group_Gateway
{
    private $_db;

    public function __construct()
    {
        $this->_db = Lanch_Db::factory('mysql');
    }

    public function getGroups()
    {
        $select = $this->_db->select();
        $select->from('groups');
        $select->where('deleted = ?', 0);

        return $this->_db->fetchAll($select);
    }

    public function getGroupById($groupId)
    {
        $select = $this->_db->select();
        $select->from('groups');
        $select->where('id = ?', $groupId);

        return $this->_db->fetchRow($select);
    }

    public function updateGroupById($groupId, Array $options)
    {
        return $this->_db->update('groups', array('name' => $options['name']), 'id = ' . $groupId);
    }

    public function insertGroup(Array $data)
    {
        $this->_db->insert('groups', array(
            'name' => $data['name']
        ));

        return $this->_db->lastInsertId();
    }

    public function deleteGroupById($groupId)
    {
        $this->_db->query('UPDATE groups SET deleted = 1 WHERE id = ' . $groupId);
    }
}