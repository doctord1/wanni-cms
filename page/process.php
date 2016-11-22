<?php 
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
$_SESSION['addon_home']='';
 start_addons_page();
########################################################################
?>

<!-- START PAGE -->


<?php

echo ' <section class="top-left-links">
		<ul>
			<li id="add_page_form_link" class="float-right-lists">
			' .'<a href="'.BASE_PATH .'page"> Back to PAGES </a> </li>
		</ul>
		</section>';


# ADD PAGES
show_session_message();
process_post_submission();



function process_submission(){
# Add pages form processing
$id = mysql_prep($_POST['id']); 
if(empty($parent_id)){
	$id  = 0;
	}
$page_name = str_ireplace(' ','-',trim(mysql_prep(strtolower(($_POST['page_name']))))) ;

$page_type = trim(mysql_prep(strtolower($_POST['page_type']))) ;
$parent_id = trim(mysql_prep($_POST['parent_id'])) ;
if(empty($parent_id)){
	$parent_id  = 0;
	}

//print_r($_POST); 
//print_r($_GET); 
//print_r($_FILES); 

if(empty($_POST['visible'])) {
$visible = 0;}
else{
$visible = 1;
}


$action = htmlentities($_POST['action']);
$updated = htmlentities($_POST['updated']);
$submitted = trim(mysql_prep($_POST['submitted']));
$content1 = urlencode($_POST['content']);
$content = trim(mysql_prep($content1));

$position = htmlentities($_POST['position']);
$section_name = trim(mysql_prep($_POST['section']));
if(isset($_POST['category'])){
$category = trim(mysql_prep($_POST['category']));
}
$deleter = $_GET['action'];
$sent_delete = $_GET['deleted'];
$parent = $page_type;
$menu_type = $_POST['menu_type'];

$author = $_SESSION['username'];
$editor = $_SESSION['username'];
$back_url = $_POST['back_url'];
$created = date('c');
$last_updated = date('c');

$start_date = trim(mysql_prep($_POST['start_date']));
$end_date = trim(mysql_prep($_POST['end_date']));
$add_page_type = $_POST['add_page_type'];
$delete_page_type = $_POST['delete_page_type'];
$page_type = $_POST['page_type'];
if(isset($_POST['allow_comments'])){
$allow_comments = 'yes';
} 
if(isset($_POST['promote'])){
$promote_on_homepage = 'yes';
} else { $promote_on_homepage = 'no'; }



//process discussions

if(isset($_POST['submit_discussion']) && $_POST['submitted'] == 'Add Page'){
$page_type = 'discussion';
$page_name1 = str_ireplace('+','-',substr($content,0,50) ."...");
$page_name = str_ireplace('#','',$page_name1);
$allow_comments = 'yes';
}

if(isset($page_type) && $page_type !== 'page'
&& $page_type !== 'blog' && $page_type !== 'notice' && $page_type !== 'discussion'){
	$destination = BASE_PATH."addons/{$page_type}/?{$page_type}_name={$page_name}";
	}
	
	if($page_type === 'blog'
	|| $page_type === 'notice' 
	|| $page_type === 'discussion'
	|| $page_type === 'page'
	|| empty($page_type)){
	$destination = BASE_PATH."?page_name={$page_name}&tid={$id}";
	}

	if($page_type === 'contest'){
	$destination = BASE_PATH."addons/{$page_type}/?{$page_type}_name={$page_name}&contest=yes";	
	$duration = calculate_duration();
	}




if ($_POST['submitted'] == 'Add Page' && $action ==='insert'){
	if(!empty($_FILES['image_field'])){

//process photos
	global $r;

	if($r==='' && !url_contains('edit_')){
		$r = dirname(__FILE__);
		$r2 = str_ireplace('/regions/','',$r);
		$r = $r2;
		}
$submit =  $_POST['submit'];

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
	
		//print_r($_POST); die();
		
   $type = $_FILES['image_field']['type'];
   $name = basename($_FILES['image_field']['name']);
   
   $path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
   
   }
   
if(isset($_GET['tid'])){
	$parent_id = mysql_prep($_GET['tid']);
	}
	
   if(!empty($_FILES['image_field'])){
   $parent = "pic".$name .date('d/m/Y'); 
	$pic_mode = true;
	$move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);
	
	}
  
//  echo 'Wanni';
	if($move ==1){
		$page_name = $parent;
		$parent2 = str_ireplace('#','%23',$parent);
		$small_path = resize_pic_small($pic=$rpath);
		$small_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $small_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		$medium_path = resize_pic_medium($pic=$rpath);
		$medium_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $medium_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		$large_path = resize_pic_large($pic=$rpath);
		$large_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $large_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		die("file dey vex");
		
		if($pic_mode == true){
			
		$destination = BASE_PATH."?page_name={$page_name}&tid={$id}";
		$created = date('c');
		$author = $_SESSION['username'];
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `page`(`id`, `section_name`, `category`, `page_name`, `parent_id`, `page_type`, `menu_type`, `position`, `visible`, `content`, `created`, `last_updated`, `author`, `editor`, `allow_comments`, `promote_on_homepage`, `destination`) 
		VALUES ('0', 'none', '', '{$parent}', 0, 'page', 'none', 0, '1', '', '{$created}', 0, '{$author}', 0, 'yes', 'no', '{$destination}')")
		 or die ("Page insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		 
		 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from page WHERE page_name='{$parent}' LIMIT 0,1");
		 while($result = mysqli_fetch_array($query)){
			$parent_id = $result['id'];
			if($query){ 
			$destination = BASE_PATH."?page_name={$page_name}&tid={$parent_id}";
				}
		
			}
		
		 $q = "UPDATE page SET section_name='{$section_name}' ,category='{$category}' ,page_name='{$page_name}',";

				$q = $q ." page_type='{$page_type}',";
			
			$q = $q ."  menu_type='{$menu_type}', 
			position=0, visible='{$visible}', content='{$content}', 
			last_updated='{$last_updated}', editor='{$editor}', 
			allow_comments='{$allow_comments}', promote_on_homepage='{$promote_on_homepage}', 
			destination='{$destination}' WHERE id='{$parent_id}'"; 
			
			$update_query = mysqli_query($GLOBALS["___mysqli_ston"], $q) or die("page UPDATE failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			 $file_parent = $page_name . ' page';
	
		 
		}
		//echo "<div class='success'>File is valid, and was successfully uploaded.\n</div>";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `files`(`id`, `name`, `large_path`, `medium_path`, `small_path`, `original_path`, `parent`, `parent_id`, `type`)
		 VALUES ('0', '{$name}', '{$large_path}', '{$medium_path}', '{$small_path}', '{$path}', '{$parent2}', '{$parent_id}', '{$type}')") 
		or die("Could not save image to DB!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$_SESSION['last_upload'] = $name;
		
		 //echo $file_parent; 
			$update_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE files SET parent='{$file_parent}' WHERE parent_id='{$parent_id}' limit 1") or die("file UPDATE failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	

} else {
		$insert_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `page`(`id`, `section_name`, `category`, `page_name`, `parent_id`, `page_type`, `menu_type`, `position`, `visible`, `content`, `created`, `last_updated`, `author`, `editor`, `allow_comments`, `promote_on_homepage`, `destination`) 
		VALUES ('0', '{$section_name}', '{$category}', '{$page_name}', '{$parent_id}', '{$page_type}', '{$menu_type}', '0', '{$visible}', '{$content}', '{$created}', '{$last_updated}', '{$author}', '{$editor}', '{$allow_comments}', '{$promote_on_homepage}', '{$destination}')")
		 or die ("Database insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	
	if(!empty($start_date) && $page_type === 'contest'){
		$contest_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `contest`(`id`, `contest_name`, `author`, `editor`, `total_votes`, `created`, `last_updated`, `start_date`, `end_date`) 
		VALUES ('','{$page_name}','{$author}','{$editor}','','{$created}','{$edited}','{$start_date}','{$end_date}')") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
		
	
	
	
	
	
	 if($insert_query  || $update_query) {
		 
			#process hashtags
			if(addon_is_active('hashtags')){
				$string = trim(mysql_prep($_POST['content']));
				process_hashtags($string,$path=$destination);
				}	
	 
		 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id` FROM page WHERE page_name='{$page_name}' ORDER BY id DESC LIMIT 1") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		 $result = mysqli_fetch_array($query);
		  activity_record(
					$parent_id = $result['id'],
					$actor=$author,
					$action=" created the {$page_type}",
					$subject_name = $page_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= BASE_PATH .'?page_name=' .$page_name,
					$date=$created,
					$parent='page'
					);
					
		//echo "<div align='center'><h2>Go to - <a href='".BASE_PATH."?page_name={$page_name}'>{$page_name}</a></h2></div>";
		session_message('success', "{$page_type} saved successfully!");
		
		
		}
	if($menu_type !== 'none' && !empty($menu_type)){
	$insert_menu = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `menus`(`id`, `menu_item_name`, `menu_type`, `position`, `visible`, `destination`, `parent`) 
	VALUES ('0','{$page_name}', '{$menu_type}', '{$position}', '{$visible}', '{$destination}', '{$parent}')")	
	or die("FAiLEd to insert menu item!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	//echo "i am about to redirect"; die();
	//redirect_to($destination);
}	



# Edit form processing

if($updated ==='Save page'){
    echo 'update';
		
			$q = "UPDATE page SET section_name='{$section_name}' ,category='{$category}' ,page_name='{$page_name}',";

				$q = $q ." page_type='{$page_type}',";
			
			$q = $q ."  menu_type='{$menu_type}', 
			position={$position}, visible={$visible}, content='{$content}', 
			last_updated='{$last_updated}', editor='{$editor}', 
			allow_comments='{$allow_comments}', promote_on_homepage='{$promote_on_homepage}', 
			destination='{$destination}' WHERE id='{$id}' LIMIT 1"; 
			
			$update_query = mysqli_query($GLOBALS["___mysqli_ston"], $q) or die("Database UPDATE failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
	 
	 if($update_query) {
		 
			#process hashtags
			if(addon_is_active('hashtags')){
				$string = trim(mysql_prep($_POST['content']));
				process_hashtags($string,$path=$destination);
				}
	 	 	 
		  activity_record(
					$parent_id = $result['id'],
					$actor=$author,
					$action=" updated the {$page_type}",
					$subject_name = $page_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= BASE_PATH .'?page_name='. $page_name,
					$date=$created,
					$parent='page'
					);
					

		//echo "<div align='center'><h2>Go to - <a href='".BASE_PATH."?page_name={$page_name}'>{$page_name}</a></h2></div>"; 
		session_message('success', 'Page saved successfully! <br> Return to - <a href="'.BASE_PATH.'?page_name='.$page_name.'&tid='.$id.'">'.$page_name.'</a>');		
		
	 }	
	 $update_menu = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `menus` SET `menu_item_name`='{$page_name}',`menu_type`='{$menu_type}',`position`='{$position}',`visible`='{$visible}',`destination`='{$destination}',`parent`='{$parent}' WHERE `menu_item_name`='{$page_name}'") 
	 or die("FAiled to UPDATE menu" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	  
	 
		redirect_to($destination);
}
#echo "deleter = " .$deleter ."<br> And sent_delete = " .$sent_delete ;   //testing

  // Now we check if delete is requested  
if($_GET['action'] == 'delete_page' && isset($_GET['tid']) && $_GET['deleted'] =='jfldjff7'){
	
	//echo 'i dey here';
	$del_page_name= $_GET['page_name'];
	$id = $_GET['tid'];
	//echo " id is " . $id . ' and delete button was pressed'; // testing
	$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from page WHERE id='{$id}' AND page_name='{$del_page_name}'") 
	or die('<div class="alert">Could not delete the specified page!</div>') . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	
} else 

if(isset($_POST['deleted'])){
		
	$del_page_name= $page_name;
	$id = $_POST['id'];
	//echo " id is " . $id . ' and delete button was pressed'; // testing
	$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from page WHERE id='{$id}'") 
	or die('<div class="alert">Could not delete the specified page!</div>') . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
}
	
	if($delete_query) {
		//echo "<div class='success'> Page '" .$del_page_name ."' deleted successfully!!</div><br>";
		redirect_to($_SESSION['prev_url']);
	}
	$delete_menu_query=mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menus` WHERE `menu_item_name`='{$del_page_name}'") 
	or die("Menu item deletion failed1" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));


if($add_page_type){
	
	$clear = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `page_type` WHERE `page_type_name`='{$page_type}'");
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `page_type`(`id`,`page_type_name`) VALUES('0','{$page_type}')")
	or die("Page type  Insert failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if($q){
		session_message('success','Page type ADDED successfully');
		}
		redirect_to($_SESSION['prev_url']);
	}
	
if($delete_page_type){
	$clear = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `page_type` WHERE `page_type_name`='{$page_type}'") 
	or die("FAiled to delete page type " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if($clear){
		session_message('success','Page type DELETED successfully');
		}
		redirect_to($_SESSION['prev_url']);
}
}
//go_back(BASE_PATH);
?>

