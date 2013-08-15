<?php
class AdminController extends Zend_Controller_Action
{
    public function init()
    {
        $authenticationService = new Lanch_Authentication_Service();
        $isAuthenticated = $authenticationService->isAuthenticated();

        if($isAuthenticated == false) {
            if ($this->getRequest()->getActionName() != 'login') {
                $this->_redirect('/admin/login');
            }
        } else {
            $this->_helper->layout->setLayout('admin');
            if ($this->getRequest()->getActionName() == 'login' || $this->getRequest()->getActionName() == 'index') {
                $this->_redirect('/admin/combos');
            }
        }
    }

    public function loginAction()
    {
        $this->_helper->layout->setLayout('login');

        if (!empty($_POST)) {

            $username = filter_var($_POST['username']);
            $password = filter_var($_POST['password']);

            $authenticationService = new Lanch_Authentication_Service();
            $result = $authenticationService->authenticate($username, $password);

            if ($result == true) {
                $this->_redirect('admin/combos');
            } else {
                $this->view->message = 'La combinación de su nombre de usuario y la contraseña no es válida';
            }

        }
    }
    
    //INVOICES
    public function invoicesAction()
    {
        $bookingService = new Lanch_Booking_Service();
        $unseenBookings = $bookingService->getAllUnseenBookings();
        $this->view->unseenBookings = $unseenBookings;
    }
    
    public function addInvoiceAdjustmentPopupAction()
    {
        $this->_helper->layout->setLayout('popup');
        
        $bookingId = $this->getRequest()->getParam('bookingId');
        
        $this->view->bookingId = $bookingId;
    }
    
    public function addInvoiceAdjustmentAjaxAction()
    {
        $bookingId = $this->getRequest()->getParam('bookingId');
        $bookingService = new Lanch_Booking_Service();
        $booking = $bookingService->getBookingById($bookingId);
        
        $options = $this->getRequest()->getParam('options');

        $booking = $bookingService->addAdjustment($booking, $options);
        $bookingService->updateBooking($booking);
        
        exit(1);
    }
    
    public function editInvoiceAction()
    {
        $bookingId = $this->getRequest()->getParam('invoiceId');
        $bookingService = new Lanch_Booking_Service();
        $booking = $bookingService->getBOokingById($bookingId);
        $this->view->booking = $booking;
    }

    //PRODUCTS
    public function productsAction()
    {
        $productRepository = new Lanch_Product_Repository();
        $groupRepository = new Lanch_Group_Repository();
        $categoryRepository = new Lanch_Category_Repository();

        $products = $productRepository->getProducts();
        $groups = $groupRepository->getGroups();
        $categories = $categoryRepository->getCategories();

        $this->view->products = $products;
        $this->view->groups = $groups;
        $this->view->categories = $categories;
    }

    public function editProductAction()
    {
        $productId = $this->getRequest()->getParam('productId');

        $this->view->messages = array();

        if(!empty($_POST)) {

            $productService = new Lanch_Product_Service();
            $messages = $productService->isValidProductData($_POST);

            if (empty($messages)) {
                $productService->updateProduct($_POST);
                $this->_redirect('/admin/products');
                exit(1);
            } else {
                $this->view->messages = $messages;
            }

        }

        $productRepository = new Lanch_Product_Repository();
        $equipmentRepository = new Lanch_Equipment_Repository();
        $groupRepository = new Lanch_Group_Repository();
        $categoryRepository = new Lanch_Category_Repository();

        $product = $productRepository->getProductById($productId);
        $equipment = $equipmentRepository->getEquipment();
        $groups = $groupRepository->getGroups();
        $categories = $categoryRepository->getCategories();

        $this->view->product = $product;
        $this->view->equipment = $equipment;
        $this->view->groups = $groups;
        $this->view->categories = $categories;
    }

