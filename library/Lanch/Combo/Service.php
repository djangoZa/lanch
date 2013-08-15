<?php
class Lanch_Combo_Service
{
    private $_repository;
    private $_comboProductRepository;

    public function __construct()
    {
        $this->_repository = new Lanch_Combo_Repository();
        $this->_comboProductRepository = new Lanch_ComboProduct_Repository();
    }

    public function isValidComboData($data, $files)
    {
        $messages = array();

        return $messages;
    }

    public function insertCombo($data, $files)
    {
        //save the basic combo data
        $comboId = $this->_repository->insertCombo($data);

        //save the combo product associations
        $products = (!empty($data['products'])) ? $data['products'] : array();
        $this->_comboProductRepository->updateByComboId($comboId, $products);

        //store the image
        $imageName = $this->_repository->storeComboImage($files);

        //save the image name to the combo
        $this->_repository->saveImageNameByComboId($comboId, $imageName);

        return $comboId;
    }

    public function updateCombo($data, $files)
    {
        $comboId = $data['id'];

        //if there is an image being uploaded do the following:
        if (!empty($files['image']['size'])) {
            //1. upload it
            $imageName = $this->_repository->storeComboImage($files);
            //2. delete the existing one
            $this->_repository->deleteImageFileByComboId($comboId);
            //3. save new image name to the database
            $this->_repository->saveImageNameByComboId($comboId, $imageName);
        }

        //update the basic details of the combo
        $this->_repository->updateComboById($comboId, $data);

        //update the this combo's selected products
        $comboProducts = (!empty($data['combo_products'])) ? $data['combo_products'] : array();
        $this->_comboProductRepository->updateByComboId($comboId, $comboProducts);
    }

    public function deleteComboById($comboId)
    {
        $this->_repository->deleteComboById($comboId);
    }
}