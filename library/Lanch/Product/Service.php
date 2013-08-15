<?php
class Lanch_Product_Service
{
    private $_repository;
    private $_productEquipmentRepository;
    private $_groupRepository;
    private $_categoryRepository;

    public function __construct()
    {
        $this->_repository = new Lanch_Product_Repository();
        $this->_productEquipmentRepository = new Lanch_ProductEquipment_Repository();
        $this->_groupRepository = new Lanch_Group_Repository();
        $this->_categoryRepository = new Lanch_Category_Repository();
    }

    public function updateProduct(Array $data)
    {
        $productId = $data['id'];

        //update the product
        $options = array();
        $options['name'] = $data['name'];
        $options['price'] = $data['price'];
        $options['group_id'] = $data['group_id'];
        $options['category_id'] = $data['category_id'];
        $options['needs_waiters'] = (!isset($data['needs_waiters'])) ? 0 : 1;

        $this->_repository->updateByProductId($productId, $options);
        
        //update its associated equipment
        $equipment = array();
        $equipment = (isset($data['equipment'])) ? $data['equipment'] : array() ;

        $this->_productEquipmentRepository->updateByProductId($productId, $equipment);
    }

    public function insertProduct(Array $data)
    {
        //update the product
        $options = array();
        $options['name'] = $data['name'];
        $options['price'] = $data['price'];
        $options['group_id'] = $data['group_id'];
        $options['category_id'] = $data['category_id'];
        $options['needs_waiters'] = (!isset($data['needs_waiters'])) ? 0 : 1;

        $productId = $this->_repository->insertProduct($options);
        
        //update its associated equipment
        $equipment = array();
        $equipment = (isset($data['equipment'])) ? $data['equipment'] : array() ;

        $this->_productEquipmentRepository->updateByProductId($productId, $equipment);
    }

    public function deleteProduct($productId)
    {
        return $this->_repository->deleteProduct($productId);
    }

    public function getProductsHierarchicallyByCategoryAndGroup()
    {
        $out = array();
        $products = $this->_repository->getProducts();
        $groups = $this->_groupRepository->getGroups();
        $categories = $this->_categoryRepository->getCategories();
        
        foreach ($products as $product) {
            if (!empty($groups[$product->getGroupId()])) {
                if (!empty($categories[$product->getCategoryId()])) {
                    $out[$product->getCategoryId()][$product->getGroupId()][$product->getId()] = $product;    
                }
            }
        }

        return $out;
    }

    public function isValidProductData(Array $data)
    {
        $messages = array();

        return $messages;
    }
}