    public function addProductAction()
    {
        $this->view->messages = array();

        if(!empty($_POST)) {

            $productService = new Lanch_Product_Service();
            $messages = $productService->isValidProductData($_POST);

            if (empty($messages)) {
                $productService->insertProduct($_POST);
                $this->_redirect('/admin/products');
                exit(1);
            } else {
                $this->view->messages = $messages;
            }

        }

        $equipmentRepository = new Lanch_Equipment_Repository();
        $groupRepository = new Lanch_Group_Repository();
        $categoryRepository = new Lanch_Category_Repository();

        $equipment = $equipmentRepository->getEquipment();
        $groups = $groupRepository->getGroups();
        $categories = $categoryRepository->getCategories();

        $this->view->equipment = $equipment;
        $this->view->groups = $groups;
        $this->view->categories = $categories;
    }

    public function deleteProductAction()
    {
        $productId = $this->getRequest()->getParam('productId');

        $productService = new Lanch_Product_Service();
        $productService->deleteProduct($productId);

        $this->_redirect('/admin/products');

        exit(1);
    }

    //EQUIPMENT
    public function equipmentAction()
    {
        $equipmentRepository = new Lanch_Equipment_Repository();
        $equipment = $equipmentRepository->getEquipment();

        $this->view->equipment = $equipment;
    }

    public function addEquipmentAction()
    {
        if(!empty($_POST)) {

            $equipmentService = new Lanch_Equipment_Service();
            $messages = $equipmentService->isValidEquipmentData($_POST);

            if (empty($messages)) {
                $equipmentService->insertEquipment($_POST);
                $this->_redirect('/admin/equipment');
            } else {
                $this->view->messages = $messages;
            }
        }
    }

    public function editEquipmentAction()
    {
        $equipmentId = $this->getRequest()->getParam('equipmentId');

        $this->view->messages = array();

        if(!empty($_POST)) {

            $equipmentService = new Lanch_Equipment_Service();
            $messages = $equipmentService->isValidEquipmentData($_POST);

            if (empty($messages)) {
                $equipmentService->updateEquipment($_POST);
                $this->_redirect('/admin/equipment');
                exit(1);
            } else {
                $this->view->messages = $messages;
            }

        }

        $equipmentRepository = new Lanch_Equipment_Repository();
        $equipment = $equipmentRepository->getEquipmentById($equipmentId);

        $this->view->equipment = $equipment;
    }

    public function deleteEquipmentAction()
    {
        $equipmentId = $this->getRequest()->getParam('equipmentId');

        $equipmentService = new Lanch_Equipment_Service();
        $equipmentService->deleteEquipmentById($equipmentId);

        $this->_redirect('/admin/equipment');

        exit(1);
    }

    //COMBOS
    public function combosAction()
    {
        $comboRepository = new Lanch_Combo_Repository();
        $combos = $comboRepository->getCombos();

        $this->view->combos = $combos;
    }

    public function addComboAction()
    {
        $this->view->messages = array();

        if (!empty($_POST)) {

            $comboService = new Lanch_Combo_Service();
            $messages = $comboService->isValidComboData($_POST, $_FILES);

            if (empty($messages)) {
                $comboId = $comboService->insertCombo($_POST, $_FILES);
                $this->_redirect('/admin/edit-combo?comboId=' . $comboId);
            } else {
                $this->view->messages = $messages;
            }

        }

        $productRepository = new Lanch_Product_Repository();
        $products = $productRepository->getProducts();

        $this->view->products = $products;
    }

