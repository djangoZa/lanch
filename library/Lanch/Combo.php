<?php
class Lanch_Combo
{
    private $_id;
    private $_name;
    private $_basePrice;
    private $_imageUrl;
    private $_minimimGuests;
    private $_minimumWaiters;
    private $_pricePerWaiter;
    private $_pricePerPersonForFormalDishes;
    private $_guestPerWaiter;
    private $_discount;
    private $_selectedProductsIds;
    private $_activeCategories;
    private $_tax = 21;

    public function __construct(Array $data)
    {
        
        $this->_id = $data['id'];
        $this->_name = $data['name'];
        $this->_basePrice = $data['base_price'];
        $this->_minimimGuests = $data['minimum_guests'];
        $this->_pricePerWaiter = $data['price_per_waiter'];
        $this->_pricePerPersonForFormalDishes = $data['formal_dishes_price_per_guest'];
        $this->_guestsPerWaiter = $data['guests_per_waiter'];
        $this->_discount = $data['discount'];
        $this->_setImageUrl($data['image_name']);
        $this->_activeCategories = Zend_Json::decode($data['active_category_ids']);
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getBasePrice()
    {
        return $this->_basePrice;
    }

    public function getImageUrl()
    {
        return $this->_imageUrl;
    }
    
    public function getTax()
    {
        return $this->_tax;
    }
    
    public function getDiscount($size)
    {
        $out = 0;

        if ($size != 'personal') {
            $out = $this->_discount;
        }

        return $out;
    }
    
    public function getMinimimGuests()
    {
        return $this->_minimimGuests;
    }
    
    public function getMinimumWaiters()
    {
        return $this->_minimumWaiters;
    }
    
    public function getPricePerWaiter()
    {
        return $this->_pricePerWaiter;
    }
    
   public function getPricePerPersonForFormalDishes()
   {
       return $this->_pricePerPersonForFormalDishes;
   }
    
    public function getGuestsPerWaiter()
    {
        return $this->_guestsPerWaiter;
    }

    public function getSelectedProductIdsBySizeId($sizeId)
    {
        $out = array();

        if (!empty($this->_selectedProductsIds[$sizeId])) {
            $out = $this->_selectedProductsIds[$sizeId];
        }

        return $out;
    }

    public function setSelectedProductsIds(Array $comboProducts)
    {
        foreach ($comboProducts as $comboProduct)
        {
            $this->_selectedProductsIds[$comboProduct->getSize()][] = $comboProduct->getProductId();
        }
    }
    
    public function setMinimumWaiters($value)
    {
        $this->_minimumWaiters = $value;
    }
    
    public function getActiveCategoriesBySizeId($sizeId)
    {
        $out = array();
        $buffer = array();
        
        //if size id is personal merge the categories together
        if ($sizeId == 'personal') {
            foreach ($this->_activeCategories as $sizeId => $categoryIds) {
                $buffer = array_merge($buffer, $categoryIds);
                $buffer = array_unique($buffer);
            }
            $out = $buffer;
        } else {
            $out = $this->_activeCategories[$sizeId];
        }
        
        return $out;
    }

    private function _setImageUrl($imageName)
    {
        $this->_imageUrl = '/uploads/combo_images/' . $imageName;
    }
}