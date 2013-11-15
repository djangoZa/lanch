<?php
class Lanch_Combo_Price_Service
{
    public function __construct()
    {
        $this->_comboRepository = new Lanch_Combo_Repository();
        $this->_productRepository = new Lanch_Product_Repository();
    }
    
    public function getPricePerPersonBySizeAndComboId(Array $combos)
    {
        $out = array();
        
        foreach ($combos as $combo)
        {
            $comboId = $combo->getId();
            $sizes = array('basico', 'medio', 'full');
            $minimumGuests = $combo->getMinimimGuests();
            
            foreach ($sizes as $size)
            {
                $out[$comboId][$size] = ceil($this->_getTotalPriceBySize($comboId, $size) / $minimumGuests);
            }
        }

        return $out;
    }
    
    private function _getTotalPriceBySize($comboId, $size)
    {
        $combo = $this->_getCombo($comboId);
        $products = $this->_getProducts($combo, $size);
        $equipment = $this->_getEquipment($products);
        
        $total = 0;
        
        $total = $this->_addCostOfComboBasePrice($total, $combo);
        $total = $this->_addCostOfProducts($total, $products, $combo);
        $total = $this->_addCostOfEquipment($total, $equipment);
        $total = $this->_addCostOfWaiters($total, $combo);
        $total = $this->_addDiscount($total, $combo, $size);

        return $total;
    }
    
    private function _addCostOfWaiters($total, $combo)
    {
        $waiters = $combo->getMinimumWaiters();
        $pricePerWaiter = $combo->getPricePerWaiter();
        $total += $pricePerWaiter * $waiters;
        return $total;
    }
    
    private function _addCostOfGuests($total, $combo)
    {
        $minimumGuests = $combo->getMinimimGuests();
        $total = $total * $minimumGuests;
        return $total;
    }
    
    private function _addCostOfComboBasePrice($total, Lanch_Combo $combo)
    {
        $total += $combo->getBasePrice();
        return $total;
    }
    
    private function _addCostOfProducts($total, Array $products, Lanch_Combo $combo)
    {
        $guests = $combo->getMinimimGuests();
        
        foreach ($products as $product) {
            $total += $product->getPrice() * $guests;
        }
    
        return $total;
    }
    
    private function _addDiscount($total, Lanch_Combo $combo, $size)
    {
        $total -= $total * ($combo->getDiscount($size) / 100);
        return $total;
    }
    
    private function _addCostOfEquipment($total, $equipment)
    {
        foreach ($equipment as $aEquipment) {
            $total += $aEquipment->getPrice();
        }
    
        return $total;
    }
    
    private function _getEquipment(Array $products)
    {
        $out = array();
    
        foreach($products as $product) {
    
            foreach($product->getEquipment() as $equipment) {
                $out[$equipment->getId()] = $equipment;
            }
    
        }
    
        return $out;
    }
    
    private function _getProducts(Lanch_Combo $combo, $size)
    {
        $products = array();
    
        $productIds = $combo->getSelectedProductIdsBySizeId($size);
    
        foreach ($productIds as $productId) {
            $products[] = $this->_productRepository->getProductById($productId);
        }
    
        return $products;
    }
    
    private function _getCombo($comboId)
    {
        $combo = $this->_comboRepository->getComboById($comboId);
        return $combo;
    }
}