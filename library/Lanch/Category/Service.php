<?php
class Lanch_Category_Service
{
    private $_repository;

    public function __construct()
    {
        $this->_repository = new Lanch_Category_Repository();
    }

    public function isValidCategoryData(Array $data)
    {
        return array();
    }

    public function updateCategory(Array $data)
    {
        $categoryId = $data['id'];

        $options = array();
        $options['name'] = $data['name'];

        $this->_repository->updateCategoryById($categoryId, $options);
    }

    public function insertCategory(Array $data)
    {
        return $this->_repository->insertCategory($data);
    }
}