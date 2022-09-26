<?php
require_once('../config/config.php');

$callbackJSONData=file_get_contents('php://input');

$logFile = "stkPush.json";
$log = fopen($logFile, "a");
fwrite($log, $callbackJSONData);
fclose($log);
  
$callbackData=json_decode($callbackJSONData);

$resultCode=$callbackData->Body->stkCallback->ResultCode;
$resultDesc=$callbackData->Body->stkCallback->ResultDesc;
$merchantRequestID=$callbackData->Body->stkCallback->MerchantRequestID;
$checkoutRequestID=$callbackData->Body->stkCallback->CheckoutRequestID;
$mpesa=$callbackData->stkCallback->Body->CallbackMetadata->Item[0]->Name;
$amount=$callbackData->Body->stkCallback->CallbackMetadata->Item[0]->Value;
$mpesaReceiptNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;
$balance=$callbackData->stkCallback->Body->CallbackMetadata->Item[2]->Value;
$b2CUtilityAccountAvailableFunds=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
$transactionDate=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
$phoneNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[4]->Value;

$amount = strval($amount);
if($resultCode == 0){
$insert = $mysqli->query("INSERT INTO `payments`(`merchantRequestID`, `checkoutRequestID`,`resultCode`, `resultDesc`, `amount`, `mpesaReceiptNumber`, `transactionDate`, `phoneNumber`)
VALUES ('$merchantRequestID', '$checkoutRequestID','$resultCode', '$resultDesc', '$amount','$mpesaReceiptNumber','$transactionDate','$phoneNumber')");


$ret = "SELECT * FROM  sales  WHERE sale_customer_phoneno='$phoneNumber' AND sale_payment_status='notpaid' AND sale_payment_method='MPESA'";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($r = $res->fetch_object()) {

//UPDATE ROOM STATUS
$sale_id =$r->sale_id;
$sale_payment_status ="paid";
$query2 = "UPDATE sales SET sale_payment_status=? WHERE sale_id=?";
    $stmt2 = $mysqli->prepare($query2);
    $rc2 = $stmt2->bind_param(
        'ss',
        $sale_payment_status,
        $sale_id
         );
    
    $stmt2->execute();
};
}
		


