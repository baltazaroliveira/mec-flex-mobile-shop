<?php
require_once 'ppNVP/CallerService.php';
require_once 'ppNVP/constants.php';

session_start ();

$token = $_REQUEST ['token'];
if (! isset ( $token )) {
	$serverName = $_SERVER ['SERVER_NAME'];
	$serverPort = $_SERVER ['SERVER_PORT'];
	$url = dirname ( 'http://' . $serverName . ':' . $serverPort . $_SERVER ['REQUEST_URI'] );
	 
		
	$paymentAmount = $_REQUEST ['paymentAmount'];
	$currencyCodeType = 'USD'; //$_REQUEST ['currencyCodeType'];
	$paymentType = 'Sale'; //$_REQUEST ['paymentType'];
	
	$VERSION = "71.0";
	$SOLUTIONTYPE = "Sole";
    $LANDINGPAGE = "";
	
	
	$n = $_REQUEST ['n']-1;
	$items = '';
	
	for ($i = 0; $i <= $n; $i++) 
	{ 
		$namekey = "L_NAME$i"; 
      	$namevalue = $_REQUEST ["L_NAME$i"];

		$items .= $namekey . '=' . $namevalue;
		$items .= '&';
	
		
		$numberkey = "L_NUMBER$i"; 
      	$numbervalue = $_REQUEST ["L_NUMBER$i"];

		$items .= $numberkey . '=' . $numbervalue;
		$items .= '&';
		
		
		$qtykey = "L_QTY$i"; 
      	$qtyvalue = $_REQUEST ["L_QTY$i"];

		$items .= $qtykey . '=' . $qtyvalue;
		$items .= '&';
	
		
		$amtkey = "L_AMT$i"; 
      	$amtvalue = $_REQUEST ["L_AMT$i"];

		$items .= $amtkey . '=' . $amtvalue;
		
		if($i < $n) {
			$items .= '&';
		}
  	}


	/* The returnURL is the location where buyers return when a
			payment has been succesfully authorized.
			The cancelURL is the location buyers are sent to when they hit the
			cancel button during authorization of payment during the PayPal flow
			*/
	
	$returnURL = urlencode ( $url . '/GetExpressCheckoutDetails.php?currencyCodeType=' . $currencyCodeType . '&paymentType=' . $paymentType . '&paymentAmount=' . $paymentAmount );
	$cancelURL = urlencode ( "$url/cancel.php?paymentType=$paymentType" );
	
	/* Construct the parameter string that describes the PayPal payment
			the varialbes were set in the web form, and the resulting string
			is stored in $nvpstr
			*/
	
	$nvpstr = "&AMT=" . $paymentAmount . "&PAYMENTACTION=" . $paymentType . "&ReturnUrl=" . $returnURL . "&CANCELURL=" . $cancelURL . "&CURRENCYCODE=" . $currencyCodeType . "&VERSION=" . $VERSION . "&SOLUTIONTYPE=" . $SOLUTIONTYPE . "&LANDINGPAGE=" . $LANDINGPAGE ."&" . $items;
	
		
	
	/* Make the call to PayPal to set the Express Checkout token
			If the API call succeded, then redirect the buyer to PayPal
			to begin to authorize payment.  If an error occured, show the
			resulting errors
			*/
	$resArray = hash_call ( "SetExpressCheckout", $nvpstr );
	$_SESSION ['reshash'] = $resArray;
	
	$ack = strtoupper ( $resArray ["ACK"] );
	
	if ($ack == "SUCCESS") {
		// Redirect to paypal.com here
		$token = urldecode ( $resArray ["TOKEN"] );
		$payPalURL = PAYPAL_URL . $token;
		header ( "Location: " . $payPalURL );
	} else {
		//Redirecting to APIError.php to display errors. 
		$location = "APIError.php";
		header ( "Location: $location" );
	}

} else {
	/* At this point, the buyer has completed in authorizing payment
			at PayPal.  The script will now call PayPal with the details
			of the authorization, incuding any shipping information of the
			buyer.  Remember, the authorization is not a completed transaction
			at this state - the buyer still needs an additional step to finalize
			the transaction
			*/
	
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
	
	if ($ack == "SUCCESS") {
		require_once "GetExpressCheckoutDetails.php";
	} else {
		//Redirecting to APIError.php to display errors. 
		$location = "APIError.php";
		header ( "Location: $location" );
	}
}

?>