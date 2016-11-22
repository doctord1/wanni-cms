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
 
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit



#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW


function show_slideshow_block($images='',$width='',$height=''){
	# $images should be an array of images

	if($images !== ''){
		echo '<div class="slider-wrapper theme-default">
    <div id="slider" class="nivoSlider">';
    
    foreach ($images as $image){    
		echo $image;
		}

	echo '</div></div>';
		} else {
				global $r;
				$dir= $r.'slideshow/images/';
				$images = scandir($dir);
			
		echo '<div class="slider-wrapper theme-default">
    <div id="slider" class="nivoSlider">';
    
    foreach ($images as $image){  
		if($image !== '.' && $image !== '..' && is_file($dir.$image)){  
		echo '<img src="'.BASE_PATH.'slideshow/images/'.$image.'" data-thumb="images/slide1.png" alt="" width="100%" height="450px"/>';
		}
	}

	echo '</div></div>';
		}

}


function upload_slideshow_pics(){ #NEEDS WORK
	
$submit =  $_POST['submit'];
$uploaddir = dirname(dirname(dirname(__FILE__))).'/slideshow/images/';
$uploadfile = $uploaddir . basename($_FILES['image_field']['name']);

# ONSUBMIT
if (isset($submit)){
   $type = $_FILES['image_field']['type'];

   if ($_SESSION['role'] ==='manager' || $_SESSION['role'] ==='admin'){
	   
	 $move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);

		if($move ==1){ 
			echo "<div class='success'>File is valid, and was successfully uploaded.\n</div>";
				
			} else { echo "<div class='alert'>Error : No file uploaded!\n</div>"; }
		
	}
	
}
	
	# UPLOAD FORM
	echo '<hr><div class=""><h2> Upload Files </h2><form action="'
	.$SERVER["PHP_SELF"] .'" method="post" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
	Send this file: <input type="file" size="500" name="image_field" value="">
	<input type="submit" name="submit" value="upload" class="submit">
	</form></div>';
#echo 'Here is some more debugging info:' .$_FILES['image_field']['error']; //testing

}

function show_uploaded_slides(){
	global $r;
	$dir= $r.'slideshow/images/';
	$images = scandir($dir);
	
	//do delete
	if(!empty($_GET['delete'])){
		$delete = trim(mysql_prep($_GET['delete']));
		$rm = unlink($dir.$delete);
		unlink($dir."large-size/".$delete);
		unlink($dir."medium-size/".$delete);
		unlink($dir."small-size/".$delete);
		
		if($rm){
			session_message("success", "File deleted successfully!");
			redirect_to(BASE_PATH.'slideshow');
			} else { status_message("error", "Unlink failed!");}
		}
	
	
	if(!empty($images)){
		echo '<div class="">';
    
    foreach ($images as $image){  
		if($image !== '.' && $image !== '..' && is_file($dir.$image)){  
		echo '<span><img src="'.BASE_PATH.'slideshow/images/'.$image.'" alt="" width="200" height="100" hspace="5" /></span>
		<span class="tiny-text"><a href="'.BASE_PATH.'slideshow/?delete='.$image.'">delete</a></span>';
		}
	}

	echo '</div>';
		}
	
}

	
 // end of slideshow functions file
 // in root/slideshow/includes/functions.php
?>
