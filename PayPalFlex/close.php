<html>
<head>
<title>PayPal PHP API Response</title>
<script type="text/javascript">
	function doCancel() {
		//alert(window.opener.window.document.getElementById('payPalFlex').doPayment);
		window.opener.window.document.getElementById('payPalFlex').doCancel();
		window.close();
	}
</script>
</head>
<body>
Payement Has been Canceled
<a href="javascript:doCancel()">Return to Flex</a>
</body>
</html>