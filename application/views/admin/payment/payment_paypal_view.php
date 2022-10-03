<?php 
header('Access-Control-Allow-Origin: *');
ini_set('display_errors',1);
error_reporting(E_ALL);
?>

<body onLoad="submitForm()">
	<form action="<?php echo $payment_paypal["paypalURL"]; ?>" name="paypal" method="POST" style="display:none">
        <!-- Identify your business so that you can collect the payments. -->
        <input type="hidden" name="business" value="<?php echo $payment_paypal["paypalID"]; ?>">
        
        <!-- Specify a Buy Now button. -->
        <input type="hidden" name="cmd" value="_xclick">
        
        <!-- Specify details about the item that buyers will purchase. -->
        <input type="hidden" name="item_name" value="<?php echo $payment_paypal['item_name']; ?>">
        <input type="hidden" name="item_number" value="<?php echo $payment_paypal['item_number']; ?>">
        <input type="hidden" name="amount" value="<?php echo $payment_paypal['amount']; ?>">
        <input type="hidden" name="currency_code" value="<?php echo $payment_paypal['currency_code']; ?>">
        
        <!-- Specify URLs -->
        <input type='hidden' name='cancel_return' value='<?php echo $payment_paypal['cancel_return']; ?>'>
		<input type='hidden' name='return' value='<?php echo $payment_paypal['return']; ?>'>
    </form>

<script>
function submitForm(){
	document.paypal.submit();
};
</script>
</body>