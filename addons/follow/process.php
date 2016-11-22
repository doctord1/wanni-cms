<?php 
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
start_addons_page();

########################################################################




# BACK LINK
echo ' 
<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
		' .'<a href="'.$back_url.'"> Go back </a> </li>
	</ul>
</section>';


# Log Follow intent


if(empty($_POST['active'])) {
$active = 0;}
else{
$active = 1;}

$action = htmlentities($_POST['action']);
$updated = htmlentities($_POST['updated']);
$submitted = trim(mysql_prep($_POST['submitted']));
$deleter = $_GET['action'];
$sent_delete = $_GET['deleted'];

$destination = trim(mysql_prep($_POST['destination']));
$author = $_SESSION['username'];
$editor = $_SESSION['username'];
if(isset($_POST['back_url'])){
	$back_url = $_POST['back_url'];
	} else {$back_url = $_SERVER['HTTP_REFERER'];}

if (isset($submitted) && $action ==='insert'){
	
	if ($fundraiser_name === ''){ $_SESSION['status_message'] = "<div class='error'>Fundraiser name is required!</div>" ; 
		eval ("<script type='text/javascript'> window.location.href = ".$_POST['back_url'] ."</script>");}
	
	else 
	{
		$insert_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `fundraiser`(`id`, `fundraiser_name`, `reason`, `perks`, `target_amount`, `amount_raised`, `author`, `editor`, `status`, `created`, `last_updated`, `start_date`, `end_date`) 
		VALUES ('{$id}', '{$fundraiser_name}', '{$reason}', '{$perks}', '{$target_amount}', '', '{$author}', '', '{$status}', '{$created}', '{$last_updated}',  '{$start_date}', '')")
		 or die ("Database insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	 if($insert_query) {
		 activity_record(
					$actor=$author,
					$action=' created the fundraiser ',
					$subject_name = $fundraiser_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= ADDONS_PATH.'fundraiser/?action=show&fundraiser_name='.$fundraiser_name,
					$parent='fundraiser'
					);
		echo status_message('success', 'Fundraiser saved successfully!');
		($location = $back_url);
		
		}
		
	$insert_menu = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `menus`(`id`, `menu_item_name`, `menu_type`, `position`, `visible`, `destination`) 
	VALUES ('','{$fundraiser_name}', '{$menu_type}', '{$position}', '{$visible}', '{$destination}')")	
	or die("FAiLEd to insert menu item!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
}


# UPDATE fundraiser

# Edit form processing

if(isset($updated) && $action ==='update'){

		if ($fundraiser_name === ''){ echo "<div class='error'>Fundraiser name is required!</div>" ;}
		else 
		{	
		$update_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE fundraiser SET reason='{$reason}', perks='{$perks}', `editor`='{$editor}', last_updated='{$last_updated}' 
		WHERE fundraiser_name='{$fundraiser_name}' LIMIT 1") or die("Database UPDATE failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		 
	 
	 if($update_query) {
		 
		  activity_record(
					$actor=$author,
					$action=' updated the fundraiser ',
					$subject_name = $fundraiser_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= ADDONS_PATH.'fundraiser/?action=show&fundraiser_name='.$fundraiser_name,
					$parent='fundraiser'
					);
					
		echo status_message('success', 'fundraiser saved successfully!');
		echo "<div align='center'><a href='".BASE_PATH."?section_name=fundraiser'> GO to fundraiser section</a><br>";
		
		go_back($location = $back_url);
		"</div>";
		
		
	 }	
	
	 }

}
#echo "deleter = " .$deleter ."<br> And sent_delete = " .$sent_delete ;   //testing

  // Now we check if delete is requested  
if(isset($deleter) && $sent_delete ==='jfldjff7'){
	
	$del_fundraiser_name= $_GET['fundraiser_name'];
	#echo " id is " . $del_fundraiser_name . ' and delete button was pressed'; // testing
	$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from fundraiser WHERE fundraiser_name='{$del_fundraiser_name}'") 
	or die('<div class="alert">Could not delete the specified fundraiser!</div>') . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	}
	
	if($delete_query) {
		echo "<div class='message-notification'> fundraiser '" .$del_fundraiser_name ."' deleted successfully!!</div>";
		
	}
	$delete_menu_query=mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menus` WHERE `menu_item_name`='{$del_fundraiser_name}'") 
	or die("Menu item deletion failed1" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));


	

if(! empty($_POST['submit']) && $_POST['submit']==='Donate'){

	# Fundraiser processing
$fundraiser_name = trim(mysql_prep(strtolower($_POST['fundraiser_name'])));
$amount=trim(mysql_prep($_POST['amount']));
$current_amount = trim(mysql_prep($_POST['current_amount']));
$update_amount = $current_amount + $amount;
$giver = $_POST['giver'];
$reciever = $_POST['reciever'];
$reason = trim(mysql_prep($_POST['reason']));
$time = getdate();
$today = $time['weekday'] .' '. $time['mday'].' '. $time['month'].' '. $time['year'].' '. $time['hours'].':'. $time['minutes'].' :: '. $time['seconds'];


$update_fundraiser_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `fundraiser` SET `amount_raised`='{$update_amount}' 
WHERE `fundraiser_name`='{$fundraiser_name}'") or die("Fundraiser query error!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$insert_fundraiser_donor_query =mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `fundraiser_donors`
(`id`, `donor`, `amount`, `fundraiser_name`, `recipient`, `date`) 
VALUES ('', '{$giver}', '{$amount}', '{$fundraiser_name}', '{$reciever}', '{$today}')") 
 or die("Fundraiser_donor insert error!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
 
 # Funds manager processing
	transfer_funds($amount,$reciever,$giver,$reason,'add');
	}
?>


