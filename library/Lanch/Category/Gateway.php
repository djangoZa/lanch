<?php
class Lanch_Category_Gateway
{
    private $_db;

    public function __construct()
    {
        $this->_db = Lanch_Db::factory('mysql');
    }

    public function getCategories()
    {
        $select = $this->_db->select();
        $select->from('categories');
        $select->where('deleted = ?', 0);
        $select->order('order', 'ASC');

        return $this->_db->fetchAll($select);
    }

    public function getCategoryById($categoryId)
    {
        $select = $this->_db->select();
        $select->from('categories');
        $select->where('id = ?', $categoryId);

        return $this->_db->fetchRow($select);
    }

    public function updateCategoryById($categoryId, Array $options)
    {
        return $this->_db->update('categories', array('name' => $options['name']), 'id = ' . $categoryId);
    }

    public function insertCategory(Array $data)
    {
        $this->_db->insert('categories', array(
            'name' => $data['name']
        ));

        return $this->_db->lastInsertId();
    }

    public function deleteCategoryById($categoryId)
    {
        $this->_db->query('UPDATE categories SET deleted = 1 WHERE id = ' . $categoryId);
    }
}