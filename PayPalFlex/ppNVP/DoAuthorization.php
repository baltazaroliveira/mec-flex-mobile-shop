<!--
DoAuthorization.html

This is the main page for DoAuthorization sample.
This page allow the user to enter the required
parameters for DoAuthorization API call and a Submit
button that calls DoAuthorizationReceipt.php.

Called by index.html.

Calls DoAuthorizationReceipt.php.

-->

<?php
	$order_id = $_REQUEST['order_id'];
	if(!isset($order_id)) {
		$order_id = '';
	}
	$amount = $_REQUEST['amount'];
	if(!isset($amount)) {
		$amount = '0.00';
	}
	$currency_cd = $_REQUEST['currency'];
	if(!isset($currency_cd)) {
		$currency_cd = 'USD';
	}
?>

<html>
<head>
    <title>PayPal SDK - DoAuthorization</title>
    <link href="sdk.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <center>
	<form action="DoAuthorizationReceipt.php" method="post">
    <table class="api">
        <tr>
            <td colspan="2" class="header">
                DoAuthorization
            </td>
        </tr>
        <tr>
            <td class="field">
                Order ID:</td>
            <td>
                <input type="text" name="order_id" value=<?php echo $order_id?>>
                (Required)</td>
        </tr>
				<tr>
					<td align=right>Amount:</td>
					<td align=left>
						<input type="text" name="amount" value=<?php echo $amount?>>
						<select name=currency>
<?php
	$currencies = array('USD', 'GBP', 'EUR', 'JPY', 'CAD', 'AUD');
	for($i = 0; $i < count($currencies); $i++) {
?>
							<option <?php echo (($currency_cd == $currencies[$i]) ? 'selected' : '')?>><?php echo $currencies[$i]?></option>
<?php
	}
?>
						</select>
					</td>
					<td><b>(Required)</b></td>
				</tr>
            <td class="field">
            </td>
            <td>
                <input type="Submit" value="Submit" />
            </td>
        </tr>
    </table>
	</form>
    </center>
    <a class="home" href="index.html">Home</a>
</body>
</html>
