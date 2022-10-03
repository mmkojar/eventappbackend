<?php 
header('Access-Control-Allow-Origin: *');
ini_set('display_errors',1);
error_reporting(E_ALL);
$this->load->helper('ipg');

date_default_timezone_set('Asia/Kolkata');

$dateTime= date('Y:m:d-H:i:s');

?>

<body onLoad="submitForm()">
	<form action="https://www4.ipg-online.com/connect/gateway/processing" name="payment" method="POST" style="display:none">
	<input type="hidden" name="timezone" value="IST" />
	<input type="hidden" name="authenticateTransaction" value="true" />
	<input size="50" type="hidden" name="txntype" value="<?php echo $payment_gateway["txntype"]; ?>"  />
	<input size="50" type="hidden" name="txndatetime" value="<?php echo $dateTime; ?>"  />
	<input size="50" type="hidden" name="hash" value="<?php echo createHash($payment_gateway["chargetotal"],"356",$payment_gateway["storename"],$payment_gateway["sharedsecret"]); ?>"  />
	<input size="50" type="hidden" name="currency" value="<?php echo $payment_gateway["currency"]; ?>"  />
	<input size="50" type="hidden" name="mode" value="<?php echo $payment_gateway["mode"]; ?>"  />
	<input size="50" type="hidden" name="storename" value="<?php echo $payment_gateway["storename"]; ?>"  />
	<input size="50" type="hidden" name="chargetotal" value="<?php echo $payment_gateway["chargetotal"]; ?>"  />
	<input size="50" type="hidden" name="sharedsecret" value="<?php echo $payment_gateway["sharedsecret"]; ?>"  />
	<input size="50" type="hidden" name="oid" value="<?php echo $payment_gateway["oid"]; ?>"  />
	<input type="hidden" name="responseSuccessURL" value="<?php echo $payment_gateway["responseSuccessURL"]; ?>"  />
	<input type="hidden" name="responseFailURL" value="<?php echo $payment_gateway["responseFailURL"]; ?>"  />
	<input type="hidden" name="hash_algorithm" value="SHA1"/>
	
	<button onClick="document.payment.submit();"> SUBMIT </button>
</form>

<script>
function submitForm(){
	document.payment.submit();
};
</script>
</body>