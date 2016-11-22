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

function add_staff(){
	if(is_admin()){
	if(isset($_POST['submit_staff'])){
		//print_r($_POST); die();
		$name=trim(mysql_prep($_POST['name_of_person']));
		$position=trim(mysql_prep($_POST['position']));
		
		$uploaddir = dirname(dirname(dirname(dirname(__FILE__)))).'/addons/staff/images/';
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
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT into staff(id,name,position) VALUES ('0','{$name}','{$position}')") 
		or die('Failed to save staff' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			session_message('success', 'Staff saved successfully');
			redirect_to(ADDONS_PATH.'staff');
			}
		}
	
	echo '<h1> Add staff</h1>
	<form method="post" action="'.$_SESSION['cuurent_url'].'" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
	Send this file: <input type="file" size="500" name="image_field" value="">
	<input type="text" name="name_of_person" class="form-control" placeholder="Name of Individual">
	<input type="text" name="position" class="form-control" placeholder="Position / Title">
	<input type="submit" name="submit_staff" value="Save staff" class="btn btn-primary">
	</form>';
	}
}


function show_staff(){
	
	global $r;
	$dir= $r.'/addons/staff/images/';
	$images = scandir($dir);
	
	//do delete
	if(!empty($_GET['delete']) && is_admin()){
		$delete = trim(mysql_prep($_GET['delete']));
		$id = mysql_prep($_GET['tid']);
		$rm = unlink($dir.$delete.'.jpg');
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM staff WHERE id='{$id}' and name='{$delete}'");
		//unlink($dir.$delete);
		
		if($query){
			session_message("success", "Staff deleted successfully!");
			redirect_to(ADDONS_PATH.'staff');
			} else {session_message("error", "Staff delete failed!");}
			redirect_to(ADDONS_PATH.'staff');
		}
		
		
	
	// Fetch testimonials from db
	echo '<div class="row smart-dark-blue">';
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM staff LIMIT 0, 30");
	while($result = mysqli_fetch_array($query)){
		
		
		if(!empty($images)){
		//echo $images["{$result['name']}"];
			foreach ($images as $image){  
				if($image == $result['name'].'.jpg'){  
				echo '<span class="thumbnail pull-left inline-block margin-10"><b>'.$result["name"].'</b><br><img src="'.ADDONS_PATH.'staff/images/'.$result['name'].'.jpg" alt="" width="250" height="350" hspace="5" />
				Position/ Title : '.$result['position'];
				if(is_admin()){
				echo '<span class="tiny-text pull-right"><a href="'.ADDONS_PATH.'staff/?delete='.$result['name'].'&tid='.$result['id'].'">delete</a></span>';
				}
				echo'</span>';
		
				}
			}

		}
		
		
		
		}
	echo '</div>';
	
}


 // end of staff functions file
 // in root/addons/staff/includes/functions.php
?>
