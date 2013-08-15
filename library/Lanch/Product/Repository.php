<?php
class Lanch_Product_Repository
{
    private $_gateway;
    private $_equipmentRepository;

    public function __construct()
    {
        $this->_gateway = new Lanch_Product_Gateway();
        $this->_equipmentRepository = new Lanch_Equipment_Repository();
    }

    public function getProducts()
    {
        $products = array();
        $rows = $this->_gateway->getNonDeletedProducts();

        foreach ($rows as $row) {
            $products[$row['id']] = $this->_makeProduct($row);
        }

        return $products;
    }

    public function getProductById($productId)
    {
        $row = $this->_gateway->getProductById($productId);
        $product = $this->_makeProduct($row);
        return $product;
    }

    public function getProductsByComboId($comboId)
    {
        $products = array();
        $rows = $this->_gateway->getProductsByComboId($comboId);

        foreach ($rows as $row) {
            $products[] = $this->_makeProduct($row);
        }

        return $products;
    }

    public function updateByProductId($id, Array $options)
    {
        return $this->_gateway->updateByProductId($id, $options);
    }

    public function insertProduct(Array $options)
    {
        return $this->_gateway->insertProduct($options);
    }

    public function deleteProduct($productId)
    {
        return $this->_gateway->deleteProduct($productId);
    }

    private function _makeProduct(Array $row)
    {
        $product = new Lanch_Product($row);
        $product = $this->_attachEquipmentToProduct($product);
        return $product;
    }

    private function _attachEquipmentToProduct(Lanch_Product $product)
    {
        $equipment = $this->_equipmentRepository->getEquipmentByProductId($product->getId());
        $product->setEquipment($equipment);

        return $product;
    }
}