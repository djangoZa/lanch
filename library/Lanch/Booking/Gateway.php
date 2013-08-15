<?php
class Lanch_Booking_Gateway
{
    private $_db;
    
    public function __construct()
    {
        $this->_db = Lanch_Db::factory('mysql');
    }
    
    public function getBookingByStatus($status)
    {
        $select = $this->_db->select();
        $select->from('bookings');
        $select->where('status = ?', $status);
        
        $rows = $this->_db->fetchAll($select);
        return $rows;
    }
    
    public function saveBooking(Lanch_Booking $booking)
    {
        //insert into booking table
        $this->_db->insert('bookings', array(
            'id' => null,
            'company_name' => $booking->getCompanyName(),
            'contact_person_name' => $booking->getContactPersonName(),
            'telephone' => $booking->getTelephoneNumber(),
            'email' => $booking->getEmailAddress(),
            'venue_address' => $booking->getVenueAddress(),
            'venue_zone' => $booking->getVenueZone(),
            'date_of_event' => $booking->getDateOfEvent(),
            'hour_of_event' => $booking->getHourOfEvent(),
            'extra_equipment_notes' => $booking->getExtraEquipmentNotes(),
            'venue_notes' => $booking->getVenueNotes(),
            'discount' => $booking->getDiscount(),
            'guests' => $booking->getGuests(),
            'waiters' => $booking->getWaiters(),
            'formal-dishes' => $booking->getFormalDishes(),
            'tax_percent' => $booking->getTaxPercent(),
            'sub_total' => $booking->getSubTotal(),
            'total' => $booking->getTotal()
        ));

        //insert into booking line items
        $bookingId = $this->_db->lastInsertId();
        $lineItems = $booking->getLineItems();
        foreach ($lineItems as $lineItem)
        {
            $this->_db->insert('booking_line_items', array(
                'booking_id' => $bookingId,
                'name' => $lineItem->getName(),
                'price' => $lineItem->getPrice(),
                'quantity' => $lineItem->getQuantity()
            ));
        }

        return $bookingId;
    }
    
    public function updateBooking(Lanch_Booking $booking)
    {
        $bookingId = $booking->getId();

        //insert into booking table
        $this->_db->update('bookings', 
            array(
                'company_name' => $booking->getCompanyName(),
                'contact_person_name' => $booking->getContactPersonName(),
                'telephone' => $booking->getTelephoneNumber(),
                'email' => $booking->getEmailAddress(),
                'venue_address' => $booking->getVenueAddress(),
                'venue_zone' => $booking->getVenueZone(),
                'date_of_event' => $booking->getDateOfEvent(),
                'hour_of_event' => $booking->getHourOfEvent(),
                'extra_equipment_notes' => $booking->getExtraEquipmentNotes(),
                'venue_notes' => $booking->getVenueNotes(),
                'discount' => $booking->getDiscount(),
                'guests' => $booking->getGuests(),
                'waiters' => $booking->getWaiters(),
                'formal-dishes' => $booking->getFormalDishes(),
                'tax_percent' => $booking->getTaxPercent(),
                'sub_total' => $booking->getSubTotal(),
                'total' => $booking->getTotal()
            ),
            'id = ' . $bookingId
        );
        
        //delete existing line items
        $this->_db->delete('booking_line_items', 'booking_id = ' . $bookingId);
        
        //insert booking line items
        $lineItems = $booking->getLineItems();
        foreach ($lineItems as $lineItem)
        {
            $this->_db->insert('booking_line_items', array(
                'booking_id' => $bookingId,
                'name' => $lineItem->getName(),
                'price' => $lineItem->getPrice(),
                'quantity' => $lineItem->getQuantity()
            ));
        }
        
        //delete adjustments
        $this->_db->delete('booking_adjustments', 'booking_id = ' . $bookingId);
        
        //insert adjustments
        $adjustments = $booking->getAdjustments();
        foreach ($adjustments as $adjustment)
        {
            $this->_db->insert('booking_adjustments', array(
                'id' => '',
                'booking_id' => $bookingId,
                'description' => $adjustment->getDescription(),
                'amount' => $adjustment->getAmount(),
                'date' => $adjustment->getDate()
            ));
        }
    }
    
    public function getLineItemsByBookingId($bookingId)
    {
        $select = $this->_db->select();
        $select->from('booking_line_items');
        $select->where('booking_id = ?', $bookingId);
        
        $rows = $this->_db->fetchAll($select);
        
        return $rows;
    }
    
    public function getAdjustmentsByBookingId($bookingId)
    {
        $select = $this->_db->select();
        $select->from('booking_adjustments');
        $select->where('booking_id = ?', $bookingId);
        $select->order('date ASC');
        
        $rows = $this->_db->fetchAll($select);
        
        return $rows;
    }
    
    public function saveLastInsertedBookingId($id)
    {
        setcookie('lastInsertedBookingId', $id, 0, '/');
        sleep(1);
    }
    
    public function getLastInsertedBookingId()
    {
        $bookingId = $_COOKIE['lastInsertedBookingId'];
        return $bookingId;
    }
    
    public function getBookingById($id)
    {
        $select = $this->_db->select();
        $select->from('bookings');
        $select->where('id = ?', $id);
        
        $row = $this->_db->fetchRow($select);
        
        return $row;
    }
}