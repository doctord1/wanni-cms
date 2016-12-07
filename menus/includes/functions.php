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

# MENU functions

function add_menu(){
	$add_menu = $_POST['add_menu'];
	$menu_type_name = $_POST['menu_name'];
	
	if($add_menu){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `menu_type`(`id`, `role_id`, `menu_type_name`) 
	VALUES ('', '{$role_id}', '{$menu_type_name}')") or die ("Menu creation failed!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	
	if($query){
		status_message('success', 'Menu successfully created!');
		}
		
	$add_menu_form = '<form action="'.$_SERVER['PHP_SELF'] .'" method="post">
					Menu Name: <input type="text" name="menu_name" placeholder="Type in Menu name here">
					<input type="submit" value="Add menu" name="add_menu" class="submit">
					</form><br>';
	echo $add_menu_form;
	
}
	
	
function list_menus(){
	$url = $_SESSION['current_url'];
	
	if(url_contains('menus') && !query_string_in_url() && is_admin()){
	
		$list_menus = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `menu_type`") or die("Menu selection failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
			$list = "<ol><br>"; 
			while($row = mysqli_fetch_array($list_menus)){
				$list .=  "<li> <big><a href='" .BASE_PATH ."menus?menu_name=" .$row['menu_type_name']  ."'>".ucfirst($row['menu_type_name']) ."</a></big>" ;
				$list .=  "<span class='u-pull-right'><a href='".BASE_PATH ."menus?delete=".$row['id'] ."'".'><em>Delete</em></a></span></li><hr>';
				
				} $list .=  "</ol><br>";
				
				echo $list; go_back();
					}
}

function menu_item_delete($name,$type,$parent){ //deletes menu items NOT MENUS
#check for permission
if($_SESSION['role'] ==='admin' || $_SESSION['role'] ==='manager'){
	
	#then check url value
	if(isset($_GET['delete']) && url_contains('?menu_name=item_delete=')){
		
		$id = $_GET['delete'];
		$delete_menu = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menu_type` WHERE `id`='{$id}'") 
		or die("Menu deletion failed") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		
	} else if($name !=='' && $type !=='' && $parent !==''){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menus` WHERE `menu_item_name`='{$name}' AND `menu_type`='{$type}' && `parent`='{$parent}'") 
		or die("Failed to delete Menu item " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	
	if($delete_menu){			
			status_message("message-notification", "Menu item deleted!");
			}
	}	
}
	
	
function delete_menu(){
#check for permission
if($_SESSION['role'] ==='admin' || $_SESSION['role'] ==='manager'){
	
	#then check url value
	if(isset($_GET['delete'])){
		
		$id = $_GET['delete'];
		$delete_menu = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menu_type` WHERE `id`='{$id}'") 
		or die("Menu deletion failed") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		
		if($delete_menu){
			
			status_message("success", "Menu deleted!");
			}
		}	
	}
}
	


function update_parent_menu_item_status($menu_item_id){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id FROM menus WHERE parent_menu_id='{$menu_item_id}'") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	
	if(empty($num)){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE menus SET is_parent='no' WHERE id='{$menu_item_id}'")
		or die('failed to update menu is_parent status'.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
		
	}	
	
# MENU ITEM functions
	
function list_menu_items(){
	
	$url = $_SESSION['current_url'];
	if(isset($_GET['menu_name']) && false === url_contains('&edit_menu_item=')){
		
		if(is_admin()){
			
			$menu_type_name = htmlentities($_GET['menu_name']);
			$items = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `menus` WHERE `menu_type`='{$menu_type_name}' AND is_child!='yes' ORDER BY `position` ASC") 
			or die ("Failed to get menu items") .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			
		if($items){ 
			go_back();
			$list = "<h2> ".ucfirst($menu_type_name) . " menu items</h2> <ol>";
				while($row = mysqli_fetch_array($items)){
					update_parent_menu_item_status($row['id']);
					$list .= "<li><big> <a href='" . strtolower($row['destination'])  ."'>".ucfirst($row['menu_item_name']) ."</a></big>" ;
					$list = $list . "<div class='u-pull-right'><a href='".$url."&edit_menu_item=".$row['id'] ."'".'><em>Edit item</em></a>' .
					"&nbsp &nbsp| &nbsp &nbsp<a href='".BASE_PATH ."menus/process.php?menu_name=". $_GET['menu_name'] ."&menu_item_delete=".$row['id'] ."'".'><em>Delete item</em></a></div></li><hr>';
					if($row['is_parent'] == 'yes'){
					$list2 = get_child_menu_items($row['id']);
					$list .=$list2;
					}
				} 
				$list .=  "</ul></ol>";
				echo $list;
				$_SESSION['temp_container'] = '';
			}
		}
	}
	
}

function get_child_menu_items($parent_menu_id){
	$url=$_SESSION['current_url'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `menus` WHERE parent_menu_id='{$parent_menu_id}' ORDER BY `position` ASC") 
			or die ("Failed to get child menu items" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			while($result = mysqli_fetch_array($query)){
				$suffix = '</ul>';
				
				$_SESSION['temp_container'] .= "<ul><li><big> <a href='" . strtolower($result['destination'])  ."'>".ucfirst($result['menu_item_name']) ."</a></big>
						<div class='u-pull-right'><a href='".$url."&edit_menu_item=".$result['id'] ."'".'><em>Edit item</em></a>' .
						"&nbsp &nbsp| &nbsp &nbsp<a href='".BASE_PATH ."menus/process.php?menu_name=". $_GET['menu_name'] ."&menu_item_delete=".$result['id'] ."'".'><em>Delete item</em></a></div></li><hr>';

					if($result['is_parent'] == 'yes'){
						$_SESSION['temp_container'] .= get_children_children_menu_items($result['id']);	
					}
					$_SESSION['temp_container'] .= '</ul>';
				}
				
				//echo $_SESSION['menu_child_level'] ."|";
			return $_SESSION['temp_container'];	
	}

function get_children_children_menu_items($parent_menu_id){
	$url=$_SESSION['current_url'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `menus` WHERE parent_menu_id='{$parent_menu_id}' ORDER BY `position` ASC") 
			or die ("Failed to get child menu items" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			while($result = mysqli_fetch_array($query)){
				$_SESSION['menu_child_level'] += 1;
					$_SESSION['temp_container'] .= "<ul><li><big> <a href='" . strtolower($result['destination'])  ."'>".ucfirst($result['menu_item_name']) ."</a></big>
						<div class='u-pull-right'><a href='".$url."&edit_menu_item=".$result['id'] ."'".'><em>Edit item</em></a>' .
						"&nbsp &nbsp| &nbsp &nbsp<a href='".BASE_PATH ."menus/process.php?menu_name=". $_GET['menu_name'] ."&menu_item_delete=".$result['id'] ."'".'><em>Delete item</em></a></div></li><hr>';
						if($result['is_parent'] == 'yes'){
							
						$_SESSION['temp_container'] .= get_children_children_menu_items($result['id']);
						$_SESSION['temp_container'] .= '</ul>';
					}
				}
				$_SESSION['temp_container'] .= '</ul>';
			
	}

function add_menu_item($name='', $type='', $menu_destination=''){
	$type = trim(mysql_prep($_GET['menu_name']));
	$add_menu_item_form =  "<h2>Add Menu item</h2>
		<form action='./process.php' method='post'>
		Menu item name: <br><input type='text' name='menu_item_name' value='' placeholder='menu item name'><br>
		<input type='hidden' name='redirect_to' value='{$_SESSION['current_url']}'>
		Menu type: <br><input type='text' name='menu_type' value='{$type}'><br>";
		
		$add_menu_item_form .= "
		
		Position :<br><select name='position'>
		<option value='0'>0</option>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10'>10</option>
		</select><br>
		
		Parent Menu item: <br><select name='parent_menu'>
		<option value='0' selected>None</option>";
		$menu_options = return_menu_items($type);
		foreach($menu_options as $option){
		$add_menu_item_form .= $option;
		}
		$add_menu_item_form .= "</select><br>
			
		Visible :<br><select name='visible'>
		<option value='0'>No</option>
		<option value='1'>Yes</option>
		</select><br>
		Destination :<br><input type='text' name='path'><br>
		<em>It is recommended you type out the full link path </em>
		<br><input type='submit' name='add_menu_item' class='submit' value='Add'></form>";

		if (isset($type) && false === url_contains('&edit_menu_item=')){		
				echo $add_menu_item_form;
				
				}
}		

	
	
	
function edit_menu_item(){
	$url = $_SESSION['current_url'];
	$type = trim(mysql_prep($_GET['menu_name']));
	if(url_contains('&edit_menu_item=')){
		
		$id = trim(mysql_prep($_GET['edit_menu_item']));
					
		$select_query=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `menus` WHERE `id`='{$id}' LIMIT 1") 
		or die("Menu item selection failed") .mysql_error;
		
		if($select_query){
			$row = mysqli_fetch_array($select_query);
			go_back();
		$edit_form = "<h2>Edit ".$row['menu_item_name'] ."</h2><hr><br>
					<form action='".BASE_PATH."menus/process.php' method='post'>
					<input type='hidden' name='id_holder' value='" .$id ."'>
					<input type='hidden' name='redirect_to' value='" .$_SESSION['prev_url'] ."'>
					Menu item name: <br><input type='text' name='menu_item_name' value='" .$row['menu_item_name'] ."'>
					<br>Menu type: <br><input type='text' name='menu_type' value='" .$row['menu_type'] ."'>
					<br><em>Valid menu type values include 'primary', 'secondary', 'user', or 'none'</em><br>
					<br>Position : (<em>higher numbers come last</em>)<br>
					<select name='position'>
					<option value='0'>0</option>
					<option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
					<option value='6'>6</option>
					<option value='7'>7</option>
					<option value='8'>8</option>
					<option value='9'>9</option>
					<option value='10'>10</option>
					</select><br>
					
		Parent Menu item: <br><select name='parent_menu'>
		<option value='0' selected>None</option>";
		$menu_options = return_menu_items($type);
		foreach($menu_options as $option){
		$edit_form .= $option;
		}
		$edit_form .= "</select><br>
			
					
					Visible :<br><select name='visible'>
					<option value='0'>No</option>
					<option value='1' selected>Yes</option>
					</select><br>
					Destination :<br><input type='text' name='menu_destination' value='".$row['destination'] ."'>
					<br><em>(It is recommended that you type out the full link path .)</em>
					<br><input type='submit' name='edit_menu_item' class='submit' value='Save'></form>";
					
			# continue here			
		
		} echo $edit_form;
	}
 }

	
function menu_item_create($name='',$type='',$destination='',$parent=''){
	$redirect_to = trim(mysql_prep($_POST['redirect_to']));
	$menu_item_name = trim(mysql_prep($name));
	$menu_type = trim(mysql_prep($type));
	if(!empty($parent)){
		//~ $destination = BASE_PATH.'page?page_name='.$name;
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from page WHERE page_name='{$name}' LIMIT 0,1");
		$result = mysqli_fetch_array($query);
		$parent_id = $result['id'];
		if($query){ 
		$destination_url = BASE_PATH."?page_name={$name}&tid={$parent_id}";
		}
		}
	$position = '5';
	$visible = trim(mysql_prep($_POST['visible']));
	//~ $destination_string = trim(mysql_prep($_POST['path']));
	//~ $dest_replace = str_ireplace('http://','',$destination_string);
	//~ $dest_replace1 = str_ireplace('ADDONS_PATH/',ADDONS_PATH,$dest_replace);
	//~ $dest_replace2 = str_ireplace('BASE_PATH/',ADDONS_PATH,$dest_replace1);
	$parent = trim(mysql_prep($parent));
	$parent_menu_id = trim(mysql_prep($_POST['parent_menu']));
	if(empty($parent_menu_id)){
		$parent_menu_id = '0';
		}
	$is_parent = '';
	$is_child = '';
	
	
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `menus`(`id`, `menu_item_name`, `menu_type`, `position`, `visible`, `destination`, `parent`, `is_parent`,`is_child`, `parent_menu_id`) 
	VALUES ('0', '{$menu_item_name}', '{$menu_type}', '{$position}', '{$visible}', '{$destination_url}', '{$parent}','{$is_parent}', '{$is_child}','{$parent_menu_id}')") ;
	//~ or die ("Add menu item failed! o" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if(!empty($parent_menu_id)){
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE menus SET is_parent='yes' WHERE id='{$parent_menu_id}'") 
	or die('Failed to update menu is_parent' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	
		}
	if($q){
	session_message('success', "Menu item added successfully!");
	redirect_to(BASE_PATH.'page');
	}
}

//  Get and set the top menu
function get_top_menu_items(){
if(is_logged_in()){
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM menus where `menu_type`='primary' and visible='1' ORDER BY `position` ASC") or die("Failed to select sections!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
 
  $is_opera_mini = check_user_agent('opera mini');
  $is_mobile = check_user_agent('mobile');
 
 if(is_logged_in() && !url_contains('addons/')){
  echo '<div class="top_menu_items" id="toggle-sidebar"><img src="'.BASE_PATH.'uploads/files/default_images/menu24.png"></div>';
 }
 
   $menu_parents = array();
   $menu_children =array();  
   $n = 0; 
   $n2=0;
   $menu_parent_ids= array();
   $menu_children_parent_ids= array();
  while($result = mysqli_fetch_array($query)) {
	 
	 if($result['is_parent'] != 'no' && empty($result['parent_menu_id'])){
		 $id = $result['id'] ;
		
		$menu_parents["{$id}"] = '<li data-toggle="dropdown" data-target="#" class="dropdown-toggle menu top_menu_items"><a href="' .$result['destination']. '">' .strtoupper($result['menu_item_name']). '</a><b class=" caret" ></b></li>';			
		$menu_parent_ids[] = $id;
		//$n++;
		}
	if($result['is_parent'] != 'yes' && empty($result['parent_menu_id'])){
		$id =$result['id'] ;
		$menu_parents["{$id}"] = '<li class="top_menu_items"><a href="' .$result['destination']. '">' .strtoupper(str_ireplace('-',' ',$result['menu_item_name'])). '</a></li>';
		$menu_parent_ids[] = $id;
		//$n++;
		}	
	if(!empty($result['parent_menu_id'])){
		$id2 = $result['id'] ;
		$menu_children["{$id2}"] = '<li class="top_menu_items"><a href="' .$result['destination']. '">' .strtoupper(str_ireplace('-',' ',$result['menu_item_name'])). '</a></li>';
		$menu_children_parent_ids["{$id2}"] = $result['parent_menu_id'];
		//$n2++;
		}	
		$n++;
	}
	//echo '<div class="dropdown">';
	$n = 0; $n2=0;
	foreach($menu_parents as $key => $menu){
	   echo $menu;
	   }
	
	echo '<menu class="dropdown-menu">';
	foreach($menu_children_parent_ids as $key => $value){
		if(array_key_exists($value,$menu_parents)){
		echo $menu_children["{$key}"];
		}
	   }
	echo '</menu>';
	
	//echo '</div>';
	if($is_opera_mini){
		$class = "greet";
	}else{
		$class = "greet cd-btn";
	}
	
	
    
    if(isset($_SESSION['username'])) { # Shows greeting
    $greet = "Hello " .$_SESSION['username'];
    $output = $output."<div class='top-menu-items {$class}'><a href='".BASE_PATH."user?user={$_SESSION['username']}'>".$greet ."! <i class='glyphicon glyphicon-user'></i></a></div>";
    echo $output .'';  
     } 
}
}

function get_top_bootstrap_menu_items(){
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM menus where `menu_type`='primary' and visible='1' ORDER BY `position` ASC") or die("Failed to select sections!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
 
  $is_opera_mini = check_user_agent('opera mini');
  $is_mobile = check_user_agent('mobile');
  
  if(is_logged_in() && !url_contains('addons/')){
  echo '<div class="top_menu_items" id="toggle-sidebar">&laquo;</div>';
 }
 echo'<nav role="navigation" class="navbar navbar-default">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">';
  show_logo();
  echo '</div>';
  echo '
  <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="top_menu_items navbar-toggle padding-20">
  <span class="sr-only">Toggle navigation</span> 
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  </button>';
  echo '<div id="navbarCollapse" class="collapse navbar-collapse">
  ';
 
 
   $menu_parents = array();
   $menu_children =array();  
   $n = 0; 
   $n2=0;
   $menu_parent_ids= array();
   $menu_children_parent_ids= array();
   
      
   
  while($result = mysqli_fetch_array($query)) {
	 
	 if($result['is_parent'] != 'no' && empty($result['parent_menu_id'])){
		 $id = $result['id'] ;
		
		$menu_parents["{$id}"] = '<li  data-toggle="dropdown" data-target="#" class="dropdown-toggle top_menu_items"><a href="' .$result['destination']. '">' .strtoupper($result['menu_item_name']). '</a><b class=" caret" ></b></li>';			
		$menu_parent_ids[] = $id;
		//$n++;
		}
	if($result['is_parent'] != 'yes' && empty($result['parent_menu_id'])){
		$id =$result['id'] ;
		$menu_parents["{$id}"] = '<li class="top_menu_items"><a href="' .$result['destination']. '">' .strtoupper(str_ireplace('-',' ',$result['menu_item_name'])). '</a></li>';
		$menu_parent_ids[] = $id;
		//$n++;
		}	
	if(!empty($result['parent_menu_id'])){
		$id2 = $result['id'] ;
		$menu_children["{$id2}"] = '<li class="top_menu_items"><a href="' .$result['destination']. '">' .strtoupper(str_ireplace('-',' ',$result['menu_item_name'])). '</a></li>';
		$menu_children_parent_ids["{$id2}"] = $result['parent_menu_id'];
		//$n2++;
		}	
		$n++;
	}
	//echo '<div class="dropdown">';
	$n = 0; $n2=0;
	foreach($menu_parents as $key => $menu){
	   echo $menu .'';
	   }
	
	echo '<menu class="dropdown-menu">';
	foreach($menu_children_parent_ids as $key => $value){
		if(array_key_exists($value,$menu_parents)){
		echo $menu_children["{$key}"];
		}
	   }
	echo '</menu>';
	
	//echo '</div>';
	if($is_opera_mini){
		$class = "greet";
	}else{
		$class = "greet cd-btn";
	}
	
	
    
    if(isset($_SESSION['username'])) { # Shows greeting
    $greet = "Hello " .$_SESSION['username'];
    $output = $output."<div class='top-menu-items {$class}'><a href='".BASE_PATH."user?user={$_SESSION['username']}'>".$greet ."! <i class='glyphicon glyphicon-user'></i></a></div>";
    echo $output .'</div></nav>';  
     } 
}


// 9. Get secondary menu items
function get_secondary_menu_items(){ 
	if(is_logged_in()){
	$is_mobile = check_user_agent('mobile');
	$result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM menus WHERE menu_type='secondary' and visible=1 order by position asc") or die("Failed to select sections!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
 
	$output = "<div class='secondary_menu_items'>"; 
	while($secondary_menu= mysqli_fetch_array($result)) {
		
		# GET LINK TYPE
		  
	
	$output = $output .'<a href="' .$secondary_menu['destination']. '">' .strtoupper($secondary_menu['menu_item_name']). '</a>&nbsp &nbsp';	
	
 
    }
  
    $output .= "<span class='u-pull-left'><a href='".BASE_PATH."page/add'>&nbsp +ADD NEW&nbsp</a>&nbsp&nbsp</span>";
	
	
    if(is_admin()){
		$output = $output .'<a href="'.BASE_PATH.'admin"> ADMIN </a> &nbsp';	
	} 
	 
    if(addon_is_active('messaging') && is_logged_in()){
		$unread = display_unread_messages();
		// if($is_mobile){ // yes is mobile
			$output = $output .'<a href="'.ADDONS_PATH.'messaging"> INBOX <span class="badge"> '.$unread['count'] .'</span></a> &nbsp';
		//	} else { // no is not mobile
		//	$output = $output .'<span class="menu u-pull-left"><span><a href="#"> INBOX <span class="badge"> '.$unread['count'] .'</span></a> &nbsp;&nbsp;</span></span>' ;
		//		}
		
	}
	 
	 $url = "";
	 
    if(isset($_SESSION['username'])) { # logout link
	 $output = $output . '&nbsp<span class="u-pull-right"><a href="'.BASE_PATH.'user/logout.php"> LOGOUT</a> </span>';
		} else { 
			if($is_mobile){ // yes is mobile
			$output = $output .'<span><a href="'.BASE_PATH.'user"> LOGIN</a> </span>' .'<a href="'.BASE_PATH.'user/?action=register">&nbsp;SIGNUP</a> </div>&nbsp;&nbsp;</div>'; 
			} else { // no is not mobile
			$output = $output .'<span class="menu"><span><a href="'.BASE_PATH.'user"> LOGIN</a> &nbsp;&nbsp;<div class="login-popout dropit-submenu"> </div></span></span>' .'<a href="'.BASE_PATH.'user/?action=register">&nbsp;SIGNUP</a> </div>&nbsp;&nbsp;</div>'; 	
				}
		}
   echo $output;
}
}


function get_menu_items($menu){
	if($menu == 'user'){
		if(is_logged_in()){	
		 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT menu_item_name, destination FROM menus where `menu_type`='{$menu}' and visible='1' ORDER BY `position` ASC") or die("Failed to get {$menu} menu!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			while($result = mysqli_fetch_array($query)){
			echo '<a href="'.$result['destination'].'"><div class="margin-3 padding-10">'.ucfirst($result['menu_item_name']).'</div></a>';
			}
		}	
	}
}

function return_menu_items($menu_type){
	$output = array();
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id, menu_item_name FROM menus where `menu_type`='{$menu_type}' and visible='1' ORDER BY `position` ASC") or die("Failed to get {$menu} menu!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($result = mysqli_fetch_array($query)){
		$output[] = '<option value="'.$result['id'].'">'.$result['menu_item_name'].'</option>';
		
		}
		return $output;
	}

function show_sidebar_settings_menu(){
	 $addons_number = 0;
	 if(is_logged_in() && is_admin()){
		$result = mysqli_query($GLOBALS["___mysqli_ston"], "Select * FROM addons ") or die("Cannot get addons List!"). ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		
		echo "<div><span class='sweet_title'>Settings</span>";
		while($row = mysqli_fetch_array($result)){
			$clean_name = str_ireplace('_',' ',$row['addon_name']);
			$addons_number++;
			if($row['core']==='yes'){
				echo "<div class='sidebar-button'><a href='" .BASE_PATH .$row['addon_name'] ."'>" .
				ucfirst($clean_name) ."</a> | ".
				"<a href='" .BASE_PATH .$row['addon_name'] ."/config'><span class='u-pull-right tiny-text'> Configure </span></a></div>";
			} else {
				echo "<div class='sidebar-button'><a href='" .ADDONS_PATH .$row['addon_name'] ."'>" .
				ucfirst($clean_name) ."</a> | " .
				"<a href='" .ADDONS_PATH .$row['addon_name'] ."/config'><span class='u-pull-right tiny-text'> Configure </span></a> </div>";
				}
		} echo '</div>';
	} else {  }
}


 // end of menu functions file
 // in root/menu/includes/functions.php
?>
