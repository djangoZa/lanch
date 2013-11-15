<?php
class CompletaTusDatosController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('front');
    }
    
    public function indexAction()
    {
        $params = $this->getRequest()->getParams();
        $orderService = new Lanch_Order_Service();
        $order = $orderService->getOrderInSession();
        $customerDetails = $order->getCustomerDetails();
        $this->view->customerDetails = $customerDetails;
        $this->view->params = $params;
    }
    
    public function guardarDatosAction()
    {
        $params = $this->getRequest()->getParams();
        $orderService = new Lanch_Order_Service();
        $response = $orderService->validateDatos($params);
        
        if ($response['status'] == true) 
        {
            $orderService->saveCustomerDetails(array(
                    'empresa' => $params['empresa'],
                    'responsable' => $params['responsable'],
                    'tel' => $params['tel'],
                    'mail' => $params['mail']
            ));
            
            $this->_redirect('/confirmalo-y-listo');
        } else {
            $this->forward('index', 'CompletaTusDatos', null, $response);
        }
    }
}