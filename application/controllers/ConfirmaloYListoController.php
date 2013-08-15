<?php
class ConfirmaloYListoController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('front');
    }
    
    public function indexAction()
    {
        $orderRepository = new Lanch_Order_Repository();
        $comboRepository = new Lanch_Combo_Repository();
        $productService = new Lanch_Product_Service();
        $equipmentRepository = new Lanch_Equipment_Repository();
        $groupRepository = new Lanch_Group_Repository();
        
        $order = $orderRepository->getOrderSession();
        $combo = $comboRepository->getComboById($order->getComboId()); 
        $size = $order->getSize();
        $products = $productService->getProductsHierarchicallyByCategoryAndGroup();
        $selectedProductIds = $combo->getSelectedProductIdsBySizeId($size);
        $equipment = $equipmentRepository->getEquipmentByProductIds($selectedProductIds);
        $groups = $groupRepository->getGroups();
        
        $this->view->order = $order;
        $this->view->products = $products;
        $this->view->customerDetails = $order->getCustomerDetails();
        $this->view->combo = $combo;
        $this->view->equipment = $equipment;
        $this->view->selectedProductIds = $selectedProductIds;
        $this->view->groups = $groups;
        $this->view->size = $size;
    }
    
    public function completarAction()
    {
        $bookingService = new Lanch_Booking_Service();
        $bookingId = $bookingService->saveBookingByOrderSession();
        $bookingService->saveLastInsertedBookingId($bookingId);
    }
    
    public function downloadBookingAction()
    {
        $this->indexAction();

        $this->view->setScriptPath(array(realpath(dirname(__FILE__) . '/../views/scripts/')));
        $html = $this->view->render('confirmalo-y-listo/pdf.phtml');
        
        $bookingPdfService = new Lanch_Booking_PDF_Service();
        $bookingPdfService->makeBookingPDF($html);
        $bookingPdfService->viewBookingPDF();
    }
}