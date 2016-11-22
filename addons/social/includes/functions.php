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

function add_social_link(){
	if(is_admin()){
	if(isset($_POST['submit_social'])){
		//print_r($_POST); die();
		$website_name=trim(mysql_prep($_POST['name_of_website']));
		$position=trim(mysql_prep($_POST['position']));
		$personal_url=trim(mysql_prep($_POST['personal_url']));
		
		$uploaddir = dirname(dirname(dirname(dirname(__FILE__)))).'/addons/social/images/';
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
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT into social(id,website_name,personal_url,position) VALUES ('0','{$website_name}','{$personal_url}','{$position}')") 
		or die('Failed to save social link' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			session_message('success', 'link saved successfully');
			redirect_to(ADDONS_PATH.'social');
			}
		}
	
	echo '<h1> Add social link</h1>
	<form method="post" action="'.$_SESSION['cuurent_url'].'" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
	Replace image: <input type="file" size="500" name="image_field" value="">
	Choose Network:<br><select name="name_of_website">
	<option>facebook</option>
	<option>twitter</option>
	<option>linkedin</option>
	<option>googleplus</option>
	<option>instagram</option>
	<option>youtube</option>
	<option>yahoo</option>
	<option>wordpress</option>
	<option>vimeo</option>
	<option>digg</option>
	<option>foursquare</option>
	<option>tumblr</option>
	<option>technorati</option>
	<option>myspace</option>
	<option>yelp</option>
	<option>skype</option>
	<option>stumbleupon</option>
	<option>lastfm</option>
	</select>
	<input type="text" name="personal_url" class="form-control" placeholder="Personal link/ profile link">
	<em>MUST START with http:// eg http://facebook.com/mypage</em>
	<br>Position: Higher numbers come last<br>
	<select name="position">
	<option>1</option>
	<option>2</option>
	<option>3</option>
	<option>4</option>
	<option>5</option>
	<option>6</option>
	<option>7</option>
	</select>
	<input type="submit" name="submit_social" value="Save social link" class="btn btn-primary">
	</form>';
	}
}


function show_social_icons(){
	
	global $r;
	
	$r = str_ireplace('regions/','',$r); 
	$dir= $r.'/addons/social/images/';
	$images = scandir($dir);
	
	//do delete
	if(!empty($_GET['delete']) && is_admin()){
		$delete = trim(mysql_prep($_GET['delete']));
		$id = mysql_prep($_GET['tid']);
		//$rm = unlink($dir.$delete.'.png');
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM social WHERE id='{$id}' and website_name='{$delete}'");
		//unlink($dir.$delete);
		
		if($query){
			session_message("success", "Social link deleted successfully!");
			redirect_to(ADDONS_PATH.'social');
			} else {session_message("error", "Social link delete failed!");}
			redirect_to(ADDONS_PATH.'social');
		}
		
		
	
	// Fetch social icons
	echo '<div class="row smart-dark-blue"><div class="margin-10 center-block">';
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM social LIMIT 0, 30");
	while($result = mysqli_fetch_array($query)){
		
		
		if(!empty($images)){
		//echo $images["{$result['name']}"];
			foreach ($images as $image){  
				if($image == $result['website_name'].'.png'){  
				echo '<span class="thumbnail pull-left inline-block margin-10"><a target="_BLANK"href="'.$result['personal_url'].'"><img src="'.ADDONS_PATH.'social/images/'.$result['website_name'].'.png" alt="" width="64" height="64" hspace="5" /></a>';
				if(is_admin()){
				echo '<span class="tiny-text pull-right"><a href="'.ADDONS_PATH.'social/?delete='.$result['website_name'].'&tid='.$result['id'].'">delete</a></span>';
				}
				echo'</span>';
		
				}
			}

		}
		
		
		
		}
	echo '</div></div>';
	
}


 // end of social functions file
 // in root/addons/social/includes/functions.php
?>
