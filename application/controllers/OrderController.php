<?php
class OrderController extends  Zend_Controller_Action
{
    public function getOrderSessionAjaxAction()
    {
        $orderRepository = new Lanch_Order_Repository();
        $order = $orderRepository->getOrderSession();
        echo json_encode($order);
        exit(1);
    }

    public function setOrderSessionAjaxAction()
    {
        $order = $this->getRequest()->getParam('order');
        
        $orderService = new Lanch_Order_Service();
        $orderRepository = new Lanch_Order_Repository();

        $order = $orderService->setSubTotal($order);
        $order = $orderService->setTotal($order);
        $orderRepository->setOrderSession($order);
        
        exit(1);
    }
    
    public function setOrderGuestsAjaxAction()
    {
        $guests = $this->getRequest()->getParam('guests');
        $orderRepository = new Lanch_Order_Repository();;
        $orderRepository->saveGuestsToOrderSession($guests);
        exit(1);
    }
}