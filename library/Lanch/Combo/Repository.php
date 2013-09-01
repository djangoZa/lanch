<?php
class Lanch_Combo_Repository
{
    private $_gateway;
    private $_comboProductRepository;
    private $_comboPriceService;
    private $_productRepository;

    public function __construct()
    {
        
        $this->_gateway = new Lanch_Combo_Gateway();
        $this->_comboProductRepository = new Lanch_ComboProduct_Repository();
        $this->_productRepository = new Lanch_Product_Repository();
        
        if(realpath(dirname(__FILE__) . '/../../../public_html/uploads/combo_images') != false) {
            //we are on the live server
            $this->_imageUploadPath = realpath(dirname(__FILE__) . '/../../../public_html/uploads/combo_images') . '/';
        } else {
            //we are on the dev server
            $this->_imageUploadPath = realpath(dirname(__FILE__) . '/../../../public/uploads/combo_images') . '/';
        }
    }

    public function getCombos()
    {
        $combos = array();
        $rows = $this->_gateway->getCombos();

        foreach ($rows as $row) {
            $combos[$row['id']] = $this->_constructCombo($row);
        }

        return $combos;
    }

    public function getComboById($comboId)
    {
        $row = $this->_gateway->getComboById($comboId);
        $combo = $this->_constructCombo($row);
        return $combo;
    }

    public function insertCombo(Array $data)
    {
        return $this->_gateway->insertCombo($data);
    }

    public function updateComboById($comboId, Array $data)
    {
        return $this->_gateway->updateComboById($comboId, $data);
    }

    public function deleteComboById($comboId)
    {
        return $this->_gateway->deleteComboById($comboId);
    }

    public function deleteImageFileByComboId($comboId)
    {
        $row = $this->_gateway->getComboById($comboId);
        $imageName = $row['image_name'];
        $pathToImage = $this->_imageUploadPath . $imageName;
        unlink($pathToImage);
    }

    public function saveImageNameByComboId($comboId, $imageName)
    {
        return $this->_gateway->saveImageNameByComboId($comboId, $imageName);
    }
    
    public function updateActiveCategoriesByComboId($comboId, $activeCategories)
    {
        return $this->_gateway->updateActiveCategoriesByComboId($comboId, $activeCategories);
    }

    public function storeComboImage(Array $files)
    {
        $uniqueName = time() . rand(1,100) . uniqid();
        $targetPath = $this->_imageUploadPath . $uniqueName;
        move_uploaded_file($files['image']['tmp_name'], $targetPath);
        return $uniqueName;
    }

    private function _constructCombo(Array $row)
    {
        $combo = new Lanch_Combo($row);

        //set the selected product ids
        $comboProducts = $this->_comboProductRepository->getProductsByComboId($combo->getId());
        $combo->setSelectedProductsIds($comboProducts);
        
        //set the minimum waiters
        $combo->setMinimumWaiters(ceil($combo->getMinimimGuests() / $combo->getGuestsPerWaiter()));

        return $combo;
    }
}