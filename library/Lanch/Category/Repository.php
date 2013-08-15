<?php
class Lanch_Category_Repository
{
    private $_gateway;

    public function __construct()
    {
        $this->_gateway = new Lanch_Category_Gateway();
    }

    public function getCategories()
    {
        $categories = array();
        $rows = $this->_gateway->getCategories();

        foreach ($rows as $row)
        {
            $categories[$row['id']] = new Lanch_Category($row);
        }

        return $categories;
    }

    public function getCategoryById($categoryId)
    {
        $row = $this->_gateway->getCategoryById($categoryId);
        $category = new Lanch_Category($row);
        return $category;
    }

    public function updateCategoryById($categoryId, Array $data)
    {
        return $this->_gateway->updateCategoryById($categoryId, $data);
    }

    public function insertCategory(Array $data)
    {
        return $this->_gateway->insertCategory($data);
    }

    public function deleteCategoryById($groupId)
    {
        return $this->_gateway->deleteCategoryById($groupId);
    }
}