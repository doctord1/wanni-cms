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
//echo $r;
require_once($r .'includes/functions.php'); #do not edit

#print_r($_SERVER);
#print_r($_POST);
#======================================================================
#						TEMPLATE ENDS
#======================================================================

echo "<style type='text/css'>
.caption-area{
position: absolute;
top: 80%;
left: 0;
padding: 10px;
background-color: gainsboro;
z-index: 5;
}

.set-profile-photo{
display: block;
position: absolute;
top: 80%;
z-index:50;
padding: 3px;
background-color: #ddd;
font-size: 12px;
}

.set-profile-photo:hover{
background-color: lightblue;
cursor: pointer;
}

</style>";

#				 ADD YOUR CUSTOM ADDON CODE BELOW

function upload_to_gallery(){
	global $r;
$submit =  $_POST['submit'];
$uploaddir = $r.'uploads/files/';
$uploadfile = $uploaddir. basename($_FILES['image_field']['name']);
$path = 'uploads/files/'. basename($_FILES['image_field']['name']);
$rpath = $r.'uploads/files/'. basename($_FILES['image_field']['name']);

	# ONSUBMIT
	if (isset($submit) && $_POST['submit'] =='upload Selected file'){
   $type = $_FILES['image_field']['type'];
   $name = basename($_FILES['image_field']['name']);
   
   $path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

   if (isset($_GET['owner'])){
   $owner = trim(mysql_prep($_GET['owner'])) ;
	} else {$owner = $_SESSION['username'];}
   
   $move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);
	
	if($move ==1){
		$resized_path = gallery_resize_pic($pic=$rpath);
		$resized_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $resized_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		$small_path = resize_pic_small($pic=$rpath);
		$small_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $small_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		$created = date('d-m-Y');
		$updated = date('d-m-Y');

		
		session_message('success',"File is valid, and was successfully uploaded");
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `gallery`(`id`, `owner`, `path`, `created`, `private`)
		 VALUES ('0', '{$owner}', '{$resized_path}', '{$created}', '0')") 
		or die("Could not save image to DB!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query) { redirect_to($_SESSiON['current_url']);} //testing
	} else {
		echo "<div class='alert'>Error : No file uploaded!\n</div>";
	}
	
}
//echo 'Here is some more debugging info:' .$_FILES['image_field']['error']; //testing
	
	
# UPLOAD FORM
	echo '<hr>'. $file_type.'<form action="'
	.htmlentities($SERVER["PHP_SELF"]) .'" method="post" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
	Select file: <input type="file" size="500" name="image_field" value="">
	<input type="submit" name="submit" value="upload Selected file" class="submit">
	</form>';
	echo '<em>' .$instruction .'</em>';
	
	show_free_images();

}
	
	
function show_user_gallery($start,$stop){
	//print_r($_POST);
	if($_POST['private']=='on'){
		$id = $_POST['id'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE gallery SET `private`='yes' WHERE id='{$id}'") or die (((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		} 
	if($_POST['public']=='on'){
		$id = $_POST['id'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE gallery SET `private`='no' WHERE id='{$id}'") or die (((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		} 
	
	if(isset($_GET['owner']) && $_GET['gallery_id'] == $_SESSION['control']){
		$owner = trim(mysql_prep($_GET['owner']));
		
		$is_owner = '';
		if($_SESSION['username'] == $owner){
		$is_owner = true;
		} 
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `gallery` WHERE `owner`='{$owner}' AND private!='yes' ORDER BY `id` DESC LIMIT {$start}, {$stop}") 
		or die('Failed to get gallery images' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	
		
		$count = mysqli_num_rows($query);
		$_SESSION['gallery_count'] = $count;
		
		
		while($result = mysqli_fetch_array($query)){
			
			if($is_owner || is_admin()){
				echo "<div class='mosaic-block'><form method='post' action='".$_SESSION['current_url']."'>";
				echo "<a href='".ADDONS_PATH."gallery/process.php?delete_gallery_image={$result['id']}'><span class='whitesmoke'> delete </span></a>";
			echo "<input type='hidden' name='id' value={$result['id']}>".
		"<span class='pull-right'><i class='glyphicon glyphicon-lock'></i> Private <input type='checkbox' name='private' onchange='this.form.submit()'> &nbsp;</span>";
			
					echo "<a href='".BASE_PATH.$result['path']."' rel='prettyPhoto[{$result['owner']}_gal]'>".
		"<img src='".BASE_PATH.$result['path']."' alt='".$images_array['name']."'>".
		"</a>".
		"</form>".

			"</div>";
			} else{
			echo "<div class='mosaic-block'><a href='".BASE_PATH.$result['path']."' rel='prettyPhoto[{$result['owner']}_gal]'>".
		"<img src='".BASE_PATH.$result['path']."'>".
		"</a>".
		"</div>"; 
			}			 	
		}
		
		// GET PRIVATE PHOTOS
		
		if($_SESSION['username'] == $owner){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `gallery` WHERE `owner`='{$owner}' AND private='yes' ORDER BY `id` DESC LIMIT {$start}, {$stop}") 
		or die('Failed to get gallery images' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	
		echo "<div class='private-photos'><h2 align='center'>Private Photos</h2>";
		
		while($result = mysqli_fetch_array($query)){
			echo "<div class='mosaic-block'><form method='post' action='".$_SESSION['current_url']."'>";
				echo "<a href='".ADDONS_PATH."gallery/process.php?delete_gallery_image={$result['id']}'><span class='whitesmoke'> delete </span></a>";
			echo "<input type='hidden' name='id' value={$result['id']}>".
		"<span class='pull-right'><i class='glyphicon glyphicon-unlock'></i> Make public <input type='checkbox' name='public' onchange='this.form.submit()'> &nbsp;</span>";
			
					echo "<a href='".BASE_PATH.$result['path']."' rel='prettyPhoto[{$result['owner']}_gal]'>".
		"<img src='".BASE_PATH.$result['path']."'>".
		"</a>".
		"</form>".

			"</div>";
		} 	
		if($_SESSION['username'] == $owner){
		echo '</div>'; // close private photos div
		}
	}
		if($count < 1){
			echo "<em>There are no visible items in this gallery.</em>";
			go_back(); 
			}
	} else {
                $owner = trim(mysql_prep($_GET['owner']));
		status_message('alert', "You must have reached this page in error, go to {$owner}'s to view {$owner}'s gallery!");
		}
}

function gallery_item_count(){
	$user = $_SESSION['user_being_viewed'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(*) as total from `gallery` WHERE `owner`='{$user}'");
	$result = mysqli_fetch_assoc($query);
	return $result['total'];
	}
	
function show_gallery_carousel(){
	$owner = trim(mysql_prep($_GET['user']));
	$gallery_id = $_SESSION['control'];
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `gallery` WHERE `owner`='{$owner}' AND private!='yes' ORDER BY `id` DESC LIMIT 0, 8") 
	or die('Failed to get gallery images' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	echo "<div class='gallery-carousel center-block'>";	
	while($result = mysqli_fetch_array($query)){
	echo "<div class='carousel-images'><a href='".ADDONS_PATH."gallery/?owner={$owner}&gallery_id={$gallery_id}'><img src='".BASE_PATH.$result['path']."' width='50px' height='50px'></a></div>";
		}
	echo "</div>";
	}

function show_link_to_gallery(){
	$owner = trim(mysql_prep($_GET['user']));
	$gallery_id = $_SESSION['control'];
	$total = gallery_item_count();
	echo "<h2>My Photo gallery</h2>";
echo "<div class='text-center'><a href='".ADDONS_PATH."gallery/?owner={$owner}&gallery_id={$gallery_id}'>My pictures ({$total})</a></div>";	
show_gallery_carousel();
}

function gallery_resize_pic($pic='',$option='auto'){
	$owner = trim(mysql_prep($_GET['owner']));
	global $r;
	$width=500; 
	$height=500;
	$dest_folder= $r.'uploads/files/'.$owner.'_gallery_'. basename($_FILES['image_field']['name']);
	$output = 'uploads/files/'.$owner.'_gallery_'. basename($_FILES['image_field']['name']);
	/**
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

function style_prettyPhoto(){
	echo '<style>
	div.pp_default .pp_top,
	div.pp_default .pp_top .pp_middle,
	div.pp_default .pp_top .pp_left,
	div.pp_default .pp_top .pp_right,
	div.pp_default .pp_bottom,
	div.pp_default .pp_bottom .pp_left,
	div.pp_default .pp_bottom .pp_middle,
	div.pp_default .pp_bottom .pp_right { height: 13px; }
	
	div.pp_default .pp_top .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) -78px -93px no-repeat; } /* Top left corner */
	div.pp_default .pp_top .pp_middle { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite_x.png) top left repeat-x; } /* Top pattern/color */
	div.pp_default .pp_top .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) -112px -93px no-repeat; } /* Top right corner */
	
	div.pp_default .pp_content .ppt { color: #f8f8f8; }
	div.pp_default .pp_content_container .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite_y.png) -7px 0 repeat-y; padding-left: 13px; }
	div.pp_default .pp_content_container .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite_y.png) top right repeat-y; padding-right: 13px; }
	div.pp_default .pp_content { background-color: #fff; } /* Content background */
	div.pp_default .pp_next:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite_next.png) center right  no-repeat; cursor: pointer; } /* Next button */
	div.pp_default .pp_previous:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite_prev.png) center left no-repeat; cursor: pointer; } /* Previous button */
	div.pp_default .pp_expand { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) 0 -29px no-repeat; display:inline-block; cursor: pointer; width: 28px; height: 28px; } /* Expand button */
	div.pp_default .pp_expand:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) 0 -56px no-repeat; cursor: pointer; } /* Expand button hover */
	div.pp_default .pp_contract { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) 0 -84px no-repeat; cursor: pointer; width: 28px; height: 28px; } /* Contract button */
	div.pp_default .pp_contract:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) 0 -113px no-repeat; cursor: pointer; } /* Contract button hover */
	div.pp_default .pp_close { width: 30px; height: 30px; background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) 2px 1px no-repeat; cursor: pointer; } /* Close button */
	div.pp_default #pp_full_res .pp_inline { color: #000; } 
	div.pp_default .pp_gallery ul li a { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/default_thumb.png) center center #f8f8f8; border:1px solid #aaa; }
	div.pp_default .pp_gallery ul li a:hover,
	div.pp_default .pp_gallery ul li.selected a { border-color: #fff; }
	div.pp_default .pp_social { margin-top: 7px; }

	div.pp_default .pp_gallery a.pp_arrow_previous,
	div.pp_default .pp_gallery a.pp_arrow_next { position: static; left: auto; }
	div.pp_default .pp_nav .pp_play,
	div.pp_default .pp_nav .pp_pause { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) -51px 1px no-repeat; height:30px; width:30px; }
	div.pp_default .pp_nav .pp_pause { background-position: -51px -29px; }
	div.pp_default .pp_details { position: relative; }
	div.pp_default a.pp_arrow_previous,
	div.pp_default a.pp_arrow_next { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) -31px -3px no-repeat; height: 20px; margin: 4px 0 0 0; width: 20px; }
	div.pp_default a.pp_arrow_next { left: 52px; background-position: -82px -3px; } /* The next arrow in the bottom nav */
	div.pp_default .pp_content_container .pp_details { margin-top: 5px; }
	div.pp_default .pp_nav { clear: none; height: 30px; width: 110px; position: relative; }
	div.pp_default .pp_nav .currentTextHolder{ font-family: Georgia; font-style: italic; color:#999; font-size: 11px; left: 75px; line-height: 25px; margin: 0; padding: 0 0 0 10px; position: absolute; top: 2px; }
	
	div.pp_default .pp_close:hover, div.pp_default .pp_nav .pp_play:hover, div.pp_default .pp_nav .pp_pause:hover, div.pp_default .pp_arrow_next:hover, div.pp_default .pp_arrow_previous:hover { opacity:0.7; }

	div.pp_default .pp_description{ font-size: 11px; font-weight: bold; line-height: 14px; margin: 5px 50px 5px 0; }

	div.pp_default .pp_bottom .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) -78px -127px no-repeat; } /* Bottom left corner */
	div.pp_default .pp_bottom .pp_middle { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite_x.png) bottom left repeat-x; } /* Bottom pattern/color */
	div.pp_default .pp_bottom .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/sprite.png) -112px -127px no-repeat; } /* Bottom right corner */

	div.pp_default .pp_loaderIcon { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/default/loader.gif) center center no-repeat; } /* Loader icon */

	
	/* ----------------------------------
		Light Rounded Theme
	----------------------------------- */


	div.light_rounded .pp_top .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) -88px -53px no-repeat; } /* Top left corner */
	div.light_rounded .pp_top .pp_middle { background: #fff; } /* Top pattern/color */
	div.light_rounded .pp_top .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) -110px -53px no-repeat; } /* Top right corner */
	
	div.light_rounded .pp_content .ppt { color: #000; }
	div.light_rounded .pp_content_container .pp_left,
	div.light_rounded .pp_content_container .pp_right { background: #fff; }
	div.light_rounded .pp_content { background-color: #fff; } /* Content background */
	div.light_rounded .pp_next:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/btnNext.png) center right  no-repeat; cursor: pointer; } /* Next button */
	div.light_rounded .pp_previous:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/btnPrevious.png) center left no-repeat; cursor: pointer; } /* Previous button */
	div.light_rounded .pp_expand { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) -31px -26px no-repeat; cursor: pointer; } /* Expand button */
	div.light_rounded .pp_expand:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) -31px -47px no-repeat; cursor: pointer; } /* Expand button hover */
	div.light_rounded .pp_contract { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) 0 -26px no-repeat; cursor: pointer; } /* Contract button */
	div.light_rounded .pp_contract:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) 0 -47px no-repeat; cursor: pointer; } /* Contract button hover */
	div.light_rounded .pp_close { width: 75px; height: 22px; background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) -1px -1px no-repeat; cursor: pointer; } /* Close button */
	div.light_rounded .pp_details { position: relative; }
	div.light_rounded .pp_description { margin-right: 85px; }
	div.light_rounded #pp_full_res .pp_inline { color: #000; } 
	div.light_rounded .pp_gallery a.pp_arrow_previous,
	div.light_rounded .pp_gallery a.pp_arrow_next { margin-top: 12px !important; }
	div.light_rounded .pp_nav .pp_play { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) -1px -100px no-repeat; height: 15px; width: 14px; }
	div.light_rounded .pp_nav .pp_pause { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) -24px -100px no-repeat; height: 15px; width: 14px; }

	div.light_rounded .pp_arrow_previous { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) 0 -71px no-repeat; } /* The previous arrow in the bottom nav */
		div.light_rounded .pp_arrow_previous.disabled { background-position: 0 -87px; cursor: default; }
	div.light_rounded .pp_arrow_next { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) -22px -71px no-repeat; } /* The next arrow in the bottom nav */
		div.light_rounded .pp_arrow_next.disabled { background-position: -22px -87px; cursor: default; }

	div.light_rounded .pp_bottom .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) -88px -80px no-repeat; } /* Bottom left corner */
	div.light_rounded .pp_bottom .pp_middle { background: #fff; } /* Bottom pattern/color */
	div.light_rounded .pp_bottom .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/sprite.png) -110px -80px no-repeat; } /* Bottom right corner */

	div.light_rounded .pp_loaderIcon { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/loader.gif) center center no-repeat; } /* Loader icon */
	
	/* ----------------------------------
		Dark Rounded Theme
	----------------------------------- */
	
	div.dark_rounded .pp_top .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) -88px -53px no-repeat; } /* Top left corner */
	div.dark_rounded .pp_top .pp_middle { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/contentPattern.png) top left repeat; } /* Top pattern/color */
	div.dark_rounded .pp_top .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) -110px -53px no-repeat; } /* Top right corner */
	
	div.dark_rounded .pp_content_container .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/contentPattern.png) top left repeat-y; } /* Left Content background */
	div.dark_rounded .pp_content_container .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/contentPattern.png) top right repeat-y; } /* Right Content background */
	div.dark_rounded .pp_content { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/contentPattern.png) top left repeat; } /* Content background */
	div.dark_rounded .pp_next:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/btnNext.png) center right  no-repeat; cursor: pointer; } /* Next button */
	div.dark_rounded .pp_previous:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/btnPrevious.png) center left no-repeat; cursor: pointer; } /* Previous button */
	div.dark_rounded .pp_expand { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) -31px -26px no-repeat; cursor: pointer; } /* Expand button */
	div.dark_rounded .pp_expand:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) -31px -47px no-repeat; cursor: pointer; } /* Expand button hover */
	div.dark_rounded .pp_contract { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) 0 -26px no-repeat; cursor: pointer; } /* Contract button */
	div.dark_rounded .pp_contract:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) 0 -47px no-repeat; cursor: pointer; } /* Contract button hover */
	div.dark_rounded .pp_close { width: 75px; height: 22px; background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) -1px -1px no-repeat; cursor: pointer; } /* Close button */
	div.dark_rounded .pp_details { position: relative; }
	div.dark_rounded .pp_description { margin-right: 85px; }
	div.dark_rounded .currentTextHolder { color: #c4c4c4; }
	div.dark_rounded .pp_description { color: #fff; }
	div.dark_rounded #pp_full_res .pp_inline { color: #fff; }
	div.dark_rounded .pp_gallery a.pp_arrow_previous,
	div.dark_rounded .pp_gallery a.pp_arrow_next { margin-top: 12px !important; }
	div.dark_rounded .pp_nav .pp_play { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) -1px -100px no-repeat; height: 15px; width: 14px; }
	div.dark_rounded .pp_nav .pp_pause { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) -24px -100px no-repeat; height: 15px; width: 14px; }

	div.dark_rounded .pp_arrow_previous { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) 0 -71px no-repeat; } /* The previous arrow in the bottom nav */
		div.dark_rounded .pp_arrow_previous.disabled { background-position: 0 -87px; cursor: default; }
	div.dark_rounded .pp_arrow_next { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) -22px -71px no-repeat; } /* The next arrow in the bottom nav */
		div.dark_rounded .pp_arrow_next.disabled { background-position: -22px -87px; cursor: default; }

	div.dark_rounded .pp_bottom .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) -88px -80px no-repeat; } /* Bottom left corner */
	div.dark_rounded .pp_bottom .pp_middle { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/contentPattern.png) top left repeat; } /* Bottom pattern/color */
	div.dark_rounded .pp_bottom .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/sprite.png) -110px -80px no-repeat; } /* Bottom right corner */

	div.dark_rounded .pp_loaderIcon { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_rounded/loader.gif) center center no-repeat; } /* Loader icon */
	
	
	/* ----------------------------------
		Dark Square Theme
	----------------------------------- */
	
	div.dark_square .pp_left ,
	div.dark_square .pp_middle,
	div.dark_square .pp_right,
	div.dark_square .pp_content { background: #000; }
	
	div.dark_square .currentTextHolder { color: #c4c4c4; }
	div.dark_square .pp_description { color: #fff; }
	div.dark_square .pp_loaderIcon { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/loader.gif) center center no-repeat; } /* Loader icon */
	
	div.dark_square .pp_expand { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/sprite.png) -31px -26px no-repeat; cursor: pointer; } /* Expand button */
	div.dark_square .pp_expand:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/sprite.png) -31px -47px no-repeat; cursor: pointer; } /* Expand button hover */
	div.dark_square .pp_contract { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/sprite.png) 0 -26px no-repeat; cursor: pointer; } /* Contract button */
	div.dark_square .pp_contract:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/sprite.png) 0 -47px no-repeat; cursor: pointer; } /* Contract button hover */
	div.dark_square .pp_close { width: 75px; height: 22px; background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/sprite.png) -1px -1px no-repeat; cursor: pointer; } /* Close button */
	div.dark_square .pp_details { position: relative; }
	div.dark_square .pp_description { margin: 0 85px 0 0; }
	div.dark_square #pp_full_res .pp_inline { color: #fff; }
	div.dark_square .pp_gallery a.pp_arrow_previous,
	div.dark_square .pp_gallery a.pp_arrow_next { margin-top: 12px !important; }
	div.dark_square .pp_nav { clear: none; }
	div.dark_square .pp_nav .pp_play { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/sprite.png) -1px -100px no-repeat; height: 15px; width: 14px; }
	div.dark_square .pp_nav .pp_pause { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/sprite.png) -24px -100px no-repeat; height: 15px; width: 14px; }
	
	div.dark_square .pp_arrow_previous { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/sprite.png) 0 -71px no-repeat; } /* The previous arrow in the bottom nav */
		div.dark_square .pp_arrow_previous.disabled { background-position: 0 -87px; cursor: default; }
	div.dark_square .pp_arrow_next { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/sprite.png) -22px -71px no-repeat; } /* The next arrow in the bottom nav */
		div.dark_square .pp_arrow_next.disabled { background-position: -22px -87px; cursor: default; }
	
	div.dark_square .pp_next:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/btnNext.png) center right  no-repeat; cursor: pointer; } /* Next button */
	div.dark_square .pp_previous:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/dark_square/btnPrevious.png) center left no-repeat; cursor: pointer; } /* Previous button */


	/* ----------------------------------
		Light Square Theme
	----------------------------------- */
	
	div.light_square .pp_left ,
	div.light_square .pp_middle,
	div.light_square .pp_right,
	div.light_square .pp_content { background: #fff; }
	
	div.light_square .pp_content .ppt { color: #000; }
	div.light_square .pp_expand { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/sprite.png) -31px -26px no-repeat; cursor: pointer; } /* Expand button */
	div.light_square .pp_expand:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/sprite.png) -31px -47px no-repeat; cursor: pointer; } /* Expand button hover */
	div.light_square .pp_contract { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/sprite.png) 0 -26px no-repeat; cursor: pointer; } /* Contract button */
	div.light_square .pp_contract:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/sprite.png) 0 -47px no-repeat; cursor: pointer; } /* Contract button hover */
	div.light_square .pp_close { width: 75px; height: 22px; background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/sprite.png) -1px -1px no-repeat; cursor: pointer; } /* Close button */
	div.light_square .pp_details { position: relative; }
	div.light_square .pp_description { margin-right: 85px; }
	div.light_square #pp_full_res .pp_inline { color: #000; }
	div.light_square .pp_gallery a.pp_arrow_previous,
	div.light_square .pp_gallery a.pp_arrow_next { margin-top: 12px !important; }
	div.light_square .pp_nav .pp_play { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/sprite.png) -1px -100px no-repeat; height: 15px; width: 14px; }
	div.light_square .pp_nav .pp_pause { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/sprite.png) -24px -100px no-repeat; height: 15px; width: 14px; }
	
	div.light_square .pp_arrow_previous { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/sprite.png) 0 -71px no-repeat; } /* The previous arrow in the bottom nav */
		div.light_square .pp_arrow_previous.disabled { background-position: 0 -87px; cursor: default; }
	div.light_square .pp_arrow_next { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/sprite.png) -22px -71px no-repeat; } /* The next arrow in the bottom nav */
		div.light_square .pp_arrow_next.disabled { background-position: -22px -87px; cursor: default; }
	
	div.light_square .pp_next:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/btnNext.png) center right  no-repeat; cursor: pointer; } /* Next button */
	div.light_square .pp_previous:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_square/btnPrevious.png) center left no-repeat; cursor: pointer; } /* Previous button */
	
	div.light_square .pp_loaderIcon { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/loader.gif) center center no-repeat; } /* Loader icon */


	/* ----------------------------------
		Facebook style Theme
	----------------------------------- */
	
	div.facebook .pp_top .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) -88px -53px no-repeat; } /* Top left corner */
	div.facebook .pp_top .pp_middle { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/contentPatternTop.png) top left repeat-x; } /* Top pattern/color */
	div.facebook .pp_top .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) -110px -53px no-repeat; } /* Top right corner */
	
	div.facebook .pp_content .ppt { color: #000; }
	div.facebook .pp_content_container .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/contentPatternLeft.png) top left repeat-y; } /* Content background */
	div.facebook .pp_content_container .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/contentPatternRight.png) top right repeat-y; } /* Content background */
	div.facebook .pp_content { background: #fff; } /* Content background */
	div.facebook .pp_expand { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) -31px -26px no-repeat; cursor: pointer; } /* Expand button */
	div.facebook .pp_expand:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) -31px -47px no-repeat; cursor: pointer; } /* Expand button hover */
	div.facebook .pp_contract { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) 0 -26px no-repeat; cursor: pointer; } /* Contract button */
	div.facebook .pp_contract:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) 0 -47px no-repeat; cursor: pointer; } /* Contract button hover */
	div.facebook .pp_close { width: 22px; height: 22px; background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) -1px -1px no-repeat; cursor: pointer; } /* Close button */
	div.facebook .pp_details { position: relative; }
	div.facebook .pp_description { margin: 0 37px 0 0; }
	div.facebook #pp_full_res .pp_inline { color: #000; } 
	div.facebook .pp_loaderIcon { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/loader.gif) center center no-repeat; } /* Loader icon */
	
	div.facebook .pp_arrow_previous { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) 0 -71px no-repeat; height: 22px; margin-top: 0; width: 22px; } /* The previous arrow in the bottom nav */
		div.facebook .pp_arrow_previous.disabled { background-position: 0 -96px; cursor: default; }
	div.facebook .pp_arrow_next { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) -32px -71px no-repeat; height: 22px; margin-top: 0; width: 22px; } /* The next arrow in the bottom nav */
		div.facebook .pp_arrow_next.disabled { background-position: -32px -96px; cursor: default; }
	div.facebook .pp_nav { margin-top: 0; }
	div.facebook .pp_nav p { font-size: 15px; padding: 0 3px 0 4px; }
	div.facebook .pp_nav .pp_play { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) -1px -123px no-repeat; height: 22px; width: 22px; }
	div.facebook .pp_nav .pp_pause { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) -32px -123px no-repeat; height: 22px; width: 22px; }
	
	div.facebook .pp_next:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/btnNext.png) center right no-repeat; cursor: pointer; } /* Next button */
	div.facebook .pp_previous:hover { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/btnPrevious.png) center left no-repeat; cursor: pointer; } /* Previous button */
	
	div.facebook .pp_bottom .pp_left { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) -88px -80px no-repeat; } /* Bottom left corner */
	div.facebook .pp_bottom .pp_middle { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/contentPatternBottom.png) top left repeat-x; } /* Bottom pattern/color */
	div.facebook .pp_bottom .pp_right { background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/sprite.png) -110px -80px no-repeat; } /* Bottom right corner */


