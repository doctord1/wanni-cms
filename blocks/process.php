<?php ob_start(); 
// 	LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
?>

<!-- START PAGE -->

<?php start_addons_page(); 
echo "<br> <br>";
# ADD BLOCK

$id = $_POST['id'];	 
$action = htmlentities($_POST['action']);
$block_name1 = trim(mysql_prep($_POST['block_name']));
$collapsed = mysql_prep($_POST['collapsible']);
$block_name =  str_replace(' ','-',$block_name1);
$block_title = trim(mysql_prep($_POST['block_title']));
$show_title = trim(mysql_prep($_POST['show_title']));
$visible = trim(mysql_prep($_POST['visible']));
$submitted = htmlentities($_POST['submitted']);
$updated = mysql_prep($_POST['updated']);
$content1 = urlencode($_POST['content']);
$content = trim(mysql_prep($content1));
$position = trim(mysql_prep($_POST['position']));
$description = trim(mysql_prep($_POST['description']));
$parent_addon = 'admin';
$region = mysql_prep($_POST['region']); 
$function_call = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['function_call']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
$page_visibility = trim(mysql_prep($_POST['page_visibility']));
$role_visibility = trim(mysql_prep($_POST['role_visibility']));
if(empty($role_visibility)){
	$role_visibility = 'all';
	}
$deleter = $_GET['action'];
$sent_delete = $_GET['deleted'];


if (isset($submitted) && $action ==='insert') {
	
if ($block_name === ''){ echo "<br><br><div class='error'>Block name is required!</div>" ;}
else {
	$insert_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `blocks`
	(`id`, `block_name`, `region`, `block_title`, `block_description`, 
	`position`, `content`, `function_call`, `parent_addon`, 
	`show_title`, `page_visibility`, `role_visibility`, `collapsed`) VALUES ('0', '{$block_name}', '{$region}', '{$block_title}', 
	'{$description}', '{$position}', '{$content}', 
	'{$function_call}', '{$parent_addon}', '{$show_title}', '{$page_visibility}', '{$role_visibility}', '{$collapsed}')") or die("Database insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
}

if($insert_query) {
	status_message("success", 'block saved successfully!');	
}

# BACK LINK
echo '<section class="top-left-links">
		<ul>
			<li id="add_page_form_link" class="float-right-lists">
			' .'<a href="'.BASE_PATH .'blocks"> Back to BLOCKS </a> </li>
		</ul>
	</section>';

	
# UPDATE BLOCKS
if($_POST['updated'] == 'Save Block' && isset($_POST['id'])){
		
	if ($block_name === ''){ echo "<br><br><div class='error'>Block name is required!</div>" ; }
	else {
	$update_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE blocks SET block_name='{$block_name}', region='{$region}', block_title='{$block_title}', 
	block_description='{$description}', position='{$position}', content='{$content}', show_title='{$show_title}', 
	page_visibility='{$page_visibility}', role_visibility='{$role_visibility}', collapsed='{$collapsed}'" .
	"WHERE id='{$id}'") or die("Database UPDATE failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	
}	
if($update_query) {
	status_message("success", "Block updated successfully!");
	redirect_to($_POST['ref_page']);
}	

# DELETE BLOCKS
#echo "deleter = " .$deleter ."<br> And sent_delete = " .$sent_delete ;   //testing

  // Now we check if delete is requested  
if(isset($deleter) && $sent_delete ==='jfldjff7'){
	
	$del_block_name= $_GET['block_name'];
	#echo " id is " . $del_page_name . ' and delete button was pressed'; // testing
	$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from blocks WHERE block_name='{$del_block_name}'") 
	or die('<div class="alert">Could not delete the specified page!</div>') . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	}
	
if($delete_query) {
	status_message("success"," Page '" .$del_block_name ."' deleted successfully!!");
}
?>
