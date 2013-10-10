<?php
class Lanch_Order
{
    public $comboId;
    public $size;
    public $guests;
    public $waiters;
    public $formalDishes;
    public $equipmentBlackList;
    public $date;
    public $time;
    public $address;
    public $notes;
    public $extraEquipment;
    public $total;
    public $subTotal;
    public $discount;
    public $customerDetails;

    public function __construct($order)
    {
        $this->comboId = (!empty($order['comboId'])) ? $order['comboId'] : null;
        $this->size = (!empty($order['size'])) ? $order['size'] : null;
        $this->guests = (!empty($order['guests'])) ? $order['guests'] : null;
        $this->waiters = (!empty($order['waiters'])) ? $order['waiters'] : null;
        $this->formalDishes = (!empty($order['formalDishes'])) ? $order['formalDishes'] : null;
        $this->date = (!empty($order['date'])) ? $order['date'] : null;
        $this->time = (!empty($order['time'])) ? $order['time'] : null;
        $this->address = (!empty($order['address'])) ? $order['address'] : null;
        $this->notes = (!empty($order['notes'])) ? $order['notes'] : null;
        $this->extraEquipment = (!empty($order['extraEquipment'])) ? $order['extraEquipment'] : null;
        $this->subTotal = (!empty($order['sub_total'])) ? $order['sub_total'] : null;
        $this->total = (!empty($order['total'])) ? $order['total'] : 0;
        $this->discount = (!empty($order['discount'])) ? $order['discount'] : null;
        $this->tax = (!empty($order['tax'])) ? $order['tax'] : null;
        $this->pricePerPerson = (!empty($this->guests)) ? round($this->total / $this->guests, 2) : 0;
        
        if (!empty($order['customerDetails']))
        {
            $this->customerDetails = $order['customerDetails'];
        }
        
        if (!empty($order['equipmentBlackList'])) 
        {
            $this->equipmentBlackList = $order['equipmentBlackList'];
        }
    }
    
    public function getComboId()
    {
        return $this->comboId;
    }
    
    public function getSize()
    {
        return $this->size;
    }
    
    public function getGuests()
    {
        return $this->guests;
    }
    
    public function getWaiters()
    {
        return $this->waiters;
    }
    
    public function getFormalDishes()
    {
        return $this->formalDishes;
    }
    
    public function getEquipmentBlackList()
    {
        return $this->equipmentBlackList;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    public function getTime()
    {
        return $this->time;
    }
    
    public function getAddress()
    {
        return $this->address;
    }
    
    public function getNotes()
    {
        return $this->notes;
    }
    
    public function getExtraEquipment()
    {
        return $this->extraEquipment;
    }
    
    public function getTotal()
    {
        return $this->total;
    }
    
    public function getSubTotal()
    {
        return $this->subTotal;
    }
    
    public function getDiscount($size)
    {
        $out = 0;
        
        if ($size != 'personal' && !empty($this->discount)) {
            $out = $this->discount;
        }
        
        return $out;
    }
    
    public function getTax()
    {
        return $this->tax;
    }
    
    public function getPricePerPerson()
    {
        return $this->pricePerPerson;
    }
    
    public function getCustomerDetails()
    {
        return $this->customerDetails;
    }
}