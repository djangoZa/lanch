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
        $this->comboId = $order['comboId'];
        $this->size = $order['size'];
        $this->guests = (!empty($order['guests'])) ? $order['guests'] : 1;
        $this->waiters = $order['waiters'];
        $this->formalDishes = $order['formalDishes'];
        $this->date = $order['date'];
        $this->time = $order['time'];
        $this->address = $order['address'];
        $this->notes = $order['notes'];
        $this->extraEquipment = $order['extraEquipment'];
        $this->subTotal = $order['sub_total'];
        $this->total = $order['total'];
        $this->discount = $order['discount'];
        $this->tax = $order['tax'];
        $this->pricePerPerson = $this->total / $this->guests;
        
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