 <?php ob_start();
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

function add_to_contacts(){
	$user_being_viewed = trim(mysql_prep($_GET['user']));
	$owner = $_SESSION['username'];
	
	if(url_contains('/user/?user=') 
	&& !empty($_GET['user']) 
	&& $user_being_viewed !== $_SESSION['username'] 
	&& !is_a_contact() 
	&& is_logged_in()){
	if($_GET['add_to_contacts'] === 'yes' ){ 
		// insert query
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `contacts`(`id`,`owner`,`contact_name`) 
		VALUES('0', '{$_SESSION['username']}', '{$user_being_viewed}')") 
		or die("Failed to add contact " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			session_message("success", "Contact added successfully !");
			}
		}
	
	//  show add to contact link
	echo "<span><a href='{$_SESSION['current_url']}&add_to_contacts=yes'><button>+Add friend</button></a> &nbsp;&nbsp;</span>";
	
	}
	
}


function view_contacts(){
	
	if(url_contains('/user/?user=') 
	&& !empty($_GET['user'])  
	&& is_logged_in()){
		$owner = trim(mysql_prep($_GET['user']));
	} else {
		$owner = $_SESSION['username'];
		}
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `contact_name` FROM `contacts` WHERE `owner`='{$owner}' LIMIT 9") 
		or die("Error selecting contacts " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))) ;
		
		echo '<div class="margin-20">';
		
		while($result = mysqli_fetch_array($query)){
		$user = get_user_details($result['contact_name']);
		
		$pic_small = default_pic_fallback($pic=$user['picture_thumbnail'], $size='small');

			echo '<span class="margin-3"><a href="'.BASE_PATH .'user/?user='.$user['user_name'] .'">'.
			'<img src="'.$pic_small.'" title="'.$user['user_name'] .'" alt="user picture" id="profile-thumbnail" class="'.$pic_class.'"></a></span>';

		}
		echo '</div>';
		
	}

	
function is_a_contact(){
	
	$owner = $_SESSION['username'];
	$user_being_viewed = trim(mysql_prep($_GET['user']));
	//echo $user_being_viewed;
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `contact_name` FROM contacts WHERE owner='{$owner}' AND `contact_name`='{$user_being_viewed}' LIMIT 1") or die("failed to check contact status " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$num = mysqli_num_rows($query);
	//echo $num;
	$result = mysqli_fetch_array($query);
	//echo $result['contact_name'];
		if($num > 0){
		//	echo "Is a contact" .$result['contact_name'];
			return true;
		} else { return false; }
	
}
	
function remove_from_contacts(){
	
	$user_being_viewed = trim(mysql_prep($_GET['user']));
	$owner = $_SESSION['username'];
	
	if(!empty($_GET['user']) 
	&& $user_being_viewed !== $_SESSION['username'] 
	&& is_a_contact() 
	&& is_logged_in()
	&& $_GET['remove_contact'] === 'yes' ){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `contacts` WHERE `owner`='{$owner}' AND `contact_name`='{$user_being_viewed}' LIMIT 1") 
		or die("Failed to remove contact " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			status_message("success", "Contact removed successfully !");
			}
		
	}
		
	if(is_a_contact()){
	echo "<span><a href='{$_SESSION['current_url']}&remove_contact=yes'><button>Remove contact</button></a> &nbsp;&nbsp;</span>";
		
	}

	
}
	
 // end of jobs_and_services functions file
 // in root/jobs_and_services/includes/functions.php
?>
