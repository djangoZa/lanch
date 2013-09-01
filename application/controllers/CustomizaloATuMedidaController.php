<?php
class CustomizaloATuMedidaController extends Zend_Controller_Action
{    
    public function init()
    {
        $this->_helper->layout->setLayout('front');
    }
    
    public function indexAction()
    {
        $comboId = $this->getRequest()->getParam('comboId');
        $size = $this->getRequest()->getParam('size');

        $comboRepository = new Lanch_Combo_Repository();
        $productRepository = new Lanch_Product_Repository();
        $equipmentRepository = new Lanch_Equipment_Repository();
        $orderService = new Lanch_Order_Service();
        
        $combos = $comboRepository->getCombos();
        $products = $productRepository->getProducts();
        $combo = $comboRepository->getComboById($comboId);
        $selectedProductIds = $combo->getSelectedProductIdsBySizeId($size);
        $equipment = $equipmentRepository->getEquipmentByProductIds($selectedProductIds);
        $order = $orderService->getOrderSession();

        $this->view->combos = $combos;
        $this->view->products = $products;
        $this->view->combo = $combo;
        $this->view->equipment = $equipment;
        $this->view->selectedProductIds = $selectedProductIds;
        $this->view->size = $size;
        $this->view->order = $order;
    }
}