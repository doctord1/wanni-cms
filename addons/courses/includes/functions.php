<?php ob_start();
#=======================================================================
#                   FUNCTIONS TEMPLATE 
#=======================================================================
# THIS TEMPLATE CONTAINS CODE ALREADY WRITTEN TO HELP YOU QUICKLY 
# AND EASILY  START WRITING ADDONS FOR WANNI CMS.
# 
#				DO NOT EDIT OR TAMPER 
#		[UNLESS YOU ABOLUTELY KNOW WHAT YOU ARE DOING]	 
# ---------------------------------------------------------------------
#					 TEMPLATE STARTS
#----------------------------------------------------------------------

# 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
#echo $r;
require_once($r .'includes/functions.php'); #do not edit

//print_r($_POST);

#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function courses_menu(){
	echo '<div class="row">
	<div class="col-md-12">';
	echo '<a href="'.ADDONS_PATH.'courses/?action=view_courses"><button class="btn">Courses</a></button>';
	
	if(is_admin()){
	echo '<a href="'.ADDONS_PATH.'courses/?action=view_registrants"><button class="btn">Registrants</a></button>';
	echo '<a href="'.ADDONS_PATH.'sms/"><button class="btn">Send Sms Notifications</a></button>';
	echo '<a href="'.ADDONS_PATH.'courses/?action=create""><button class="btn">Add course</a></button>';
	
	
	}
	
	//echo '<a href="'.ADDONS_PATH.'courses/?action=view_registrants"><button class="btn">View registrants</a></button>';
	echo '<hr></div>
	</div>';
	
	
	}
	




function add_course(){
	if($_GET['action'] == 'create'){
	create_item('courses');
	}
}

function register_for_course(){
	
	if(($_GET['action']== 'view_course' || $_GET['action'] == 'register_for_course' || isset($_POST['register_course'])) 
	&& $_GET['action'] != 'view_courses' 
	&& !isset($_GET['no_course'])){
		$course = trim(mysql_prep($_GET['tid']));
		show_course_registration_form();
		}
	if($_GET['action'] == 'register_for_course' && !isset($_GET['course_name']) && $_GET['no_course'] == 'yes' ){	
	$select = get_select_list_from_table('courses','course_name','course_name');
	echo '<form method="post" action="'.$_SESSION['current_url'].'">';
	echo $select;
	echo '<input type="submit" name="select_course" value="Continue">';
	echo '</form>';
	}
	
	if(isset($_POST['select_course'])){
		show_course_registration_form();
	//create_item('course_registration',$select_lists = '','hidden',$hide_columns=array('status','course_name','amount_paid','balance'));	
	}
		
}
	
	
function is_registered_for_course(){
	if(!empty($_GET['course_name']) && !empty($_GET['tid'])){
	$course_id = trim(mysql_prep($_GET['tid']));
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `full_name` FROM `course_registration` WHERE id='{$course_id}'") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$val = mysqli_num_rows($query);
		if(empty($val)){
			return true;
			} else {
				return false;
				}
	}
}
	
	
function view_course(){
	
	if(!empty($_GET['course_name']) && $_GET['action'] !== 'register_for_course'){ 
		
		$course = fetch_item_from_table('courses');
		
		echo '<div class="sweet_title">'.ucfirst($course['course_name']).'</div>';
		
		if(!is_registered_for_course() && $_GET['action'] !=='register_for_course'){
		echo '<a href="'.ADDONS_PATH.'courses/?action=register_for_course&course_name='.$course['course_name'].'&tid='.$course['id'].'"><button class="tn btn-lg btn-primary">Register for this Course</button></a>';
		}		
		
		echo '<div class="page_content">'.parse_text_for_output($course['description']).'</div>';
		
		if(is_admin()){
			delete_item('courses');
			}
	}
	
}
	 

function list_all_courses(){
	if(($_GET['action'] == 'view_courses' && (!isset($_GET['course_name']))) || isset($_GET['page_name'])){
	unset($_POST['course_name']);// important do not remove
	unset($_SESSION['use_me_course_name']);// important do not remove
		
	$query = mysqli_query($GLOBALS["___mysqli_ston"], 'SELECT * FROM courses') or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	
	while($result = mysqli_fetch_array($query)){
		echo '<div class="transparent-white margin-10 padding-10"><a href="'.ADDONS_PATH.'courses/?action=view_courses&course_name='.$result['course_name'].'&tid='.$result['id'].'"><h2>'.ucfirst($result['course_name']).'</h2></a></div></li>';
		}
		
	}
	
}
	

