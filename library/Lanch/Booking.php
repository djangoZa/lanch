<?php
class Lanch_Booking
{
    private $_id;
    private $_companyName;
    private $_contactPersonName;
    private $_telephoneNumber;
    private $_emailAddress;
    private $_venueAddress;
    private $_venueZone;
    private $_dateOfEvent;
    private $_hourOfEvent;
    private $_lineItems;
    private $_adjustments = array();
    private $_extraEquipmentNotes;
    private $_venueNotes;
    private $_discount;
    private $_taxPercent;
    private $_subTotal;
    
    public function __construct(Array $row = array())
    {
        $this->_id = (!empty($row['id'])) ? $row['id'] : null;
        $this->_companyName = (!empty($row['company_name'])) ? $row['company_name'] : null;
        $this->_contactPersonName = (!empty($row['contact_person_name'])) ? $row['contact_person_name'] : null;
        $this->_telephoneNumber = (!empty($row['telephone'])) ? $row['telephone'] : null;
        $this->_emailAddress = (!empty($row['email'])) ? $row['email'] : null;
        $this->_venueAddress = (!empty($row['venue_address'])) ? $row['venue_address'] : null;
        $this->_venueZone = (!empty($row['venue_zone'])) ? $row['venue_zone'] : null;
        $this->_dateOfEvent = (!empty($row['date_of_event'])) ? $row['date_of_event'] : null;
        $this->_hourOfEvent = (!empty($row['hour_of_event'])) ? $row['hour_of_event'] : null;
        $this->_extraEquipmentNotes = (!empty($row['extra_equipment_notes'])) ? $row['extra_equipment_notes'] : null;
        $this->_venueNotes = (!empty($row['venue_notes'])) ? $row['venue_notes'] : null;
        $this->_discount = (!empty($row['discount'])) ? $row['discount'] : null;
        $this->_guests = (!empty($row['guests'])) ? $row['guests'] : null;
        $this->_waiters = (!empty($row['waiters'])) ? $row['waiters'] : null;
        $this->_formalDishes = (!empty($row['formal-dishes'])) ? $row['formal-dishes'] : null;
        $this->_taxPercent = (!empty($row['tax_percent'])) ? $row['tax_percent'] : null;
        $this->_subTotal = (!empty($row['sub_total'])) ? $row['sub_total'] : null;
        $this->_total = (!empty($row['total'])) ? $row['total'] : null;
    }
    
    public function addCompanyName($name)
    {
        $this->_companyName = $name;
    }
    
    public function addContactPersonName($name)
    {
        $this->_contactPersonName = $name;
    }
    
    public function addTelephoneNumber($number)
    {
        $this->_telephoneNumber = $number;
    }
    
    public function addEmailAddress($address)
    {
        $this->_emailAddress = $address;
    }
    
    public function addVenueAddress($address)
    {
        $this->_venueAddress = $address;
    }
    
    public function addVenueZone($zone)
    {
        $this->_venueZone = $zone;
    }
    
    public function addDateOfEvent($date)
    {
        $this->_dateOfEvent = $date;
    }
    
    public function addHourOfEvent($hour)
    {
        $this->_hourOfEvent = $hour;
    }
    
    public function addLineItem(Array $options)
    {
        $this->_lineItems[] = new Lanch_Booking_Line_Item(array(
            'name' => $options['name'],
            'price' => $options['price'],
            'quantity' => $options['quantity']
        ));
    }
    
    public function addAdjustment(Array $options)
    {
        $this->_adjustments[] = new Lanch_Booking_Adjustment(array(
            'description' => $options['description'],
            'amount' => $options['amount'],
            'date' => $options['date']
        ));
    }
    
    public function addDiscount($discount)
    {
        $this->_discount = $discount;
    }
    
    public function addGuests($guests)
    {
        $this->_guests = $guests;
    }
    
    public function addWaiters($waiters)
    {
        $this->_waiters = $waiters;
    }
    
    public function addFormalDishes($formalDishes)
    {
        $this->_formalDishes = $formalDishes;
    }
    
    public function addExtraEquipmentNotes($notes)
    {
        $this->_extraEquipmentNotes = $notes;
    }
    
    public function addVenueNotes($notes)
    {
        $this->_venueNotes = $notes;
    }

    public function addTaxPercent($percent)
    {
        $this->_taxPercent = $percent;
    }
    
    public function addTotal($total)
    {
        $this->_total = $total;
    }
    
    public function addSubTotal($total)
    {
        $this->_subTotal = $total;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getCompanyName()
    {
        return $this->_companyName;
    }
    
    public function getContactPersonName()
    {
        return $this->_contactPersonName;
    }
    
    public function getTelephoneNumber()
    {
        return $this->_telephoneNumber;
    }
    
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }
    
    public function getVenueAddress()
    {
        return $this->_venueAddress;
    }
    
    public function getVenueZone()
    {
        return $this->_venueZone;
    }
    
    public function getDateOfEvent()
    {
        return $this->_dateOfEvent;
    }
    
    public function getHourOfEvent()
    {
        return $this->_hourOfEvent;
    }
    
    public function getLineItems()
    {
        return $this->_lineItems;
    }
    
    public function getAdjustments()
    {
        return $this->_adjustments;
    }
    
    public function getDiscount()
    {
        $out = 0;
        
        if(!empty($this->_discount)) {
            $out = $this->_discount;
        }
        
        return $out;
    }
    
    public function getGuests()
    {
        return $this->_guests;
    }
    
    public function getWaiters()
    {
        return $this->_waiters;
    }
    
    public function getFormalDishes()
    {
        return $this->_formalDishes;
    }
    
    public function getExtraEquipmentNotes()
    {
        return $this->_extraEquipmentNotes;
    }
   
    public function getVenueNotes()
    {
        return $this->_venueNotes;
    }
    
    public function getTaxPercent()
    {
        return $this->_taxPercent;
    }
    
    public function getTotal()
    {
        return $this->_total;
    }
    
    public function getSubTotal()
    {
        return $this->_subTotal;
    }
}