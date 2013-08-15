<?php
class Zend_View_Helper_IsPersonalTabActive {

    public function isPersonalTabActive($view, $combo)
    {
        $out = false;
        
        if ($view->size == 'personal') {
            if ($view->combo->getId() == $combo->getId()) {
                $out = true;
            }
        }

        return $out;
    }
}