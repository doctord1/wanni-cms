<?php 
#=======================================================================
#					- Template starts
	
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/
 

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit

start_addons_page(); #from root/inludes/functions.php
$is_admin = is_admin();
#					- Template Ends -
#=======================================================================

#print_r($_POST);

echo "<section class='container'>";
	show_session_message();

	# buyable_items variables
	$id = trim(mysql_prep($_GET['id']));
	$money_service_id = trim(mysql_prep($_POST['money_service_id']));
	$money_service_code = trim(mysql_prep($_POST['money_service_code']));
	$money_service_type = trim(mysql_prep($_POST['money_service_type']));
	$description = trim(mysql_prep($_POST['description']));
	$price = trim(mysql_prep($_POST['price']));
	$stock = trim(mysql_prep($_POST['stock']));
	$seller = trim(mysql_prep($_POST['seller']));
	$currency = trim(mysql_prep($_POST['currency']));
	$author = $_SESSION['username'];
	$submit = $_POST['submit'];
	$destination = $_POST['destination'];
	$date = date('c');
	
	# money_service types variables
	$money_service_type_name = trim(mysql_prep($_POST['money_service_type_name']));
	
	# ADD NEW money_service
	if(isset($_POST['add_new_money_service'])){
		
		if($submit){	
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `money_service`(`money_service_id`, `money_service_code`, `money_service_type`, `price`, `stock`, `description`, `seller`, `currency`, `author`, `status`) 
		VALUES ('0', '{$money_service_code}', '{$money_service_type}', '{$price}', '{$stock}', '{$description}', '{$seller}', '{$currency}', '{$author}', 'approved')") 
		or die("money_service insert failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){ 
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `money_service_id` FROM money_service WHERE money_service_code='{$money_service_code}' LIMIT 1") or die('failed before recording activity '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$result = mysqli_fetch_array($query);
		  
			   activity_record(
					$parent_id = $result['id'],
					$actor=$author,
					$action=" created the money service",
					$subject_name = $money_service_code,
					$actor_path = BASE_PATH.'user/?user='.$seller,
					$subject_path= ADDONS_PATH .'money_service/?show=money_service&money_service_code=' .$money_service_code,
					$date=$date,
					$parent= $money_service_code . ' money_service'
					);
	
			status_message("success",'Your money_service has been added successfully!'); 
			echo status_message("alert","Awaiting approval");
			$_SESSION['total_pending_money_services'] +=1;
			echo '<div class=""><a href="'.ADDONS_PATH.'jobs/cv/?user='.$_SESSION['username'].'</div>';
			}
		
		
		}
	}
	show_money_services_list($approval='pending',$start_number='',$step_number='');
	# DELETE money_service
	if(!empty($_GET['action']) && $_GET['action']==='delete'){
		$is_admin = is_admin();
		if($is_admin){
			
			$get_id = trim(mysql_prep($_GET['id']));
			$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `money_service` WHERE `money_service_id`='{$_GET['id']}'") 
			or die("money_service deletion failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			if($delete_query){ session_message("message-notification",'money_service DELETED successfully!'); }
		}
	}
	
	# EDIT money_service
		if(isset($_POST['edit_money_service'])){
		if($_POST['save_edit'] ==='Submit'){ //not working
			
			if($is_admin){
							
				$update_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `money_service` SET `money_service_id`='{$money_service_id}', `money_service_code`='{$money_service_code}', `money_service_type`='{$money_service_type}', `price`='{$price}', `stock`='{$stock}', `description`='{$description}', `seller`='{$seller}', `currency`='{$currency}', `author`='{$author}' WHERE `money_service_id`='{$money_service_id}'")	
				or die("Update query failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				
				if($update_query){
					$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `money_service_id` FROM money_service WHERE money_service_code='{$money_service_code}' LIMIT 1") or die('failed before recording activity '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
					$result = mysqli_fetch_array($query);
		  
					activity_record(
					$actor = $author,
					$action =' updated the money_service ',
					$subject_name = $money_service_code,
					$actor_path = trim(mysql_prep(BASE_PATH.'user/?user='.$author)),
					$subject_path= ADDONS_PATH.'money_service/?show=money_services&money_service_code='.$subject_name,
					$date = $date,
					$parent='money_service');
					
					session_message("success", "Item EDITED successfully!");
					redirect_to(ADDONS_PATH.'money_service');
					}	
			} else {deny_access();}
					
		}
	}
	
	# ADD NEW money_service TYPE
	if(isset($_POST['add_new_money_service_type'])){
		
		if($submit){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `money_service_type`(`id`, `money_service_type_name`, `money_service_type_description`) 
			VALUES ('', '{$money_service_type_name}', '{$description}')") or die("Failed to insert money_service type" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			if($query){status_message("message-notification", 'money_service TYPE added successfully!');}
			}
		
		}
	# EDIT money_service TYPE
	
	if(isset($_POST['edit_money_service_type'])){
		if($submit){
			if($is_admin){
							
				$update_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `money_service_type` SET `id`='{$id}', `money_service_type_name`='{$money_service_type_name}', `description`='{$description}'")	
				or die("Update query failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				
				if($update_query){ session_message("success", "Item TYPE EDITED successfully!");}	
				redirect_to(ADDONS_PATH.'money_service');
			} else {deny_access();}
		}
		
	}
		
	# APPROVE PENDING money_serviceS
	if($_GET['action']==='approve_money_service'){
		
		if($is_admin){
	
			$approve_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `money_service` SET `status`='approved' WHERE `money_service_id`='{$id}'") 
			or die("Approval failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			if($approve_query){ session_message("message-notification", "money_service Approved!");
				activity_record(
					$actor=$author,
					$action=' created the money service',
					$subject_name = $money_service_code,
					$actor_path = BASE_PATH.'user/?user='.$seller,
					$subject_path= ADDONS_PATH.'money_service/?show=money_services&money_service_code='.$money_service_code,
					$date = $date,
					$parent= $money_service_code .'money_services');
				redirect_to($_SESSION['prev_url']);}
			}
	}
	
	 show_money_service_admin_links();
	 
	 if(isset($_POST['delete_item'])){
		 if(is_author() || is_admin()){
			 
			 $query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `money_service` WHERE `money_service_id`='{$money_service_id}'") 
			 or die("Failed to delete money_service item " .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			 }
			 
			 if($query){
				 session_message("success", "money_service item deleted!");
		
				 }
			redirect_to(ADDONS_PATH.'money_service/catalog');
		 }


echo "</section>";

?>



