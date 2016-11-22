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
require_once($r .'/includes/resize_class.php'); 
//print_r($_POST);

#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW
function get_page_ads(){
	if($_GET['page_name'] == 'home'){
		$page = 'Front page';
		} else {
		$page = $_SESSION['current_url'];
		}
	
	echo '<div class="text-center"><a  href="'.ADDONS_PATH.'ads">Advertise here</a></div>';
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `ads` WHERE `page_visibility`='{$page}' or `page_visibility`='All pages' ORDER BY id DESC LIMIT 0,4");
	while($result = mysqli_fetch_array($query)){
	echo '<div class="text-center ad_holder center-block"><div class=" center-block">';
	//if(is_admin() || $result['owner'] == $_SESSION['username']){
		//echo '<a class="tiny-edit-text" href="'.ADDONS_PATH.'ads/?action=view_ads&tid='.$result['id'].'">view</a><br>';
		//}
		echo '<hr>'.substr($result['ad_text'],0,20).'...<br>'.'<a href="'.ADDONS_PATH.'ads/?action=view_ads&tid='.$result['id'].'">'.
		'<img class="u-full-width " src="'.$result['thumbnail_path'].'" width="100%" height="90"><br>'.substr($result['link_to'],7,18).'&raquo;'.
		'</a><br></div>';
	echo '</div>';
		}
	
	}
	
	

function create_ads() {
	
	if(is_logged_in() && empty($_GET)){
	#$folder should end in a forward slash eg $folder = 'user/'
	global $r;

	if($r==='' && !url_contains('edit_')){
		$r = dirname(__FILE__);
		//$r2 = str_ireplace('/regions/','',$r);
		$r = $r2;
		}
$submit =  $_POST['submit'];

$uploaddir = $r.'addons/ads/ad_images/';
$uploadfile = $uploaddir . basename($_FILES['image_field']['name']);

//$m = str_ireplace('/regions/','',$uploadfile); // fixes a bugin upload_no_edit()
//$uploadfile = $m;
//echo $uploadfile;
$path = ADDONS_PATH.'ads/ad_images/'. basename($_FILES['image_field']['name']);
//$m = str_ireplace('/regions/','',$path);
//$path = $m;
$rpath = $r.'addons/ads/ad_images/'. basename($_FILES['image_field']['name']);
//$m = str_ireplace('/regions/','',$rpath); // fixes a bugin upload_no_edit()
//$rpath= $m;

	# ONSUBMIT
	if ($submit==='upload' && !empty($_FILES)){
		
		
   $type = $_FILES['image_field']['type'];
   $name = basename($_FILES['image_field']['name']);
   $owner = $_SESSION['username'];
   $image_path = ADDONS_PATH .'ads/ad_images/'.$name;
   $ad_text = trim(mysql_prep($_POST['ad_text']));
   $link_to = trim(mysql_prep($_POST['link_to']));
   $duration = trim(mysql_prep($_POST['duration']));
   $ad_type = trim(mysql_prep($_POST['ad_type']));
   $page_visibiity = trim(mysql_prep($_POST['page_visibility']));
   $path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
 
   $move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);

	if($move ==1){
			
		$thumbnail_path = resize_ad_thumbnail($pic=$rpath);
		$thumbnail_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $thumbnail_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		echo "<div class='message-notification'>File is valid, and was successfully uploaded.\n</div>";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `ads`(`id`, `owner`, `image_path`, `thumbnail_path`, `file_name`, `ad_text`, `link_to`, `duration`, `start_date`, `end_date`, `status`, `ad_type`, `page_visibility`) 
		VALUES ('0','{$owner}','{$image_path}','{$thumbnail_path}','{$name}','{$ad_text}','{$link_to}','{$duration}','','','','{$ad_type}','{$page_visibiity}')") 
		or die("Could not save image to DB!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$_SESSION['last_upload'] = $name;
		
		//if($query) { echo "Succesfully saved Ad!";} //testing
		redirect_to($_SESSION['current_url']);
	} else {
		echo "<div class='alert'>Error : No file uploaded!\n</div>";
	}
	
}
//echo 'Here is some more debugging info:' .$_FILES['image_field']['error']; //testing
	
	
# UPLOAD FORM
	echo '<form class="padding-20 whitesmoke" action="http://'
	.$_SERVER["HTTP_HOST"] .$_SERVER["REQUEST_URI"] .'" method="post" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <h3> Create new Ad </h3>
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
	<input type="file" size="500" name="image_field" value=""><em>All pictures will be resized to 180 x 90 thumbnails</em>
	<br><textarea name="ad_text" placeholder="Ad text" rows="2"></textarea>
	<br>Link: <input type="text" name="link_to" placeholder="(Optional webpage to link to)" value=""><br>
	<em>links should begin with http:// eg http://www.mysite.com</em>
	<br> Duration: <select name="duration">
	<option>1 week</option>
	<option>2 weeks</option>
	<option>3 weeks</option>
	<option>4 weeks</option>
	<option>2 months</option>
	<option>3 months</option>
	<option>6 months</option>
	</select>
	<br> Ad type: <select name="ad_type">
	<option>Image Ad</option>
	<option>Text Ad</option>
	</select>
	<br> Show on: <select name="page_visibility">
	<option>Front page</option>
	<option>All pages</option>
	<option>User pages</option>
	<option>Job pages</option>
	<option>Fundraiser pages</option>
	<option>Project pages</option>';
	$categories = get_categories();
	
	foreach($categories as $category){
		echo '<option>'.$category.'</option>';
		}
	echo '</select>
	<br><input type="submit" name="submit" value="upload" class="button-primary padding-10">
	</form>';
	echo '<em>' .$instruction .'</em>';
	
	}

}	

