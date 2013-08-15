<?php
class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_helper->layout->setLayout('blank');
    }

    public function homeAction()
    {
        $this->_helper->layout->setLayout('front');
    }
}