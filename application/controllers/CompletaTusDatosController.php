<?php
class CompletaTusDatosController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('front');
    }
    
    public function indexAction()
    {
        $orderService = new Lanch_Order_Service();
        $order = $orderService->getOrderSession();
        $customerDetails = $order->getCustomerDetails();
        $this->view->customerDetails = $customerDetails;
    }
    
    public function guardarDatosAction()
    {
        $params = $this->getRequest()->getParams();
        $orderService = new Lanch_Order_Service();
        $orderService->saveCustomerDetails(array(
            'empresa' => $params['empresa'],
            'responsable' => $params['responsable'],
            'tel' => $params['tel'],
            'mail' => $params['mail']
        ));
        
        $this->_redirect('/confirmalo-y-listo');
    }
}