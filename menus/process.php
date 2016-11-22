<?php require_once('../includes/session.php');
#=======================================================================
#					- Template starts
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
?>

<!-- START PAGE -->

<?php start_addons_page();#from root/inludes/functions.php
//print_r($_POST);
#					- Template Ends -
#=======================================================================

if($_SESSION['role']==='admin' || $_SESSION['role']==='manager'){	
	
	echo '<div class="top-left-links">
		<ul>
			<li id="add_page_form_link" class="float-right-lists">
				<a href="'.BASE_PATH .'menus/add">Add Menu </a></li>
			<li id="show_blocks_form_link" class="float-right-lists">
				<a href="'.BASE_PATH .'menus/"> List menus</a> </li>
		</ul>
	</div>';


go_back();

if(isset($_POST['menu_item_name'])){
		$name = trim(mysql_prep($_POST['menu_item_name']));
		}

	if(isset($_POST['menu_type'])){
		$type = trim(mysql_prep($_POST['menu_type']));
		}
	if(isset($_POST['destination'])){
		$destination = trim(mysql_prep($_POST['menu_destination']));
		}
	if(isset($_POST['redirect_to'])){
		$redirect_to = trim(mysql_prep($_POST['redirect_to']));
		}
	if(isset($_POST['position'])){
		$position = trim(mysql_prep($_POST['position']));
		}
	if(isset($_POST['visible'])){
		$visible = trim(mysql_prep($_POST['visible']));
		}
	if(isset($_POST['parent'])){
		if($_POST['parent'] !== ''){
		$parent = trim(mysql_prep($_POST['parent']));
		}else{ $parent = '';
	}
}
		
if(isset($_POST['add_menu_item'])){
	//print_r($_POST);
	 menu_item_create($name,$type,$destination,$parent);

}
		
		

if(isset($_POST['id_holder']) && $_POST['edit_menu_item'] === 'Save'){
	
	$parent_menu_id = trim(mysql_prep($_POST['parent_menu']));
	if(!empty($parent_menu_id)){
	$is_child = 'yes';
	$position = '50';
	}else {
		$position = mysql_prep($_POST['position']);
		}
	$id = trim(mysql_prep($_POST['id_holder']));
	$menu_item_name = strtolower(trim(mysql_prep($_POST['menu_item_name'])));
	$menu_type = trim(mysql_prep($_POST['menu_type']));
	
	$visible = trim(mysql_prep($_POST['visible']));
	$parent = trim(mysql_prep(strtolower($_POST['parent'])));

		
	$destination_string = trim(mysql_prep($_POST['menu_destination']));
	$dest_replace = str_ireplace('http://','',$destination_string);
	$dest_replace1 = str_ireplace('ADDONS_PATH/',ADDONS_PATH,$dest_replace);
	$dest_replace2 = str_ireplace('BASE_PATH/',ADDONS_PATH,$dest_replace1);
	if(!string_contains($dest_replace2,'http://')){
		$dest_replace2 = 'http://'.$dest_replace2;
		}
	
	
	
	$save_edit = trim(mysql_prep($_POST['edit_menu_item']));

	$edit_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE menus SET `menu_item_name`='{$menu_item_name}', menu_type='{$menu_type}', `position`='{$position}', `visible`='{$visible}', `destination`='{$dest_replace2}', `parent`='{$parent}', `is_parent`='{$is_parent}', `is_child`='{$is_child}', `parent_menu_id`='{$parent_menu_id}' 
	WHERE `id`='{$id}'") or die("Edit query failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if(!empty($parent_menu_id)){
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE menus SET is_parent='yes' WHERE id='{$parent_menu_id}'") 
	or die('Failed to update menu is_parent' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	
		}
	if($edit_query) { session_message('success', 'Menu item edited successfully!'); }
	redirect_to($redirect_to);
	


}

if(isset($_GET['menu_name']) && isset($_GET['menu_item_delete'])){
	$id = trim(mysql_prep($_GET['menu_item_delete']));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menus` WHERE `id`='{$id}'");
	if($query){ session_message('success', 'menu item deleted successfully!'); }
	redirect_to($redirect_to);
}

echo "<hr>";




} else { deny_access(); }
?>



