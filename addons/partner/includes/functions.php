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
//print_r($_SESSION);

#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function add_partner(){
	if(is_admin()){
	if(isset($_POST['submit_partner'])){
		//print_r($_POST); die();
		$name=trim(mysql_prep($_POST['name_of_person']));
		$testimonial=trim(mysql_prep($_POST['position']));
		
		$uploaddir = dirname(dirname(dirname(dirname(__FILE__)))).'/addons/partner/images/';
		$uploadfile = $uploaddir . $name .'.jpg';
		
		$type = $_FILES['image_field']['type'];

		if ($_SESSION['role'] ==='manager' || $_SESSION['role'] ==='admin'){
		// echo 'i dey here o!';  
		$move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);

		if($move ==1){ 
			echo "<div class='success'>File is valid, and was successfully uploaded.\n</div>";
				
			} else { echo "<div class='alert'>Error : No file uploaded!\n</div>"; }
		
	}
	//echo $uploadfile; die();
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT into partner(id,name) VALUES ('0','{$name}')") 
		or die('Failed to save partner' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			session_message('success', 'partner saved successfully');
			redirect_to(ADDONS_PATH.'partner');
			}
		}
	
	echo '<h1> Add partner</h1>
	<form method="post" action="'.$_SESSION['cuurent_url'].'" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
	Send this file: <input type="file" size="500" name="image_field" value="">
	<input type="text" name="name_of_person" class="form-control" placeholder="Name of partner">
	<input type="submit" name="submit_partner" value="Save partner" class="btn btn-primary">
	</form>';
	}
}


function show_partner(){
	
	global $r;
	$dir= str_ireplace('/regions/','',$r.'/addons/partner/images/');
	
	$images = scandir($dir);

	//do delete
	if(!empty($_GET['delete']) && is_admin()){
		$delete = trim(mysql_prep($_GET['delete']));
		$id = mysql_prep($_GET['tid']);
		$rm = unlink($dir.$delete.'.jpg');
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM partner WHERE id='{$id}' and name='{$delete}'");
		//unlink($dir.$delete);
		
		if($query){
			session_message("success", "partner deleted successfully!");
			redirect_to(ADDONS_PATH.'partner');
			} else { session_message("error", "partner delete failed!"); }
			redirect_to(ADDONS_PATH.'partner');
		}
		
		
	
	// Fetch testimonials from db
	echo '<marquee>';
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM partner LIMIT 0, 30");
	while($result = mysqli_fetch_array($query)){
		
		
		if(!empty($images)){
			foreach ($images as $image){  
				if($image == $result['name'].'.jpg'){  
				echo '<span class="padding-10 inline-block"><img src="'.ADDONS_PATH.'partner/images/'.$result['name'].'.jpg" alt="" width="150" height="100" hspace="5" />';
					if(is_admin()){
					echo '<a href="'.ADDONS_PATH.'partner/?delete='.$result['name'].'&tid='.$result['id'].'">delete</a></span>';
					}
				}
			}
		
		}
		
	}echo'</marquee>';
}


 // end of partner functions file
 // in root/addons/partner/includes/functions.php
?>
