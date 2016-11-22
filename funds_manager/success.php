<?php 
#======================================================================
#					- Template starts
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
require_once('details.php');
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .BASE_PATH . $my_addon_name .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'</a>';

?>

<!-- START PAGE -->

<?php start_addons_page();#from root/inludes/functions.php

#					- Template Ends -
#======================================================================

//print_r($_POST);


echo "<section class='container'>";
//echo $_SERVER['HTTP_ORIGIN'];
/*--------------Begin Processing-----------------*/
if ($_SERVER['HTTP_ORIGIN']==='https://voguepay.com' || $_SERVER['HTTP_ORIGIN']==='http://geniusaid.org'){
	$merchant_id = '13302-13767';
	$_test_id = 'demo-557ea89077d72'; // a known successful transaction
	#$_POST['transaction_id']

	$demo_retrieve_url = 'https://voguepay.com/?v_transaction_id='.$_POST['transaction_id'].'&type=xml&demo=true';
	$real_retrieve_url = 'https://voguepay.com/?v_transaction_id='.$_POST['transaction_id'].'&type=json';

	##Check if transaction ID has been submitted
	
	if(isset($_POST['transaction_id'])){
		//print_r($_POST);
		$trans_id = mysql_prep($_POST['transaction_id']);
		//echo $trans_id;
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `voguepay_transactions` where transaction_id='{$trans_id}'") 
		or die('Unable to get transaction id '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$result = mysqli_fetch_array($query);
		$num = mysqli_num_rows($query);
		
	
		 if($result['user'] === $_SESSION['username'] && !empty($num)){
			status_message('error', 'You have already been credited for this transaction!'); die();
		} else if($result['user'] != $_SESSION['username'] && !empty($num)){
			status_message('alert', "{$result['user']} has already been credited for this transaction."); die();
			}
		
		else if(empty($num)){
		$transaction_id = $_POST['transaction_id'];
		$voguepay = get_voguepay_transaction_details($transaction_id);

		if($voguepay['total_credited_to_merchant'] === 0){die('Invalid total');}
		if($voguepay['status'] !== 'Approved') {
			//~ echo $voguepay['status'];
			die('Failed transaction');}
		if($voguepay['merchant_id'] !== $merchant_id) {die('Invalid merchant');}
		
		#TRANSFER FUNDS TO USER
		$amount = $voguepay['total'];
		$reciever = $voguepay['merchant_ref'];
		$giver = 'system';
		$reason = $voguepay['memo'] .' - transaction_id: '.$transaction_id ;
		$action = 'add';
		
		transfer_funds($action,$amount,$giver,$reciever,$reason);
		$trans_id = mysql_prep($transaction_id);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `voguepay_transactions`(`id`, `transaction_id`, `user`) 
		VALUES ('0','{$trans_id}','{$reciever}')") or die('failed to update voguepay_transaction status '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		echo "<a href='".BASE_PATH."user/?user=".$_SESSION['username']."'><div align='center'> Go to Profile Page</div></a>";
		echo "<a href='".ADDONS_PATH."funds_manager'>"."<div align='center'> Add more Funds</div></a>";
		echo "<a href='".BASE_PATH."'>"."<div align='center'> Go to Home Page</div></a><br><br>";
		
		
		/*You can do anything you want now with the transaction details or the merchant reference.
		You should query your database with the merchant reference and fetch the records you saved for this transaction.
		Then you should compare the $transaction['total'] with the total from your database.*/
		}

echo "</section>";
} else {
	echo '<form method="post" action="'.$_SESSION['current_url'].'">
	<input type="text" name="transaction_id" placeholder="voguepay transaction id">
	<input type="submit" name="submit" value="Fund account">
	</form>';
	}
}
?>
</body>
</html>
