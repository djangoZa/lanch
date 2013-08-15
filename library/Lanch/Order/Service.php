<?php
class Lanch_Order_Service
{
    private $_orderRepository;
    private $_comboRepository;
    private $_comboProductRepository;
    private $_productRepository;
    private $_equipmentRepository;
    private $_customerDetails;
    
    public function __construct()
    {
        $this->_orderRepository = new Lanch_Order_Repository();
        $this->_comboRepository = new Lanch_Combo_Repository();
        $this->_comboProductRepository = new Lanch_ComboProduct_Repository();
        $this->_productRepository = new Lanch_Product_Repository();
        $this->_equipmentRepository = new Lanch_Equipment_Repository();
    }
    
    public function getOrderSession()
    {
        return $this->_orderRepository->getOrderSession();
    }
    
    public function saveCustomerDetails(Array $details)
    {
        $this->_orderRepository->saveCustomerDetailsToOrderSession($details);
    }
    
    public function setSubTotal(Array $order)
    {
        //get the data needed to form the total
        $combo = $this->_getCombo($order);
        $products = $this->_getProducts($combo, $order);
        $equipment = $this->_getEquipment($products);
        
        //calculate the total
        $subTotal = 0;
         
        $subTotal = $this->_addCostOfComboBasePrice($subTotal, $combo);
        $subTotal = $this->_addCostOfProducts($subTotal, $products, $order);
        $subTotal = $this->_addCostOfEquipment($subTotal, $equipment, $order);
        $subTotal = $this->_addCostOfWaiters($subTotal, $order);
        $subTotal = $this->_addCostOfFormalDishes($subTotal, $order);
        
        $order['sub_total'] = $subTotal;
        
        return $order;
    }
    
    public function setTotal(Array $order)
    {
        //get the data needed to form the total
        $combo = $this->_getCombo($order);
        
        $total = $this->_addDiscount($order['sub_total'], $combo, $order);
        $total = $this->_addTax($total);
        
        $order['total'] = $total;

        return $order;
    }
    
    private function _addCostOfGuests($total, Array $order)
    {
        $guests = $order['guests'];
        $total = $total * $guests;
        return $total;
    }
    
    private function _addCostOfWaiters($total, Array $order)
    {
        $waiters = $order['waiters'];
        $pricePerWaiter = 50;
        $total += ($pricePerWaiter * $waiters);
        return $total;
    }
    
    private function _addCostOfFormalDishes($total, Array $order)
    {
        $formalDishes = $order['formalDishes'];
        $guests = $order['guests'];
        $pricePerFormalDish = 50;
    
        if ($formalDishes != 0) {
            $total +=  ($pricePerFormalDish * $guests);
        }
    
        return $total;
    }
    
    private function _addCostOfComboBasePrice($total, Lanch_Combo $combo)
    {
        $total += $combo->getBasePrice();
        return $total;
    }
    
    private function _addCostOfProducts($total, Array $products, Array $order)
    {
        $guests = $order['guests'];
        
        foreach ($products as $product) {
            $total += $product->getPrice() * $guests;
        }
    
        return $total;
    }
    
    private function _addCostOfEquipment($total, $equipment, $order)
    {
        $equipmentBlackListedIds = $order['equipmentBlackList'];
        foreach ($equipment as $aEquipment) {
            if (!in_array($aEquipment->getId(), $equipmentBlackListedIds)) {
                $total += $aEquipment->getPrice();
            }
        }

        return $total;
    }
    
    private function _addDiscount($total, Lanch_Combo $combo, $order)
    {
        $size = $order['size'];

        $total -= ($total * ($combo->getDiscount($size) / 100));

        return $total;
    }
    
    private function _addTax($total)
    {
        $total += ($total * (25 / 100));

        return $total;
        
    }
    
    private function _getProducts(Lanch_Combo $combo, Array $order)
    {
        $products = array();
    
        $size = $order['size'];
    
        $productIds = $combo->getSelectedProductIdsBySizeId($size);
    
        foreach ($productIds as $productId) {
            $products[] = $this->_productRepository->getProductById($productId);
        }
    
        return $products;
    }
    
    private function _getCombo(Array $order)
    {
        $comboId = $order['comboId'];
        $combo = $this->_comboRepository->getComboById($comboId);
        return $combo;
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
}