function get_registered_course_members(){
	
	if($_GET['action'] == 'view_registrants'){
		
		echo "<h1>Search or filter results (all fields are optional)</h1>
		<form method='post' action='".$_SESSION['current_url']."'>";
		echo '<input type="number" name="phone" placeholder="Phone number">';
		echo '<select name="status">
		<option>awaiting-payment</option>
		<option>started with part payment</option>
		<option>started</option>
		<option>finished</option>
		</select>';
		echo '<select name="owes_balance">
		<option>Yes</option>
		<option>No</option>
		</select>';
		echo "<input type='submit' name='do_filter' value='Filter Registrants'>";
		echo "<input type='submit' name='do_filter' value='Reset'>
		</form>";
		
		if(is_admin() && !isset($_GET['update_payment']) && $_GET['action'] == 'view_registrants'){
		$pager = pagerize();
		$limit = $_SESSION['pager_limit'];
		if($_POST['do_filter'] == 'Filter Registrants'){
			$phone = trim(mysql_prep($_POST['phone']));
			$owes_balance = trim(mysql_prep($_POST['owes_balance']));
			$status = trim(mysql_prep($_POST['status']));
			 
				
				if(!empty($phone)){
				$condition = ' WHERE phone="'.$phone.'"';
				} else {
					if($_POST['owes_balance'] == 'Yes'){ 
					$condition = ' WHERE balance > 0 AND status="'.$_POST['status'].'"';
					} else if($_POST['owes_balance'] == 'No'){
					$condition = ' WHERE balance="0" AND status="'.$_POST['status'].'"';
					}
				}
			} else if($_POST['do_filter'] == 'Reset'){
				$condition = '';
				}
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM course_registration {$condition} {$limit}");
		$num = mysqli_num_rows($query);
		
		echo "<h2>Registrant details</h2></h2><table class='table'><thead><th>Full name</th><th>Phone number</th><th>Course</th><th>Paid</th><th>Balance</th><th>Status</th></thead>
		<tbody>";
		while($result = mysqli_fetch_array($query)){
			
			echo "<tr><td> ". $result['full_name'] 
			."</td><td> ". $result['phone'] 
			."</td><td> ".$result['course_name'] ."</td> <td>".$result['amount_paid'] 
			."</td> <td>".$result['balance'] ."</td> <td>".$result['status'] ."<br><br>";
			if($result['status']!= 'finished'){
				echo "<a href='".ADDONS_PATH."courses?update_payment={$result['id']}&registrant={$result['full_name']}&course_name={$result['course_name']}&paid={$result['amount_paid']}&status={$result['status']}'><button>Update payment and status</button></a></td></tr>";
			}
		}
		if(empty($num)){
			status_message('error','There are no registrants that match those criteria! ');
			
			}
		echo "</tbody></table>";
		echo $pager;
		}
	}
}

function show_course_registration_form(){
	
	//echo "<br> ".$_SESSION['use_me_course_name'];
	if(isset($_POST['select_course']) ){
		$_SESSION['use_me_course_name'] = trim(mysql_prep($_POST['course_name']));
		}
		
		if(isset($_SESSION['use_me_course_name'])){
		$course = $_SESSION['use_me_course_name'];
		} else if(isset($_POST['course_name'])){
			$course = trim(mysql_prep($_POST['course_name'])); 
			} else if (isset($_GET['course_name'])){
				$course = trim(mysql_prep($_GET['course_name']));
				}
		$course_name =	$course;	
		$full_name = trim(mysql_prep($_POST['full_name']));
		$phone = mysql_prep($_POST['phone']);
		$date = date(dd/mm/YY);
		
	if(isset($_POST['register_course']) || $_GET['save_reg'] == 'true'){

		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `course_registration`(`id`, `full_name`, `phone`, `course_name`, `amount_paid`, `balance`, `date_of_last_payment`, `status`) 
		VALUES ('0','{$full_name}','{$phone}','{$course_name}','0','0','0','awaiting-payment')") 
		or die('Error saving course registration' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			$success = sms_notify($reciever=$phone,$message='You have registered for the '.$course_name.' course. Your reg-id is '.$phone.'. Kindly proceed to our office @122 NTA-MGBUOBA RD for payment, or call 08166438855');
			if($success){
				echo $success;
			status_message('success','Registration saved successfully ');
			}
			}
		
		//check if user exists, if not create user
		}
	
	if($_GET['action'] =='register_for_course' || isset($_POST['select_course'])){
	
	echo "<h1> You are registering for training in our {$course}</h1>";
	echo '<form method="post" action="'.$_SERVER['current_url'].'?save_reg=true">
	<input type="text" name="full_name" placeholder="Full name (surname first)">
	<input type="text" name="phone" placeholder="Phone number">
	<input type="hidden" name="course_name" placeholder="Course" value="'.$course.'"> 
	<input type="submit" name="register_course" value="Register">
	
	</form> ';
	}
	
}

function update_payment_and_status(){
	
	if(isset($_POST['update_status'])){
	$id = trim(mysql_prep($_GET['update_payment']));
	$total = $_GET['paid'] + $_POST['amount'];
	$status = trim(mysql_prep($_POST['status']));
	$balance = mysql_prep($_POST['balance']);
	$date = date('d/m/Y');
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE course_registration SET amount_paid='{$total}', balance='{$balance}', date_of_last_payment='{$date}', status='{$status}' WHERE id='{$id}'") 
	or die('ERROR updating payment details '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($query){
		status_message('success','Payment and status saved!');
		}
	}
	
	if(isset($_GET['update_payment'])){
		$registrant = trim(mysql_prep($_GET['registrant']));
		$course_name = trim(mysql_prep($_GET['course_name']));
		
		echo "<h1>Update payment details for {$registrant} in {$course_name}</h1>";
		
		echo "<form method='post' action='".$_SERVER['current_url']."'>
		NGN <input type='text' name='amount' placeholder='Add Amount'>
		NGN <input type='text' name='balance' placeholder='balance'>
		<select name='status'>
		<option>started</option>
		<option>started with part payment</option>
		<option>finished</option>
		</select>
		<input type='submit' name='update_status' value='Update status'>
		</form>";
		
		}

	}


 // end of course_registration functions file
 // in root/course_registration/includes/functions.php
?>
