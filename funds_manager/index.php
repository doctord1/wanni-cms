<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
require_once('details.php');
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .BASE_PATH . $my_addon_name .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'</a>';
?>

<?php start_addons_page();  
#						- Template ends -
#=======================================================================

?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<!-- HEADER REGION START -->


<section class="container">
 <h1>FUNDS MANAGER</h1><hr><br>


<?php  
# SHOW ADD FUNDS FORM

if (isset($_SESSION['username'])){
	echo "<div class='container'>";
	#echo "<a href='".BASE_PATH."user/?user=".$_SESSION['username']."'><div align='center'> Go to Profile Page</div></a>";
	echo "<a href='".BASE_PATH."funds_manager?action=fund_account'>"."<button align='center'><br><img src='".BASE_PATH."uploads/files/default_images/coins-64.png'><br> Fund your account</button></a>";
	echo "<a href='".BASE_PATH."funds_manager?action=load_top_up'>"."<button align='center'><br><img src='".BASE_PATH."uploads/files/default_images/top-up-64.jpeg'><br> Load a Top-Up pin</button></a>";
	echo "<a href='".BASE_PATH."funds_manager?action=transfer_other'>"."<button align='center'><br><img src='".BASE_PATH."uploads/files/default_images/transfer-funds-64.jpeg'><br> Transfer funds </button></a>";
	echo "<a href='".ADDONS_PATH."payment/?action=payment_requested&control=".$_SESSION['control']."'>"."<button align='center'><br><img src='".BASE_PATH."uploads/files/default_images/bank-64.png'><br>  Payout to Bank</button></a>";
	
	if($_SESSION['role']==='admin' || $_SESSION['role']==='manager'){
	
	#LINK TO GET TRANSACTION HISTORY 
	echo "<a href='" .BASE_PATH .'funds_manager/transaction_history.php' ."'><button align='center'> Get transaction History </button></a><br><hr>";
	}
	echo "<br><a href='" .BASE_PATH .'funds_manager?action=confirm_transaction' ."'><button align='center'> Confirm Vogepay transaction </button></a><br><hr>";
	
	#ShOW ADD FUNDS FORM

	if($_GET['action']==='transfer_other'){
	 add_funds_form_block();
	} else if($_GET['action'] === 'fund_account'){
	 fund_account_voguepay(); 
	} else if($_GET['action'] === 'payout'){
	 do_payout();
	} else if($_GET['action'] === 'load_top_up' || $_POST['action'] === 'load_top_up'){
		
		echo '<p></p><div class="whitesmoke">'; 
		 if(is_logged_in()){
		 echo '<a href="'.ADDONS_PATH . 'top_up_pin">Agents and pins</a></div><p></p>';
		 load_top_up_pin();
		 }
	}
	
	if($_GET['action'] == 'confirm_transaction'){
	echo '<form method="post" action="success.php">
	If your payment was interrupted by network, you may confirm its status here using the transaction pin supplied to you.
	<input type="text" name="transaction_id" class="form-control" placeholder="voguepay transaction id">
	<input type="submit" name="submit" value="Confirm transaction">
	</form>';
	}
	
	echo '</div>';
	
	
} else { log_in_to_continue();}
?>
</section>

</body>
</html>
