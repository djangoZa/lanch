<?php
class Lanch_Combo
{
    private $_id;
    private $_name;
    private $_basePrice;
    private $_imageUrl;
    private $_minimimGuests;
    private $_minimumWaiters;
    private $_discount;
    private $_selectedProductsIds;

    public function __construct(Array $data)
    {
        $this->_id = $data['id'];
        $this->_name = $data['name'];
        $this->_basePrice = $data['base_price'];
        $this->_minimimGuests = $data['minimum_guests'];
        $this->_discount = $data['discount'];
        $this->_setImageUrl($data['image_name']);
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

    private function _setImageUrl($imageName)
    {
        $this->_imageUrl = '/uploads/combo_images/' . $imageName;
    }
}