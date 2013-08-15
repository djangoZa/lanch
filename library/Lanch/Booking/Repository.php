<?php
class Lanch_Booking_Repository
{
    private $_gateway;
    private $_cookie;

    public function __construct()
    {
        $this->_gateway = new Lanch_Booking_Gateway(); 
    }
    
    public function getAllUnseenBookings()
    {
        $out = array();
        $rows = $this->_gateway->getBookingByStatus('unseen');
        
        foreach($rows as $row)
        {
            $out[] = new Lanch_Booking($row);
        }
        
        return $out;
    }
    
    public function updateBooking(Lanch_Booking $booking)
    {
        return $this->_gateway->updateBooking($booking);
    }
    
    public function saveBooking(Lanch_Booking $booking)
    {
        return $this->_gateway->saveBooking($booking);
    }
    
    public function getBookingById($id)
    {
        $row = $this->_gateway->getBookingById($id);
        $booking = new Lanch_Booking($row);
        
        //add line items
        $rows = $this->_gateway->getLineItemsByBookingId($id);
        
        foreach($rows as $row)
        {
            $booking->addLineItem(array(
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => $row['quantity']
            ));
        }
        
        //add adjustments
        $rows = $this->_gateway->getAdjustmentsByBookingId($id);
        
        foreach($rows as $row)
        {
            $booking->addAdjustment(array(
                'description' => $row['description'],
                'amount' => $row['amount'],
                'date' => $row['date']
            ));
        }
        
        return $booking;
    }
    
    public function saveLastInsertedBookingId($id)
    {
        $this->_gateway->saveLastInsertedBookingId($id);
    }
    
    public function getLastInsertedBookingId()
    {
        $bookingId = $this->_gateway->getLastInsertedBookingId();
        return $bookingId;
    }
}