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
$r = $r .''; #do not edit
require_once($r .'/includes/functions.php'); #do not edit
require_once($r .'/includes/resize_class.php'); 


#======================================================================
#						TEMPLATE ENDS
#======================================================================
echo '<style>
.bring-to-front{
position: relative;
z-index: 10000;
}
</style>';

#				 ADD YOUR CUSTOM ADDON CODE BELOW

# FILE UPLOADS

function upload_image($r='',$folder='', $instruction='', $comment_id='') {
	#$folder should end in a forward slash eg $folder = 'user/'
	global $r;

	if($r==='' && !url_contains('edit_')){
		$r = dirname(__FILE__);
		$r2 = str_ireplace('/regions/','',$r);
		$r = $r2;
		}
	
   $type = $_FILES['image_field']['type'];
   //~ echo $type; die();
   
$submit =  mysql_prep($_POST['submit']);

$destination_url = $_SESSION['current_url'];

$uploaddir = $r.'/uploads/files/';
$uploadfile = $uploaddir .$folder.'/'. basename($_FILES['image_field']['name']);

$m = str_ireplace('/regions/','',$uploadfile); // fixes a bugin upload_no_edit()
$uploadfile = $m;
//echo $uploadfile;
$path = BASE_PATH.'uploads/files/'.$folder.'/'. basename($_FILES['image_field']['name']);
$m = str_ireplace('/regions/','',$path);
$path = $m;
$rpath = $r.'/uploads/files/'.$folder.'/'. basename($_FILES['image_field']['name']);
$m = str_ireplace('/regions/','',$rpath); // fixes a bugin upload_no_edit()
$rpath= $m;

	
	# ONSUBMIT
	if ((($submit== 'upload' || $submit == 'Say it' ) && !empty($_FILES)) || isset($_POST['add_comment'])){
	//print_r($_POST);print_r($_FILES); //die();
	
   $name = basename($_FILES['image_field']['name']);
   
   $path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
   
   if($parent == ''){

   if (isset($_GET['page_name'])){
   $parent = trim(mysql_prep($_GET['page_name'])) ." page";
   
} elseif (isset($_GET['block_name'])){
   $parent = trim(mysql_prep($_GET['block_name'])) ." block";
} elseif(isset($_GET['section_name'])){
	$parent = trim(mysql_prep($_GET['section_name'])) ." section";
} elseif(isset($_GET['product_code'])){
	$parent = trim(mysql_prep($_GET['product_code'])) ." product";
} elseif(isset($_GET['fundraiser_name'])){
	$parent = trim(mysql_prep($_GET['fundraiser_name'])) ." fundraiser";
} elseif(isset($_GET['contest_name'])){
	$parent = trim(mysql_prep($_GET['contest_name'])) ." contest";
} elseif(isset($_GET['money_service_code'])){
	$parent = trim(mysql_prep($_GET['money_service_code'])) ." money_service";
} elseif(isset($_GET['company_name'])){
	$parent = trim(mysql_prep($_GET['company_name'])) ." company";
} else {$parent = "pic".$name; 
	$pic_mode = true;}

}

if(isset($_GET['tid'])){
	$parent_id = mysql_prep($_GET['tid']);
	} else {$parent_id = 0;}
	
   

if(isset($_POST['contest_entry_id'])){
	$contest_entry_id = mysql_prep($_POST['contest_entry_id']);
	} else {$contest_entry_id = 0;}
	
   

if(isset($_GET['contest_entry_id'])){
	$contest_entry_id = mysql_prep($_GET['contest_entry_id']);
	if(isset($_POST['is_comment'])){
		$is_comment = true;
		$comment_id = '0';
		} else {
			$is_comment = false;
			$comment_id = '0';
			}
	} else {$contest_entry_id = '0';}
	
	if(empty($comment_id)){
		$comment_id = 0;
		}
	if($type== 'image/jpeg' || $type== 'image/png'){
	$move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);
	} else { status_message('error','File type not allowed!'); }
	
	if($move ==1){
		
$newImg = imagecreatetruecolor($nWidth=500, $nHeight=500);
imagealphablending($newImg, false);
imagesavealpha($newImg,true);
$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);


		
		$parent2 = str_ireplace('#','%23',$parent);
		$small_path = resize_pic_small($pic=$rpath);
		$small_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $small_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		$medium_path = resize_pic_medium($pic=$rpath);
		$medium_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $medium_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		$large_path = resize_pic_large($pic=$rpath);
		$large_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $large_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		//die("file dey vex");
		
		if($pic_mode == true){
		$destination = BASE_PATH."?page_name={$page_name}&tid={$id}";
		$created = date('c');
		$author = $_SESSION['username'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `page`(`id`, `section_name`, `category`, `page_name`, `parent_id`, `page_type`, `menu_type`, `position`, `visible`, `content`, `created`, `last_updated`, `author`, `editor`, `allow_comments`, `promote_on_homepage`, `destination`) 
		VALUES ('0', 'none', '', '{$parent}', '{$parent_id}', 'page', 'none', '', '1', '', '{$created}', '', '{$author}', '', 'yes', 'no', '{$destination}')")
		 or die ("Page insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		 
		 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from page WHERE page_name='{$parent}' LIMIT 0,1");
		 while($result = mysqli_fetch_array($query)){
		 $parent_id = $result['id'];
		 $parent2 = $parent2 . ' page';
		 }
		 
		}
		if(isset($_GET['contest_entry_id'])){
			$contest_entry_id = mysql_prep($_GET['contest_entry_id']);
			$owner = trim(mysql_prep($_GET['reg_user']));
			$parent = 'GA-CTST-'.mysql_prep($_GET['contest_name']).'-'.mysql_prep($_GET['contest_entry_id']);
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `files`(`id`, `name`, `large_path`, `medium_path`, `small_path`, `original_path`, `parent`, `parent_id`, `type`,`destination_url`,`comment_id`,`contest_entry_id`,`owner`)
		 VALUES ('0', '{$name}', '{$large_path}', '{$medium_path}', '{$small_path}', '{$path}', '{$parent}', '{$parent_id}', '{$type}', '{$destination_url}', '0','{$contest_entry_id}','{$owner}')") 
		or die("Could not save image to DB!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	
		} else {

		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `files`(`id`, `name`, `large_path`, `medium_path`, `small_path`, `original_path`, `parent`, `parent_id`, `type`,`destination_url`,`comment_id`,`contest_entry_id`,`owner`)
		 VALUES ('0', '{$name}', '{$large_path}', '{$medium_path}', '{$small_path}', '{$path}', '{$parent2}', '{$parent_id}', '{$type}', '{$destination_url}', '{$comment_id}','{$contest_entry_id}','{$author}')") 
		or die("Could not save image to DB!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$_SESSION['last_upload'] = $name;
		session_message('success','File is valid, and was successfully uploaded.');
		
		}
		if(!$query) {echo "<div class='alert'>Error : No file uploaded!\n</div>"; } 
		redirect_to($destination_url);
	} 
	
}
//echo 'Here is some more debugging info:' .$_FILES['image_field']['error']; //testing
	
		if($_GET['page_name'] != 'home' && !isset($_GET['company_name'])  && !isset($_POST['is_comment'])){
# UPLOAD FORM
	echo '<h3> Add pictures to slider</h3><form action="'
	.$_SESSION['current_url'].'" method="post" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="contest_entry_id" value="'.$contest_entry_id.'" />
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
	<input type="file" size="500" name="image_field" value="">
	<input type="submit" name="submit" value="upload">
	</form>';
	echo '<em>' .$instruction .'</em>';
	
	show_page_images();
	show_free_images();
	upload_attachment();
	show_linked_attachments();
	}
}


function upload_attachment(){
	#$folder should end in a forward slash eg $folder = 'user/'
	global $r;
	$type = $_FILES['image_field']['type'];
	if($r==='' && !url_contains('edit_')){
		$r = dirname(__FILE__);
		$r2 = str_ireplace('/regions/','',$r);
		$r = $r2;
		}

	if($type == 'application/pdf'){
		//~ echo 'PDF here!'; die();
		$submit =  mysql_prep($_POST['submit']);
		$destination_url = $_SESSION['current_url'];
		$uploaddir = $r.'/uploads/files/';
		$uploadfile = $uploaddir .$folder.'/'. basename($_FILES['image_field']['name']);
		$m = str_ireplace('/regions/','',$uploadfile); // fixes a bugin upload_no_edit()
		$uploadfile = $m;
		//echo $uploadfile;
		$path = BASE_PATH.'uploads/files/'.$folder.'/'. basename($_FILES['image_field']['name']);
		$m = str_ireplace('/regions/','',$path);
		$path = $m;
		$rpath = $r.'/uploads/files/'.$folder.'/'. basename($_FILES['image_field']['name']);
		$m = str_ireplace('/regions/','',$rpath); // fixes a bugin upload_no_edit()
		$rpath= $m;


		# ONSUBMIT
		if (($submit== 'upload attachment') && !empty($_FILES)){
		//~ print_r($_POST);print_r($_FILES); die();

		$name = basename($_FILES['image_field']['name']);
		$path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

		if (isset($_GET['page_name'])){
		$parent = trim(mysql_prep($_GET['page_name'])) ." page";
		} elseif (isset($_GET['block_name'])){
		$parent = trim(mysql_prep($_GET['block_name'])) ." block";
		}

		if(isset($_GET['tid'])){
		$parent_id = mysql_prep($_GET['tid']);
		} else {$parent_id = 0;}


		if(empty($comment_id)){
		$comment_id = 0;
		}
		if($type== 'application/pdf'){
		$move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);
		} else { status_message('error','File type not allowed!'); }

	if($move ==1){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `files`(`id`, `name`, `large_path`, `medium_path`, `small_path`, `original_path`, `parent`, `parent_id`, `type`,`destination_url`,`comment_id`,`contest_entry_id`,`owner`)
		 VALUES ('0', '{$name}', '{$large_path}', '{$medium_path}', '{$small_path}', '{$path}', '{$parent2}', '{$parent_id}', '{$type}', '{$destination_url}', '{$comment_id}','0','{$author}')") 
		or die("Could not save image to DB!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$_SESSION['last_upload'] = $name;
		session_message('success','File is valid, and was successfully uploaded.');
		
		if(!$query) {
			echo "<div class='alert'>Error : No file uploaded!</div>"; 
			} 
		redirect_to($destination_url);
		}

		}	
	}
	
	echo '<h3> Add Attachment </h3><form action="'
	.$_SESSION['current_url'].'" method="post" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
	<input type="file" size="500" name="image_field" value="">
	<input type="submit" name="submit" value="upload attachment">
	</form>';
}

function show_linked_attachments(){
	if(isset($_GET['page_name']) ){
		$parent = trim(mysql_prep($_GET['page_name'])) ." page";
	}elseif(isset($_GET['block_name'])){
		$parent = trim(mysql_prep($_GET['block_name'])) ." block";
	}
	if(isset($_GET['tid'])){
		$parent_id = mysql_prep($_GET['tid']);
		}
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE (`parent_id`='{$parent_id}' ) 
	 or (`parent`='{$parent}' AND `destination_url`='{$destination_url}') ORDER BY `id` DESC") 
	 or die("Failed to get attachments!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 $num = mysqli_num_rows($query);
	 
	 if($num > 0){
		 echo '<div class="clear"><h3>Attachments</h3>';
		 while($result = mysqli_fetch_array($query)){
			 if($result['type'] == 'application/pdf'){
				echo '<a target="_BLANK" href="'.$result['original_path'] .'">'.$result['name'].'</a>
				<a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
	.'&delete_pic=' .$result['id'] 
	.'"><em> | <span class="tiny-text">delete </span></em></a><hr>';
				 }
			 }
		echo '</div>';
		}
	 
	}

function get_linked_image($subject_id='',$pic_size='',$limit='',$comment_id='',$has_zoom='',$for_slideshow=''){
	
	//~ if (empty($subject_id) && !empty($_GET['page_name'])){
   //~ $parent = trim(mysql_prep($_GET['page_name'])) ." page";
//~ } elseif(empty($subject_id) && !empty($_GET['block_name'])){
   //~ $parent = trim(mysql_prep($_GET['block_name'])) ." block";
//~ } elseif(isset($_GET['section_name'])){
	//~ $parent = trim(mysql_prep($_GET['section_name'])) ." section";
//~ } elseif(empty($subject_id) && !empty($_GET['fundraiser_name'])){
	//~ $parent = trim(mysql_prep($_GET['fundraiser_name'])) ." fundraiser";
//~ } elseif(empty($subject_id) && !empty($_GET['product_code'])){
	//~ $parent = trim(mysql_prep($_GET['product_code'])) ." product";
//~ } elseif(empty($subject_id) && !empty($_GET['contest_name'])){
	//~ $parent = trim(mysql_prep($_GET['contest_name'])) ." contest";
//~ } elseif(empty($subject_id) && !empty($_GET['company_name'])){
	//~ $parent = trim(mysql_prep($_GET['company_name'])) ." company";
//~ } 

	$contest_entry_id = mysql_prep($_GET['contest_entry_id']);
	
	 if ($limit !==''){
		 $sql_suffix = "LIMIT 0, {$limit}";
		 } else {$sql_suffix = '';}
		 
		 
$destination_url = $_SESSION['current_url'];
		 
	 $output = array();
	
	 if(isset($_GET['contest_entry_id'])){
		$parent = 'GA-CTST-'.mysql_prep($_GET['contest_name']).'-'.mysql_prep($_GET['contest_entry_id']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT DISTINCT * FROM `files` WHERE parent='{$parent}' AND contest_entry_id='{$contest_entry_id}' ORDER BY `id` DESC {$sql_suffix}") 
		or die('Error fetching contest entry photos '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 
	 }elseif(isset($_GET['tid']) && $_GET['tid'] != 0 && empty($comment_id)){
		 $subject_id = mysql_prep($_GET['tid']);
		 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT DISTINCT * FROM `files` WHERE parent_id='{$subject_id}' ORDER BY `id` DESC {$sql_suffix}") ; 
		 $num = mysqli_num_rows($query);
		 
	} else if(isset($_GET['tid']) && !empty($parent) && empty($subject_id) && !empty($comment_id)){
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE (`parent`='{$parent}' AND `comment_id`='{$comment_id}') ORDER BY `id` DESC {$sql_suffix}");
	} else if(!isset($_GET['tid']) && !empty($parent) && !empty($subject_id)){
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE (`parent`='{$parent}') or parent_id='{$subject_id}' ORDER BY `id` DESC {$sql_suffix}");
	} else if(!empty($subject_id) && empty($comment_id)){
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE (`parent_id`='{$subject_id}') ORDER BY `id` DESC {$sql_suffix}");
	 } else if(empty($subject_id) && !empty($contest_entry_id)){
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE (`parent_id`='{$parent}') AND contest_entry_id='{$contest_entry_id}' ORDER BY `id` DESC {$sql_suffix}");
	 } else if(empty($subject_id) && empty($comment_id)){
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE (`parent_id`='{$parent}') ORDER BY `id` DESC {$sql_suffix}");
	 } else {
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE (`destination_url`='{$destination_url}' AND `destination_url`!='') ORDER BY `id` DESC {$sql_suffix}");
	 } 
	//if($query){
		//echo "Images selected";
		//} else { echo "failed to select!";}
	# SET RETURN PATH 

	while($images_array= mysqli_fetch_array($query)) { 
		if(!empty($images_array['parent_id']) && $images_array['type'] != 'application/pdf'){
		$width ='';
		if($pic_size ==='large'){ 
			$image_sized = $images_array['large_path'];
		} else if($pic_size==='small'){ 
			$image_sized = $images_array['small_path'];
		} else if($pic_size=== 'medium'){ 
			$image_sized = $images_array['medium_path'];
		} else if($pic_size==='original'){ 
			$image_sized = $images_array['original_path'];
		} else if($pic_size==='half'){ 
			$image_sized = $images_array['medium_path'];
			$width = "35%";
		} else if($pic_size==='fit'){ 
			$image_sized = $images_array['medium_path'];
			//$width = 'width="100%"';
		} else { 
			$image_sized = $images_array['medium_path'];
		}
	
	if($has_zoom == 'true' ){
	$file .= "<a href='".$images_array['original_path']."' rel='prettyPhoto[".$subject_id.$parent.$comment_id."_gal]'>";
	}
	$file .='<img src="' .$image_sized .'" alt="'.$images_array['name'].'" class="inline-block thumbnail img-responsive" width="'.$width.'">';
	if($has_zoom=='true'){	
	$file .= '</a>';
	}
	if((is_file_owner($file_owner=$images_array['owner']) || is_admin())  && $for_slideshow != 'true' && $pic_size != 'half'){
		$file .= '<a href="'.$_SESSION['current_url'].'&delete_pic='.$images_array['id'].'" class="padding-5 tiny-text pull-right inline-block">delete pic</a>';
		}
	
	
	$output[] =$file;
	}
	}
	return $output;
}

function is_file_owner($file_owner=''){
	if(is_logged_in() && $_SESSION['username'] == $file_owner){
		return true;
		}
	}


function remove_file(){
	 
	 # DELETE ANY REMOVED FILES
	if(isset($_GET['delete_pic'])){
	$file_id= trim(mysql_prep($_GET['delete_pic']));
	} else if(isset($_GET['do_delete'])){
	$file_id= trim(mysql_prep($_GET['delete_pic']));
	}


	if($_GET['delete_pic'] && is_logged_in()){
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `large_path`, `medium_path`, `small_path`, `original_path` FROM `files` WHERE `id`='{$file_id}'")
		or die("Error fetching filepath ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$result = mysqli_fetch_array($query);
		$file_id = $result['id'];
		$original_file_path = $result['original_path'];
		$large_file_path = $result['large_path'];
		$medium_file_path = $result['medium_path'];
		$small_file_path = $result['small_path'];
		
		$lookup = strpos($large_file_path, 'large_size');
		if($lookup > 1){
			unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/files/large_size/'.$result['name']);
		} 
		$lookup = strpos($medium_file_path, 'medium_size');
		
		 if($lookup > 1){
			unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/files/medium_size/'.$result['name']);
		}
		$lookup = strpos($small_file_path, 'small_size');
		 if($lookup > 1){
			unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/files/small_size/'.$result['name']);
		}
		$lookup = strpos($original_file_path, '_size');
		 if($lookup < 1){ 
			unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/files/'.$result['name']);
		}
		
		$delete = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM files WHERE id='{$file_id}'") 
		or die("Could not delete images!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		
		if($delete){
			session_message('success','File / picture removed!');
			redirect_to($_SESSION['prev_url']);	
		}
		
	} 
}




function show_thumbnail($subject=''){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT medium_path from files where parent='{$subject}' order by id desc limit 0, 1")
	 or die("Something is wrong".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($result = mysqli_fetch_array($query)){
	echo "<img src='{$result['medium_path']}' width='50' height='50' hspace='10' vspace='10'>";
	}
}

function show_images_in_list($images='',$width='',$height=''){
	# $images should be an array of images

	if($images !== ''){
		
    
    foreach ($images as $image){    
		
		echo $image;
		}

	
		} 

}


function show_page_images(){
	$url = $_SESSION['current_url'];
	if (url_contains('edit')){
		if(isset($_GET['page_name'])){
		$parent = trim(mysql_prep($_GET['page_name'])) ." page";
		}elseif(isset($_GET['block_name'])){
		$parent = trim(mysql_prep($_GET['block_name'])) ." block";
		}elseif(isset($_GET['section_name'])){
		$parent = trim(mysql_prep($_GET['section_name'])) ." section";
		}elseif(isset($_GET['contest_name'])){
		$parent = trim(mysql_prep($_GET['contest_name'])) ." contest";
		}elseif(isset($_GET['product_code'])){
		$parent = trim(mysql_prep($_GET['product_code'])) ." product";
		} elseif(isset($_GET['fundraiser_name'])){
		$parent = trim(mysql_prep($_GET['fundraiser_name'])) ." fundraiser";
	}
	
	if(isset($_GET['tid']) && $_GET['tid'] != 0){
		 $parent_id = mysql_prep($_GET['tid']);
		 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT DISTINCT * FROM `files` WHERE parent_id='{$parent_id}' ORDER BY `id` DESC") 
		 or die("Failed to select images!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		 } else {
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE (`parent`='{$parent}' AND `destination_url`='{$destination_url}') or (`parent`='{$parent}' AND `destination_url`='') ORDER BY `id` DESC") 
	 or die("Failed to select images!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 }
	 
echo "<p align='center'><hr><big><strong>Uploaded Images</strong></big></p>";



 
# SET RETURN PATH 


while($images_array= mysqli_fetch_array($query)) { 
	if($images_array['type'] != 'application/pdf'){
	$pics = '<table><tr>
	<td></div><img src="' .$images_array['small_path'] .
	'" width="50" height="50" alt="image">&nbsp &nbsp<br>';
	
	$text = $images_array['name'];
	$wrapped_text = wordwrap($text,11,"<br> \n", true);
	
	$pics = $pics .$wrapped_text .'</td>
	<td><a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
	.'&delete_pic=' .$images_array['id'] 
	.'"><em> remove </em></a></td>
	
	<td></div></td>
	
	</tr></table><hr>';
	echo $pics;
	}
	}

}

}



function show_free_images(){
	$url = $_SESSION['current_url'];
	if (url_contains('uploads')){		
		echo "<p align='center'><hr><h1> All FREE Uploaded Images</h1></p>";	
		# Get  free uploaded images
		$parent = 'free';

		#echo "Parent is " .$parent .""; //Testing

		# GET RELATED FILES (CHILD IMAGES OR FILES)
		$images_result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from files WHERE `parent`='{$parent}' ")
		 or die("Failed to select images!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		
		$num = mysqli_num_rows($query);

		while($images_array= mysqli_fetch_array($images_result)) { 
			
			$pics = '<table><tr>
			<td></div><img src="' .$images_array['small_path'] .
			'" width="100" height="100" alt="image">&nbsp &nbsp<br>';
			
			$text = $images_array['name'];
			$wrapped_text = wordwrap($text,11,"<br> \n", true);
			
			$pics = $pics .$wrapped_text .'</td>
			<td><a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] 
			.'?delete_pic=' .$images_array['id'] 
			.'"><em> remove </em></a></td>
			
			<td></div></td>
			
			</tr></table><hr>';
			echo $pics;
			
			}
			if(empty($num)){
				echo "No free images <br><hr>";
				}


	}


}

function resize_pic_small($pic='',$option='auto'){
	global $r;
	$width=50; 
	$height=50;
	$dest_folder= $r.'uploads/files/small-size/'. basename($_FILES['image_field']['name']);
	$m = str_ireplace('regions/','',$dest_folder); // fixes a bugin upload_no_edit()
	$dest_folder = $m;
	$output = BASE_PATH.'uploads/files/small-size/'. basename($_FILES['image_field']['name']);
	/**$folder is the folder name, eg thumbnail, medium etc
	 * $option is one of : exact, portrait, landscape, auto, crop
	 * */
	
	
	// USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 90);

return $output;
	
}

function resize_pic_medium($pic='',$option='auto'){
	global $r;
	$width=240; 
	$height=240;
	$dest_folder= $r.'uploads/files/medium-size/'. basename($_FILES['image_field']['name']);
	$m = str_ireplace('regions/','',$dest_folder); // fixes a bugin upload_no_edit()
	$dest_folder = $m;
	$output = BASE_PATH.'uploads/files/medium-size/'. basename($_FILES['image_field']['name']);
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

function resize_pic_large($pic='',$option='auto'){
	global $r;
	$width=500; 
	$height=500;
	$dest_folder= $r.'uploads/files/large-size/'. basename($_FILES['image_field']['name']);
	$m = str_ireplace('regions/','',$dest_folder); // fixes a bugin upload_no_edit()
	$dest_folder = $m;
	$output = BASE_PATH.'uploads/files/large-size/'. basename($_FILES['image_field']['name']);
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

function show_files_listing($start='',$stop=''){
	
	if(empty($start)){$start = 0;}
	if(empty($stop)){$stop = 50;}
	
	if(isset($_GET['start'])){$start = trim(mysql_prep($_GET['start']));}
	if(isset($_GET['stop'])){$stop = trim(mysql_prep($_GET['stop']));}
	
	global $r;
	$dir= $r.'uploads/files/';
	$files = scandir($dir);
	
	//echo $dir."small-size/".$delete;
	//do requested delete
	if(!empty($_GET['delete'])){
		$delete = trim(mysql_prep($_GET['delete']));
		$rm = unlink($dir.$delete);
		unlink($dir."large-size/".$delete);
		unlink($dir."medium-size/".$delete);
		unlink($dir."small-size/".$delete);
		
		if($rm){
			status_message("success", "File deleted successfully!");
			} else { status_message("error", "Unlink failed!");}
		}
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` order by id DESC LIMIT {$start},{$stop}") 
	or die("Error Fetching files " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$start = $stop;
	
	$num = mysqli_num_rows($query);
	
	//refresh $files
	$files = scandir($dir);
	echo "<h1>Files Listing</h1>
	<table class='table'><thead><th>id</th>
	<th>Preview</th><th>Filename</th>
	</thead><tbody>";
	while($result = mysqli_fetch_array($query)){
		
		if(in_array($result['name'],$files,TRUE)){
			echo "<tr><td>{$result['id']}</td><td><a href='".$result['large_path']."'><img src='".$result['small_path']."'></a></td><td>".$result['name']."<br>
			<strong>Appears in: </strong> <em>".$result['parent']."</em></td></tr>";
			if(($key = array_search($result['name'], $files)) !== false) {
			unset($files[$key]);
			}
		} else{
			echo "<tr><td>{$result['id']}</td><td><a href='".$result['large_path']."'><img src='".$result['small_path']."'></a></td><td>".$result['name']."<br>
			<strong>Appears in: </strong> <em>".$result['parent']."</em></td></tr>";
			}
			
	}
	echo "</tbody></table>";
	
	if(empty($num)){
		status_message("alert","There are no more files !");
		}
	echo "<a href='".BASE_PATH."uploads?start={$start}&stop={$stop}'><button>Show me more</button></a> ";
	echo "<a href='".BASE_PATH."uploads'><button>Reset</button></a> ";
	
	
	
	echo "<br><hr><h1>Unused files</h1>";
	
	foreach($files as $file){
		if(strpos($file,'_gallery') === false){
			if($file !== '.' && $file !== '..' && is_file($dir.$file)){
			echo "<hr><a href='".BASE_PATH."uploads/files/large-size/{$file}'><img src='".BASE_PATH."uploads/files/small-size/{$file}'></a>&nbsp;&nbsp;
			<a href='".BASE_PATH."uploads?delete=".$file."&control=".$_SESSION['control']."'><em>delete</em></a>";
			}
		}
	}
	
}



 function upload_no_edit($allow=''){
	 
	 $is_mobile = check_user_agent('mobile');
	  if((is_author() || is_admin() || $allow == true)
	 && is_logged_in() 
	 && !url_contains('page_name=home') 
	 && !url_contains('page_name=sections') 
	 && !url_contains('section_name=')){
		 
			 echo "<div id='pic-toggle' style='background-color: whitesmoke;'>
		 <span class='text-center gainsboro inline-block' id='add-picture'>Add media to this post</span><span id='pic-close' style='background-color: gainsboro; padding: 5px; cursor: pointer'> Close x</span><br>
		 <div class='content'>";
		 
		 upload_image();
		
			 echo "</div></div>";
		// show_page_images();
		 
	}
}

 // end of uploads functions file
 // in root/uploads/includes/functions.php
 ?>
