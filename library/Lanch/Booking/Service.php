<?php
class Lanch_Booking_Service
{
    private $_taxPercent = 21;
    
    private $_orderRepository;
    private $_comboRepository;
    private $_productRepository;
    private $_equipmentRepository;
    private $_bookingRepository;
    
    public function __construct()
    {
        $this->_orderRepository = new Lanch_Order_Repository();
        $this->_comboRepository = new Lanch_Combo_Repository();
        $this->_productRepository = new Lanch_Product_Repository();
        $this->_equipmentRepository = new Lanch_Equipment_Repository();
        $this->_bookingRepository = new Lanch_Booking_Repository();
    }
    
    public function getAllUnseenBookings()
    {
        $bookings = $this->_bookingRepository->getAllUnseenBookings();
        return $bookings;
    }
    
    public function updateBooking(Lanch_Booking $booking)
    {
        $booking = $this->_addSubTotalAndTotal($booking);
        $this->_bookingRepository->updateBooking($booking);
    }
    
    public function saveBookingByOrderSession()
    {
        $order = $this->_orderRepository->getOrderSession();
        $combo = $this->_comboRepository->getComboById($order->getComboId());

        $booking = new Lanch_Booking();
        $booking = $this->_addContactDetails($booking, $order);
        $booking = $this->_addVenueDetails($booking, $order);
        $booking = $this->_addDeliveryDetails($booking, $order);
        $booking = $this->_addNotes($booking, $order);
        $booking = $this->_addLineItems($booking, $order, $combo);
        $booking = $this->_addDiscount($booking, $order, $combo);
        $booking = $this->_addTax($booking, $order);
        $booking = $this->_addGuests($booking, $order);
        $booking = $this->_addWaiters($booking, $order);
        $booking = $this->_addFormalDishes($booking, $order);
        $booking = $this->_addSubTotalAndTotal($booking);

        $bookingId = $this->_bookingRepository->saveBooking($booking);

        return $bookingId;
    }
    
    public function saveBooking(Lanch_Booking $booking)
    {
        $this->_bookingRepository->saveBooking($booking);
    }
    
    public function saveLastInsertedBookingId($bookingId)
    {
        $this->_bookingRepository->saveLastInsertedBookingId($bookingId);
    }
    
    public function addAdjustment($booking, Array $options = array())
    {
        $booking->addAdjustment(array(
            'description' => $options['description'],
            'amount' => $options['amount'],
            'date' => date('Y-m-d H:i')
        ));
        
        return $booking;
    }
    
    public function getLastInsertedBookingId()
    {
        $bookingId = $this->_bookingRepository->getLastInsertedBookingId();
        return $bookingId;
    }
    
    public function getBookingById($id)
    {
        $booking = $this->_bookingRepository->getBookingById($id);
        return $booking;
    }
    
    private function _addGuests(Lanch_Booking $booking, Lanch_Order $order)
    {
        $booking->addGuests($order->getGuests());
        return $booking;
    }
    
    private function _addWaiters(Lanch_Booking $booking, Lanch_Order $order)
    {
        $booking->addWaiters($order->getWaiters());
        return $booking;
    }
    
    private function _addFormalDishes(Lanch_Booking $booking, Lanch_Order $order)
    {
        $booking->addFormalDishes($order->getFormalDishes());
        return $booking;
    }
    
    private function _addSubTotalAndTotal(Lanch_Booking $booking)
    {
        $total = 0;
        
        //sum up the line items
        $lineItems = $booking->getLineItems();
        foreach ($lineItems as $lineItem)
        {
            $total += $lineItem->getPrice() * $lineItem->getQuantity();
        }
        
        //minus the discount
        if($booking->getDiscount() > 0)
        {
            $total -= (($total * ($booking->getDiscount() / 100)));
        }
        
        //sum up the adjustments
        $adjustments = $booking->getAdjustments();
        foreach ($adjustments as $adjustment)
        {
            $total += $adjustment->getAmount();
        }

        //add the total to the booking
        $booking->addTotal($total);
        
        //deduct the tax
        $total -= (($total * ($booking->getTaxPercent() / 100)));

        //add the subtotal to the booking
        $booking->addSubTotal($total);
        
        return $booking;
    }
    
