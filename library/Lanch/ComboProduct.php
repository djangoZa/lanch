<?php
class Lanch_ComboProduct
{
    private $_comboId;
    private $_productId;
    private $_size;

    public function __construct(Array $data)
    {
        $this->_comboId = $data['combo_id'];
        $this->_productId = $data['product_id'];
        $this->_size = $data['size'];
    }

    public function getProductId()
    {
        return $this->_productId;
    }

    public function getSize()
    {
        return $this->_size;
    }
}