    public function editComboAction()
    {
        $comboId = $this->getRequest()->getParam('comboId');

        $this->view->messages = array();

        if (!empty($_POST)) {

            $comboService = new Lanch_Combo_Service();
            $messages = $comboService->isValidComboData($_POST, $_FILES);

            if (empty($messages)) {
                $comboService->updateCombo($_POST, $_FILES);
            } else {
                $this->view->messages = $messages;
            }

        }

        $productService = new Lanch_Product_Service();
        $comboRepository = new Lanch_Combo_Repository();
        $groupRepository = new Lanch_Group_Repository();
        $categoryRepository = new Lanch_Category_Repository();

        $products = $productService->getProductsHierarchicallyByCategoryAndGroup();
        $combo = $comboRepository->getComboById($comboId);
        $groups = $groupRepository->getGroups();
        $categories = $categoryRepository->getCategories();

        $this->view->combo = $combo;
        $this->view->products = $products;
        $this->view->categories = $categories;
        $this->view->groups = $groups;
        $this->view->sizes = array(
            'basico' => 'Básico',
            'medio' => 'Medio',
            'full' => 'Full'
        );
    }

    public function deleteComboAction()
    {
        $comboId = $this->getRequest()->getParam('comboId');

        $comboRepository = new Lanch_Combo_Repository();
        $comboRepository->deleteComboById($comboId);

        $this->_redirect('/admin/combos');

        exit(1);
    }

    //GROUPS
    public function groupsAction()
    {
        $groupRepository = new Lanch_Group_Repository();
        $groups = $groupRepository->getGroups();

        $this->view->groups = $groups;
    }

    public function editGroupAction()
    {
        $groupId = $this->getRequest()->getParam('groupId');

        $this->view->messages = array();

        if(!empty($_POST)) {

            $groupService = new Lanch_Group_Service();
            $messages = $groupService->isValidGroupData($_POST);

            if (empty($messages)) {
                $groupService->updateGroup($_POST);
                $this->_redirect('/admin/groups');
                exit(1);
            } else {
                $this->view->messages = $messages;
            }

        }

        $groupRepository = new Lanch_Group_Repository();
        $group = $groupRepository->getGroupById($groupId);

        $this->view->group = $group;
    }

    public function addGroupAction()
    {
        $this->view->messages = array();

        if (!empty($_POST)) {

            $groupService = new Lanch_Group_Service();
            $messages = $groupService->isValidGroupData($_POST);

            if (empty($messages)) {
                $groupService->insertGroup($_POST);
                $this->_redirect('/admin/groups');
                exit(1);
            } else {
                $this->view->messages = $messages;
            }

        }
    }

    public function deleteGroupAction()
    {
        $groupId = $this->getRequest()->getParam('groupId');

        $groupRepository = new Lanch_Group_Repository();
        $groupRepository->deleteGroupById($groupId);

        $this->_redirect('/admin/groups');

        exit(1);
    }

    //CATEGORIES
    public function categoriesAction()
    {
        $categoryRepository = new Lanch_Category_Repository();
        $categories = $categoryRepository->getCategories();

        $this->view->categories = $categories;
    }

    public function editCategoryAction()
    {
        $categoryId = $this->getRequest()->getParam('categoryId');

        $this->view->messages = array();

        if(!empty($_POST)) {

            $categoryService = new Lanch_Category_Service();
            $messages = $categoryService->isValidCategoryData($_POST);

            if (empty($messages)) {
                $categoryService->updateCategory($_POST);
                $this->_redirect('/admin/categories');
                exit(1);
            } else {
                $this->view->messages = $messages;
            }

        }

        $categoryRepository = new Lanch_Category_Repository();
        $category = $categoryRepository->getCategoryById($categoryId);

        $this->view->category = $category;
    }

    public function addCategoryAction()
    {
        $this->view->messages = array();

        if (!empty($_POST)) {

            $categoryService = new Lanch_Category_Service();
            $messages = $categoryService->isValidCategoryData($_POST);

            if (empty($messages)) {
                $categoryService->insertCategory($_POST);
                $this->_redirect('/admin/categories');
                exit(1);
            } else {
                $this->view->messages = $messages;
            }

        }
    }

    public function deleteCategoryAction()
    {
        $categoryId = $this->getRequest()->getParam('categoryId');

        $categoryRepository = new Lanch_Category_Repository();
        $categoryRepository->deleteCategoryById($categoryId);

        $this->_redirect('/admin/categories');

        exit(1);
    }
}