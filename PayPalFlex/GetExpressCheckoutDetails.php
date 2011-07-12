<?php
/********************************************************
GetExpressCheckoutDetails.php

This functionality is called after the buyer returns from
PayPal and has authorized the payment.

Displays the payer details returned by the
GetExpressCheckoutDetails response and calls
DoExpressCheckoutPayment.php to complete the payment
authorization.

Called by ReviewOrder.php.

Calls DoExpressCheckoutPayment.php and APIError.php.

********************************************************/


require_once 'ppNVP/CallerService.php';
session_start();

$token = urlencode ( $_REQUEST ['token'] );

/* Build a second API request to PayPal, using the token as the
		ID to get the details on the payment authorization
		*/
$nvpstr = "&TOKEN=" . $token;

/* Make the API call and store the results in an array.  If the
		call was a success, show the authorization details, and provide
		an action to complete the payment.  If failed, show the error
		*/
$resArray = hash_call ( "GetExpressCheckoutDetails", $nvpstr );
$_SESSION ['reshash'] = $resArray;
$ack = strtoupper ( $resArray ["ACK"] );
if ($ack != "SUCCESS") {
	//Redirecting to APIError.php to display errors. 
	$location = "APIError.php";
	header ( "Location: $location" );
	exit();
} 

/* Collect the necessary information to complete the
   authorization for the PayPal payment
   */

$_SESSION['token']=$_REQUEST['token'];
$_SESSION['payer_id'] = $_REQUEST['PayerID'];

$_SESSION['paymentAmount']=$_REQUEST['paymentAmount'];
$_SESSION['currCodeType']=$_REQUEST['currencyCodeType'];
$_SESSION['paymentType']=$_REQUEST['paymentType'];

$resArray=$_SESSION['reshash'];

/* Display the  API response back to the browser .
   If the response from PayPal was a success, display the response parameters
   */

?>



<html>
<head>
    <title>PayPal PHP SDK - ExpressCheckout API</title>
    <link href="sdk.css" rel="stylesheet" type="text/css" />

</head>
<body>
   
	<form>
	 <center>
           <table width =400>
            <tr>
                <td><b>Order Total:</b></td>
                <td>
                  <?=$_REQUEST['currencyCodeType'] ?> <?=$_REQUEST['paymentAmount']?></td>
            </tr>
			<tr>
			    <td ><b>Shipping Address: </b></td>
			</tr>
            <tr>
                <td >
                    Street 1:</td>
                <td>
                   <?=$resArray['SHIPTOSTREET'] ?></td>

            </tr>
            <tr>
                <td >
                    Street 2:</td>
                <td><?=$resArray['SHIPTOSTREET2'] ?>
                </td>
            </tr>
            <tr>
                <td >
                    City:</td>

                <td>
                    <?=$resArray['SHIPTOCITY'] ?></td>
            </tr>
            <tr>
                <td >
                    State:</td>
                <td>
                    <?=$resArray['SHIPTOSTATE'] ?></td>
            </tr>
            <tr>
                <td >
                    Postal code:</td>

                <td>
                    <?=$resArray['SHIPTOZIP'] ?></td>
            </tr>
            <tr>
                <td >
                    Country:</td>
                <td>
                     <?=$resArray['SHIPTOCOUNTRYNAME'] ?></td>
            </tr>
            <tr>
                <td class="thinfield">
                     <a href="DoExpressCheckoutPayment.php">Pay</a>
                </td>
            </tr>
        </table>
    </center>
    </form>

</body>
</html>
