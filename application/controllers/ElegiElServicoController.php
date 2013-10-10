<?php
class ElegiElServicoController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('front');
    }

    public function indexAction()
    {
        $comboRepository = new Lanch_Combo_Repository();
        $comboPriceService = new Lanch_Combo_Price_Service();

        $combos = $comboRepository->getCombos();
        $comboPrices = $comboPriceService->getTotalPricesBySizeAndComboId($combos);

        $this->view->combos = $combos;
        $this->view->comboPrices = $comboPrices;
    }

    public function verAction()
    {
        $comboId = $this->getRequest()->getParam('comboId');
        $size = $this->getRequest()->getParam('size');

        $comboRepository = new Lanch_Combo_Repository();
        $productRepository = new Lanch_Product_Repository();
        $comboPriceService = new Lanch_Combo_Price_Service();

        $combo = $comboRepository->getComboById($comboId);
        $comboTotalPrice = $comboPriceService->getTotalPriceBySize($comboId, $size);
        $products = $productRepository->getProducts();
        $combos = $comboRepository->getCombos();

        $this->view->combo = $combo;
        $this->view->comboTotalPrice = $comboTotalPrice;
        $this->view->products = $products;
        $this->view->size = $size;
        $this->view->combos = $combos;
    }

    public function personalizarAction()
    {
        $comboId = $this->getRequest()->getParam('comboId');
        $size = $this->getRequest()->getParam('size');

        $comboRepository = new Lanch_Combo_Repository();
        $productService = new Lanch_Product_Service();
        $groupRepository = new Lanch_group_Repository();
        $categoryRepository = new Lanch_Category_Repository();
        $orderService = new Lanch_Order_Service();
 
        $combo = $comboRepository->getComboById($comboId);
        $combos = $comboRepository->getCombos();
        $products = $productService->getProductsHierarchicallyByCategoryAndGroup();
        $groups = $groupRepository->getGroups();
        $categories = $categoryRepository->getCategories(); 
        $order = $orderService->getOrder($size, $comboId);

        $this->view->size = $size;
        $this->view->combo = $combo;
        $this->view->combos = $combos;
        $this->view->products = $products;
        $this->view->groups = $groups;
        $this->view->categories = $categories;
        $this->view->order = $order;
    }

    public function savePersonalProductSelectionAjaxAction()
    {
        $comboId = $this->getRequest()->getParam('comboId');
        $checkedProductIds = $this->getRequest()->getParam('checkedProductIds');
        $guests = $this->getRequest()->getParam('guests');

        $comboProductRepository = new Lanch_ComboProduct_Repository();

        $comboProductRepository->savePersonalProductIdsByComboId($comboId, $checkedProductIds);

        exit(0);
    }

    public function getPersonalProductSelectionAjaxAction()
    {
        $comboId = $this->getRequest()->getParam('comboId');

        $comboProductRepository = new Lanch_ComboProduct_Repository();

        $productIds = $comboProductRepository->getPersonalProductIdsByComboId($comboId);

        echo json_encode($productIds);

        exit(0);
    }
}