<?php
class OrderController extends  Zend_Controller_Action
{
    public function getOrderSessionAjaxAction()
    {
        $size = $this->getRequest()->getParam('size');
        $comboId = $this->getRequest()->getParam('comboId');

        $orderService = new Lanch_Order_Service();
        $order = $orderService->getOrder($size, $comboId);

        echo json_encode($order);
        exit(0);
    }
    
    public function updateOrderSessionWithPersonalProductsAjaxAction()
    {
        $size = $this->getRequest()->getParam('size');
        $comboId = $this->getRequest()->getParam('comboId');
        
        $orderService = new Lanch_Order_Service();
        $orderRepository = new Lanch_Order_Repository();
        
        $order = $orderRepository->getOrderSession($comboId, $size);
        $order = (array) $order;
        
        $order['size'] = 'personal';

        $order = $orderService->setTotal($order);
        $order = $orderService->setSubTotal($order);
        
        $orderRepository->setOrderSession($order);
        
        exit(0);
    }

    public function setOrderSessionAjaxAction()
    {
        $order = $this->getRequest()->getParam('order');
        
        $orderService = new Lanch_Order_Service();
        $orderRepository = new Lanch_Order_Repository();

        $order = $orderService->setTotal($order);
        $order = $orderService->setSubTotal($order);
        
        $orderRepository->setOrderSession($order);

        exit(0);
    }
    
    public function setOrderGuestsAjaxAction()
    {
        $guests = $this->getRequest()->getParam('guests');
        $orderRepository = new Lanch_Order_Repository();
        $orderRepository->saveGuestsToOrderSession($guests);
        exit(0);
    }
}