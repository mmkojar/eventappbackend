<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');  
  
class PayPal {
    public function PayPal() {
        require_once('PayPal/DPayPal.php');
    }
}