    private function _addContactDetails(Lanch_Booking $booking, Lanch_Order $order)
    {
        $customerDetails = $order->getCustomerDetails();
        
        $booking->addCompanyName($customerDetails['empresa']);
        $booking->addContactPersonName($customerDetails['responsable']);
        $booking->addTelephoneNumber($customerDetails['tel']);
        $booking->addEmailAddress($customerDetails['mail']);
        
        return $booking;
    }
    
    private function _addVenueDetails(Lanch_Booking $booking, Lanch_Order $order)
    {
        $booking->addVenueAddress($order->getAddress());
        $booking->addVenueZone('to be added');
        
        return $booking;
    }
    
    private function _addDeliveryDetails(Lanch_Booking $booking, Lanch_Order $order)
    {
        $booking->addDateOfEvent($order->getDate());
        $booking->addHourOfEvent($order->getTime());
        
        return $booking;
    }
    
    private function _addDiscount(Lanch_Booking $booking, Lanch_Order $order, Lanch_Combo $combo)
    {
        $size = $order->getSize();
        
        if ($size != 'personal') {
            $discount = $combo->getDiscount();
        }
        
        $booking->addDiscount($discount);
        
        return $booking;
    }
    
    private function _addLineItems(Lanch_Booking $booking, Lanch_Order $order, Lanch_Combo $combo)
    {
        //add combo base price
        $booking->addLineItem(array(
            'name' => 'Base Price',
            'price' => $combo->getBasePrice(),
            'quantity' => 1
        ));
        
        //line items for products
        $productIds = $combo->getSelectedProductIdsBySizeId($order->getSize());
        $products = $this->_productRepository->getProducts();
        
        foreach ($productIds as $productId)
        {
            $booking->addLineItem(array(
                'name' => $products[$productId]->getName(),
                'price' => $products[$productId]->getPrice(),
                'quantity' => $order->getGuests()
            ));    
        }
        
        //line items for equipment
        $equipmentBlackListIds = $order->getEquipmentBlackList();
        $equipments = $this->_equipmentRepository->getEquipmentByProductIds($productIds);
        
        foreach ($equipments as $equipment)
        {
            $addEquipment = true;
            
            if (!empty($equipmentBlackListIds))
            {
                if (in_array($equipment->getId(), $equipmentBlackListIds))
                {
                    $addEquipment = false;
                }
            }
            
            if ($addEquipment == true)
            {
                $booking->addLineItem(array(
                    'name' => $equipment->getName(),
                    'price' => $equipment->getPrice(),
                    'quantity' => 1
                ));
            }
        }
        
        //line item formal/normal dishes
        $isRequiredFormalDishes = $order->getFormalDishes();
        $pricePerFormalDish = $combo->getPricePerPersonForFormalDishes();
        $booking->addLineItem(array(
            'name' => 'Vajilla' . (($isRequiredFormalDishes) ? ' Formal' : null),
            'price' => ($isRequiredFormalDishes) ? $pricePerFormalDish : 0,
            'quantity' => $order->getGuests()
        ));
        
        //line item for waiters
        $pricePerWaiter = $combo->getPricePerWaiter();
        $booking->addLineItem(array(
            'name' => 'Waiters',
            'price' => $pricePerWaiter,
            'quantity' => $order->getWaiters()
        ));

        return $booking;
    }
    
    private function _addNotes(Lanch_Booking $booking, Lanch_Order $order)
    {
        $booking->addExtraEquipmentNotes($order->getExtraEquipment());
        $booking->addVenueNotes($order->getNotes());
        
        return $booking;
    }
    
    private function _addTax(Lanch_Booking $booking, Lanch_Order $order)
    {
        $booking->addTaxPercent($this->_taxPercent);
        
        return $booking;
    }
}