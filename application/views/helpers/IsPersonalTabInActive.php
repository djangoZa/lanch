<?php
class Zend_View_Helper_IsPersonalTabInActive {

    public function isPersonalTabInActive($view, $combo)
    {
        $out = false;
        
        $productIds = $combo->getSelectedProductIdsBySizeId('personal');
        if (empty($productIds)) {
            $out = true;
        }

        return $out;
    }
}