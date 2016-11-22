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

function add_testimonial(){
	if(is_admin()){
	if(isset($_POST['submit_testimonial'])){
		//print_r($_POST); die();
		$name=trim(mysql_prep($_POST['name_of_person']));
		$testimonial=trim(mysql_prep($_POST['testimonial']));
		
		$uploaddir = dirname(dirname(dirname(dirname(__FILE__)))).'/addons/testimonial/images/';
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
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT into testimonial(id,name,testimonial) VALUES ('0','{$name}','{$testimonial}')") 
		or die('Failed to save testimonial' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			session_message('success', 'Testimonial saved successfully');
			redirect_to(ADDONS_PATH.'testimonial');
			}
		}
	
	echo '<h1> Add Testimonial</h1>
	<form method="post" action="'.$_SESSION['cuurent_url'].'" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
	Send this file: <input type="file" size="500" name="image_field" value="">
	<input type="text" name="name_of_person" class="form-control" placeholder="Name of Individual">
	<textarea name="testimonial" class="form-control" placeholder="Details of your testimonial"></textarea>
	<input type="submit" name="submit_testimonial" value="Save testimonial" class="btn btn-primary">
	</form>';
	}
}


function show_testimonials(){
	
	global $r;
	$dir= $r.'/addons/testimonial/images/';
	$images = scandir($dir);
	
	//do delete
	if(!empty($_GET['delete']) && is_admin()){
		$delete = trim(mysql_prep($_GET['delete']));
		$id = mysql_prep($_GET['tid']);
		$rm = unlink($dir.$delete.'.jpg');
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM testimonial WHERE id='{$id}' and name='{$delete}'");
		//unlink($dir.$delete);
		
		if($query){
			session_message("success", "Testimonial deleted successfully!");
			redirect_to(ADDONS_PATH.'testimonial');
			} else {session_message("error", "Testimonial delete failed!");}
			redirect_to(ADDONS_PATH.'testimonial');
		}
		
		
	
	// Fetch testimonials from db
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM testimonial LIMIT 0, 30");
	while($result = mysqli_fetch_array($query)){
		echo '<div class="row whitesmoke center-block">';
		echo '<div class="col-md-5">';
		if(!empty($images)){
		//echo $images["{$result['name']}"];
		foreach ($images as $image){  
			if($image == $result['name'].'.jpg'){  
			echo '<span><img src="'.ADDONS_PATH.'testimonial/images/'.$result['name'].'.jpg" class="thumbnail" alt="" width="200" height="100" hspace="5" /></span>';
				
			}
		}

		echo '</div>';
		}
		echo '
		<div class="col-md-6 offset-by-1">
		Name : '.$result['name'].'<br>
		Testimonial : '.$result['testimonial'].'
		</div>';
		if(is_admin()){
				echo '<span class="tiny-text pull-right"><a href="'.ADDONS_PATH.'testimonial/?delete='.$result['name'].'&tid='.$result['id'].'">delete</a></span>';
				}
		echo '</div>';
		}
	
	
}


 // end of testimonial functions file
 // in root/addons/testimonial/includes/functions.php
?>
