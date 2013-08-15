<?php
require_once(dirname(__FILE__) . '/../../../../scripts/mpdf/mpdf.php');

class Lanch_Booking_PDF_Service
{
    private $_mpdf;
    
    public function __construct()
    {
        $this->_mpdf = new mPDF('utf-8', 'A4');
        $this->_mpdf->SetDisplayMode('fullpage');
    }
    
    public function viewBookingPDF()
    {
        $this->_mpdf->Output('Lanch.' . time() . '.pdf','D');
        exit;
    }
    
    public function makeBookingPDF($html)
    {
        $stylesheet = file_get_contents(realpath(dirname(__FILE__) . '/../../../../public/css/pdf.css'));
        $this->_mpdf->WriteHTML($stylesheet, 1);
        $this->_mpdf->WriteHTML($html);
    }
}