/* ------------------------------------------------------------------------
	DO NOT CHANGE
------------------------------------------------------------------------- */

	div.pp_pic_holder a:focus { outline:none; }

	div.pp_overlay {
		background: #000;
		display: none;
		left: 0;
		position: absolute;
		top: 0;
		width: 100%;
		z-index: 9500;
	}
	
	div.pp_pic_holder {
		display: none;
		position: fixed;
		width: 100px;
		z-index: 10000;
	}
	

		
		.pp_top {
			height: 20px;
			position: relative;
		}
			* html .pp_top { padding: 0 20px; }
		
			.pp_top .pp_left {
				height: 20px;
				left: 0;
				position: absolute;
				width: 20px;
			}
			.pp_top .pp_middle {
				height: 20px;
				left: 20px;
				position: absolute;
				right: 20px;
			}
				* html .pp_top .pp_middle {
					left: 0;
					position: static;
				}
			
			.pp_top .pp_right {
				height: 20px;
				left: auto;
				position: absolute;
				right: 0;
				top: 0;
				width: 20px;
			}
		
		.pp_content { height: 40px; min-width: 40px; }
		* html .pp_content { width: 40px; }
		
		.pp_fade { display: none; }
		
		.pp_content_container {
			position: relative;
			text-align: left;
			width: 100%;
		}
		
			.pp_content_container .pp_left { padding-left: 20px; }
			.pp_content_container .pp_right { padding-right: 20px; }
		
			.pp_content_container .pp_details {
				float: left;
				margin: 10px 0 2px 0;
			}
				.pp_description {
					display: none;
					margin: 0;
				}
				
				.pp_social { float: left; margin: 0; }
				.pp_social .facebook { float: left; margin-left: 5px; width: 55px; overflow: hidden; }
				.pp_social .twitter { float: left; }
				
				.pp_nav {
					clear: right;
					float: left;
					margin: 3px 10px 0 0;
				}
				
					.pp_nav p {
						float: left;
						margin: 2px 4px;
						white-space: nowrap;
					}
					
					.pp_nav .pp_play,
					.pp_nav .pp_pause {
						float: left;
						margin-right: 4px;
						text-indent: -10000px;
					}
				
					a.pp_arrow_previous,
					a.pp_arrow_next {
						display: block;
						float: left;
						height: 15px;
						margin-top: 3px;
						overflow: hidden;
						text-indent: -10000px;
						width: 14px;
					}
		
		.pp_hoverContainer {
			position: absolute;
			top: 0;
			width: 100%;
			z-index: 2000;
		}
		
		.pp_gallery {
			display: none;
			left: 50%;
			margin-top: -50px;
			position: absolute;
			z-index: 10000;
		}
		
			.pp_gallery div {
				float: left;
				overflow: hidden;
				position: relative;
			}
			
			.pp_gallery ul {
				float: left;
				height: 35px;
				margin: 0 0 0 5px;
				padding: 0;
				position: relative;
				white-space: nowrap;
			}
			
			.pp_gallery ul a {
				border: 1px #000 solid;
				border: 1px rgba(0,0,0,0.5) solid;
				display: block;
				float: left;
				height: 33px;
				overflow: hidden;
			}
			
			.pp_gallery ul a:hover,
			.pp_gallery li.selected a { border-color: #fff; }
			
			.pp_gallery ul a img { border: 0; }
			
			.pp_gallery li {
				display: block;
				float: left;
				margin: 0 5px 0 0;
				padding: 0;
			}
			
			.pp_gallery li.default a {
				background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/facebook/default_thumbnail.gif) 0 0 no-repeat;
				display: block;
				height: 33px;
				width: 50px;
			}
			
			.pp_gallery li.default a img { display: none; }
			
			.pp_gallery .pp_arrow_previous,
			.pp_gallery .pp_arrow_next {
				margin-top: 7px !important;
			}
		
		a.pp_next {
			background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/btnNext.png) 10000px 10000px no-repeat;
			display: block;
			float: right;
			height: 100%;
			text-indent: -10000px;
			width: 49%;
		}
			
		a.pp_previous {
			background: url('.BASE_PATH.'libraries/prettyPhoto/images/prettyPhoto/light_rounded/btnNext.png) 10000px 10000px no-repeat;
			display: block;
			float: left;
			height: 100%;
			text-indent: -10000px;
			width: 49%;
		}
		
		a.pp_expand,
		a.pp_contract {
			cursor: pointer;
			display: none;
			height: 20px;	
			position: absolute;
			right: 30px;
			text-indent: -10000px;
			top: 10px;
			width: 20px;
			z-index: 20000;
		}
			
		a.pp_close {
			position: absolute; right: 0; top: 0; 
			display: block;
			line-height:22px;
			text-indent: -10000px;
		}
		
		.pp_bottom {
			height: 20px;
			position: relative;
		}
			* html .pp_bottom { padding: 0 20px; }
			
			.pp_bottom .pp_left {
				height: 20px;
				left: 0;
				position: absolute;
				width: 20px;
			}
			.pp_bottom .pp_middle {
				height: 20px;
				left: 20px;
				position: absolute;
				right: 20px;
			}
				* html .pp_bottom .pp_middle {
					left: 0;
					position: static;
				}
				
			.pp_bottom .pp_right {
				height: 20px;
				left: auto;
				position: absolute;
				right: 0;
				top: 0;
				width: 20px;
			}
		
		.pp_loaderIcon {
			display: block;
			height: 24px;
			left: 50%;
			margin: -12px 0 0 -12px;
			position: absolute;
			top: 50%;
			width: 24px;
		}
		
		#pp_full_res {
			line-height: 1 !important;
		}
		
			#pp_full_res .pp_inline {
				text-align: left;
			}
			
				#pp_full_res .pp_inline p { margin: 0 0 15px 0; }
	
		div.ppt {
			color: #fff;
			display: none;
			font-size: 17px;
			margin: 0 0 5px 15px;
			z-index: 9999;
		}
	</style>';
	
	}

 // end of galley functions file
 // in root/addons/gallery/includes/functions.php
?>
