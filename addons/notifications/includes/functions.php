<?php
#=======================================================================
#                   FUNCTIONS TEMPLATE 
#=======================================================================
# THIS TEMPLATE CONTAINS CODE ALREADY WRITTEN CODE TO HELP YOU QUICKLY 
# AND EASILY  START WRITING ADDONS FOR WANNI CMS.
# 
#				DO NOT EDIT OR TAMPER 
#		[UNLESS YOU ABOLUTELY KNOW WHAT YOU ARE DOING]	 
# ---------------------------------------------------------------------
#					 TEMPLATE STARTS
#----------------------------------------------------------------------

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
require_once($r .'/includes/resize_class.php'); 

//print_r($_SERVER);
#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function add_notification(){
	if(is_admin()){
	echo '<h3>Add notification</h3><form method="post" action="'.$_SESSION['current_url'].'">
	<input type="text" name="for_user" placeholder="For" value="all">
	<textarea name="notification_message"></textarea>
	<input type="submit" name="submit" value="Save notification">
		
	</form>';
	}
	}

function save_notification($addon='',$message='',$for_user=''){
	
	if($_POST['submit'] == 'Save notification'){
		$addon = 'system';
		$notification_message = trim(mysql_prep($_POST['notification_message']));
		$created = date('c');
		$user = trim(mysql_prep($_POST['for_user']));
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `notifications`(`id`, `notification_message`, `addon`, `status`, `created`, `for_user`)
			 VALUES ('0','{$notification_message}','{$addon}','active','{$created}','{$user}')") 
			 or die('Could not save notification '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			 
			 if($query){
				 status_message('success','Notification saved');
				 }
		}
	
	if(empty($addon)){
		$addon = 'system';
		}
	if(empty($for_user)){
		$user = 'all';
		} else {
			$user = trim(mysql_prep($for_user));
			}
	
		if(!empty($message)){
			$notification_message = trim(mysql_prep($message));
			$created = date('c');
			
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `notifications`(`id`, `notification_message`, `addon`, `status`, `created`, `for_user`)
			 VALUES ('0','{$notification_message}','{$addon}','active','{$created}','{$user}')") 
			 or die('Could not save notification '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			 
			 if($query){
				 status_message('success','Notification saved');
				 }
			
		
		}
		
		//Add notice form
	}
	
	
function show_notifications($addon,$range='week',$for_user=''){
	
	// site will only store a maximum of 100 notifications
	delete_notifications();
	show_session_message();
	
	//Dismiss notices start
	if($_POST['submit'] == 'dismiss' && $_POST['dismiss_notice'] == 'yes'){
		$id = mysql_prep($_POST['notification_id']);
		if(!empty($_POST['for_user'])){
			$dm_user = trim(mysql_prep($_POST['for_user']));
			$dismissing_user_condition = "AND for_user='{$dm_user}'";
			}
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM notifications WHERE id='{$id}' {$dismissing_user_condition} ") 
		or die('Error dismissing notification '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		// claim reward/freebie
		} else if($_POST['submit'] == 'Grab it!' && $_POST['dismiss_notice'] == 'yes'){
		$id = mysql_prep($_POST['notification_id']);
		if(!empty($_POST['for_user'])){
			$dm_user = trim(mysql_prep($_POST['for_user']));
			$dismissing_user_condition = "AND for_user='{$dm_user}'";
		}
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM notifications WHERE id='{$id}' {$dismissing_user_condition} ") 
		or die('Error dismissing notification '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			redirect_to(ADDONS_PATH.'rewards/?action=claim_freebie&recipient='.$_SESSION['username'].'&desc='.$_POST['description'].'&status=available&control='.$_SESSION['control']);
			}
		} //claim reward end
	
	//Dismiss notices stop
	
	
	 if ($addon !='all' && addon_is_active($addon)){
			$addon = trim(mysql_prep($addon));
			$cond1 = "WHERE addon='{$addon}'";
			} else if($addon == 'all'){
				$cond1 = '';
				}
			
	if(empty($for_user)){
		$user == 'all';
		$condition = '';
		} else if($for_user == 'user'){
			$this_user = $_SESSION['username'];
			if($cond1==''){
			$condition = "WHERE for_user='{$this_user}'";
			} else { $condition = "AND for_user='{$this_user}'";}
			}
		// Set limits based on page
		if(url_contains('?page_name=') || url_contains('user/?user=')){
			$limit = 'LIMIT 0, 1';
			} else if(url_contains('/addons/notifications')){
				$limit = 'LIMIT 0, 50';
				}
		
		// end limit setting	

		$this_month = date('m');
		$today = date('d');
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM notifications {$cond1} {$condition} ORDER BY id DESC {$limit}") 
		or die("Error fetching notifications ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		//$num = mysql_num_rows($query);
		
		
		echo '<div class="notification">'; // start notification div
		
		while($result = mysqli_fetch_array($query)){
			//check for notifying addon
			if($result['addon'] == 'reward'){
				$icon = '<span class="glyphicon glyphicon-ice-lolly-tasted"></span>';
				} else {
				$icon = '<span class="glyphicon glyphicon-exclamation-sign"></span>';
				}
			
			//Set range
			if($range == 'week'){	
				$notice_date_month = substr( $result['created'],5,2);
				$notice_date_day = substr( $result['created'],8,2);
				$limit = 'LIMIT 0,2';
			} else if($range == 'all'){	
				$limit = '';
				}
				if(($this_month > $notice_date_month) || ($this_month == $notice_date_month)){
					$diff = $today - $notice_date_day;
					}
				if($diff <= 7 || empty($diff)){
					// Range is known, now we check if it is dismissble by user
					if($result['for_user'] == $_SESSION['username']){
						$dismiss = '<form method="post" action="'.$_SESSION['current_url'].'">
						<input type="hidden" name="for_user" value="'.$result['for_user'].'">
						<input type="hidden" name="notification_id" value="'.$result['id'].'">
						<input type="hidden" name="dismiss_notice" value="yes">
						<input type="submit" name="submit" value="dismiss" class="btn btn-primary btn-xs">
						</form>';
						} else if($result['addon'] == 'reward'){
						$dismiss = '<form method="post" action="'.$_SESSION['current_url'].'" class="tiny-text">
						<input type="hidden" name="for_user" value="'.$result['for_user'].'">
						<input type="hidden" name="notification_id" value="'.$result['id'].'">
						<input type="hidden" name="description" value="'.$result['notification_message'].'">
						<input type="hidden" name="dismiss_notice" value="yes">
						<input type="submit" name="submit" value="Grab it!" class="btn btn-primary btn-xs">
						</form>';
						}else { $dismiss = ''; }
					
					$info = $icon .' : '.$result['notification_message'];
					if(is_admin()){
						
						$info .= '<span class="tiny-edit-text"><a href="'.$_SESSION['current_url'].'&del_notification='.$result['id'].'">delete</a></span>';
					}
						$info .='<p>'.$dismiss;
					status_message('alert',$info);
					}
			
		}
		
	
		if(!url_contains('addons/notifications') && !is_user_page()){ 
		echo '<a href="'.ADDONS_PATH.'notifications" class="tiny-text pull-right"> see more ..</a>';
		}
		echo '</div>'; // end notification div
	
}

function delete_notifications(){
	if(is_admin() && isset($_GET['del_notification'])){
		$id = mysql_prep($_GET['del_notification']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM notifications WHERE id='{$id}'") 
		or die('Error deleting notification '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			redirect_to($_SESSION['prev_url']);
			}
		}	
	}

function email_contact(){ //set or save as block
	
	if(ISSET($_POST['email_genai'])){
		$subject = '-';
	if(!is_logged_in() && !empty($_POST['spam_blocker'])){
		$spam_check = strtolower(trim(mysql_prep($_POST['spam_blocker'])));
		if($spam_check === 'garri'){
		$pass = true;	
			} else { $pass = false;}
	} else if(is_logged_in){
		$pass = true;
		}
		
		$to = trim(mysql_prep($_POST['to']));
		$email= trim(mysql_prep($_POST['email']));
		$headers = 'From :'. $email;
		$subject = $_POST['message'];
		$phone = $_POST['phone'];
		$message = $_POST['message'] .' Call me on : '.$phone;
		
		$sent = mail($to,$subject,$message,$headers);
		if($sent){status_message('success','Message sent!');}
		unset($_POST['email_genai']);
		}
		
	echo '<form method="post" action="'.$_SESSION['current_url'].'">
	<input type="text" name="email" placeholder="Email" class="form-control" >
	<input type="hidden" name="to" value="site@geniusaid.org">
	<input type="tel" name="phone" placeholder="Phone number" class="form-control" required>
	<textarea name="message" class="form-control" placeholder="Message details" required>
	</textarea>';
	if(!is_logged_in()){
		echo "<div class='gainsboro'>
		<strong>Phone, Garri, Plastic, Bottle</strong><br>
		Which of the above is a FOOD item?<br>
		<input type='text' size='30' name='spam_blocker' value='' placeholder='Type your answer here'><br>
		<em>(This is to help us know if you are a human visitor or a spam bot)</em>
		</div>";	
		}
	echo '<input type="submit" name="email_genai" value="Send">
	</form>';
	
	}

 // end of notifications functions file
 // in root/notifications/includes/functions.php
?>