function view_ads(){
	if($_GET['action'] =='view_ads'){
	$output = view_item('ads');
	//print_r($output);
	echo '<div class="row">
	<div class=""><a href="'.$output['link_to'].'"><img src="'.$output['image_path'].'" class="img-responsive"></a></div>
	<div class="">'.$output['ad_text'].'<br>'.'<a href="'.$output['link_to'].'">'.$output['link_to'].'</a>';
	if($output['owner'] == $_SESSION['username'] || is_admin()){
		delete_ad('ads');
		show_delete_link($type='button',$file_name=$output['file_name']);
		}
	echo'</div>
	</div> ';
	
	}
}

function delete_ad($table='',$destination=''){
	if($_GET['action'] == "delete_{$table}" && !isset($_GET['do_delete'])){
		
	$id = trim(mysql_prep($_GET['tid']));
	
	$query= mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM ads WHERE id={$id}") or die("Failed to delete item" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	unlink($_SERVER['DOCUMENT_ROOT'].'/addons/ads/ad_images/'.$result['name']);
	redirect_to($_SESSION['prev_url']);
	
	} else if($_GET['do_delete'] == 'true'){
		
	$id = trim(mysql_prep($_GET['tid']));
	
	$query= mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM {$table} WHERE id={$id}")or die("Failed to delete item" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if($query){
		session_message('success', 'item deleted!');
			if($destination !=''){
			redirect_to($destination);
			}else{
				redirect_to($_SESSION['prev_url']);
				}
		}
	
	}

}

function view_ad_rates(){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ad_rates");
	while($result =mysqli_fetch_array($query)){
		echo ' * '.$result['ad_rate_name'].' &nbsp; : &nbsp;'.$result['amount'].' &nbsp; - &nbsp;'.$result['duration'].'<br>';
		}
	
}
	
function list_all_ads(){
	if(is_admin() && !isset($_GET['action'])){
	$pager = pagerize();
	$limit = $_SESSION['pager_limit'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ads {$limit}");
	echo '<h2>All ads</h2>';
	echo "<ol>";
	
	while ($result = mysqli_fetch_array($query)){
	echo '<li><a href="'.ADDONS_PATH.'ads/?action=view_ads&tid='.$result['id'].'">'.$result['ad_text'].'</a> -- Duration: '.$result['duration'].' &nbsp;<a href="'.BASE_PATH.'user/?user='.$result['owner'].'">'.$result['owner'].'</a></li>';
		
		}
	echo '</ol><hr>';
	}
}
	
function list_ads_by_owner(){
	if(is_logged_in() && !isset($_GET['action'])){
	$owner = $_SESSION['username'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ads WHERE owner='{$owner}'");
	$num = mysqli_num_rows($query);
	if(!empty($num)){
		echo '<h2>My ads</h2>';
		echo "<ol>";
		
		while ($result = mysqli_fetch_array($query)){
		echo '<li><a href="'.ADDONS_PATH.'ads/?action=view_ads&tid='.$result['id'].'">'.$result['ad_text'].'</a> -- Duration: '.$result['duration'].'</li>';
			
			}
		echo '</ol><hr>';
	} else { echo '<h2>My ads</h2> --- nil---- <em>You have not created any Ads yet!</em>';}
	}
}


function ads_prices(){
	if(!isset($_GET['action'])){
	
	if(is_logged_in()){
		echo '<h1>Advert rates</h1>';
		view_ad_rates();
		crud_do('ad_rates','create');
		}
	}
}

function resize_ad_thumbnail($pic='',$option='auto'){
	global $r;
	$width=180; 
	$height=90;
	$dest_folder= $r.'addons/ads/ad_images/thumbnails/'. basename($_FILES['image_field']['name']);
	$m = str_ireplace('regions/','',$dest_folder); // fixes a bugin upload_no_edit()
	$dest_folder = $m;
	$output = BASE_PATH.'addons/ads/ad_images/thumbnails/'. basename($_FILES['image_field']['name']);
	/**$folder is the folder name, eg thumbnail, medium etc
	 * $option is one of : exact, portrait, landscape, auto, crop
	 * */
	
	
	// USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 80);

return $output;
}

 // end of ads functions file
 // in root/addons/ads/includes/functions.php
?>
